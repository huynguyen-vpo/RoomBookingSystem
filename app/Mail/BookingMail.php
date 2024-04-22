<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\LaravelDriver\MailerSendTrait;

class BookingMail extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Your Reservation Is Confirmed!',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.booking-email',
            with: ['name' => $this->name],
        );
    }
   
}
