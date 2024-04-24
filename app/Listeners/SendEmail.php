<?php

namespace App\Listeners;

use App\Mail\BookingMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $booking = $event->booking;
        $user = User::FindOrFail($booking->target_id);
        $name = $user->name;
        $email = $user->email;
        Mail::to($email)->send(new BookingMail($name));
    }
}
