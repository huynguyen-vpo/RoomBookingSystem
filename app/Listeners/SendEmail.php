<?php

namespace App\Listeners;

use App\Events\BookingProcessed;
use App\Mail\BookingMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingProcessed $event): void
    {
        $booking = $event->booking;
        $user = User::FindOrFail($booking->target_id);
        $name = $user->name;
        $email = $user->email;
        Mail::to($email)->send(new BookingMail($name));
    }
}
