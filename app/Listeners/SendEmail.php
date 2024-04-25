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
     * @param  \App\Events\BookingProcessed  $event
     * @return void
     */
    public function handle(BookingProcessed $event)
    {
        $booking = $event->booking;
        $user = User::FindOrFail($booking->target_id);
        $name = $user->name;
        $email = $user->email;
        Mail::to("dumyduyen02@gmail.com")->send(new BookingMail($name));
    }
}
