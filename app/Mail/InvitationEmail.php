<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invitation;
    public $url;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
        // Create the signed/tokenized URL
        $this->url = route('invitations.register', ['token' => $invitation->token]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have been invited to join Clinical Pro',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invitation',
        );
    }
}