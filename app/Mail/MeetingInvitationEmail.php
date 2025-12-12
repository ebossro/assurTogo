<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Police;

class MeetingInvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $police;
    public $dateRendezVous;

    public function __construct(Police $police)
    {
        $this->police = $police;
        $this->dateRendezVous = $police->date_rendez_vous;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitation à votre rendez-vous de validation - AssurTogo',
        );
    }
    
    public function content(): Content
    {
        return new Content(
            view: 'emails.meeting_invitation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
