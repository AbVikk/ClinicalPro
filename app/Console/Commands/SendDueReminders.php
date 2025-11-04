<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder;
use App\Notifications\ReminderNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     * The signature is what we call when scheduling.
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     */
    protected $description = 'Checks the database for and sends all due pending reminders.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting reminder check...');
        
        // 1. Find all reminders that are 'pending' AND are scheduled NOW or in the PAST minute.
        // We look for reminders scheduled anytime up to the current minute.
        $dueReminders = Reminder::where('status', 'pending')
            // Find reminders where the scheduled time is less than or equal to NOW
            ->where('scheduled_at', '<=', Carbon::now()) 
            ->get();

        if ($dueReminders->isEmpty()) {
            $this->info('No reminders are due.');
            return Command::SUCCESS;
        }

        $this->info("Found {$dueReminders->count()} reminders due.");

        // 2. Process each due reminder
        foreach ($dueReminders as $reminder) {
            try {
                // The target user to notify
                $user = $reminder->user; 
                
                if ($user) {
                    // Send the notification using the target user's Notifiable trait
                    $user->notify(new ReminderNotification($reminder));
                    
                    // Update the reminder status to prevent sending it again
                    $reminder->status = 'sent';
                    $reminder->save();
                    
                    $this->line("Sent reminder #{$reminder->id} to user {$user->id}.");
                } else {
                    $this->error("Error: Reminder #{$reminder->id} has no valid target user.");
                    $reminder->status = 'dismissed'; // Mark as dismissed if user is invalid
                    $reminder->save();
                }

            } catch (\Exception $e) {
                Log::error("Failed to send reminder #{$reminder->id}: " . $e->getMessage());
            }
        }
        
        $this->info('Reminder check complete.');
        return Command::SUCCESS;
    }
}