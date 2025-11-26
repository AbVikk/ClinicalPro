<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $otp;
    public $action; // e.g., "Registration" or "Password Reset"

    public function __construct($otp, $action = 'Verification')
    {
        $this->otp = $otp;
        $this->action = $action;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->action} Code - Clinical Pro",
        );
    }

    public function content(): Content
    {
        // We will use a simple markdown or blade view
        return new Content(
            view: 'emails.otp',
        );
    }
}