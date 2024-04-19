<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Booking;
use App\Models\Group;
use App\Models\User;

final readonly class BookingMutation
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        // TODO implement the resolver
    }

    public function createBookingByUser(null $_, array $args){  
        $user = User::findOrFail($args['userId']);
        $newBooking = new Booking();
        $newBooking->check_in_date = $args['checkInDate'];
        $newBooking->check_out_date = $args['checkOutDate'];
        $newBooking->total_price =  0;
        return $user->bookings()->save($newBooking);
         
    }

    public function createBookingByGroup(null $_, array $args){
        $groupId = $args['groupId'];
        $checkInDate = $args['checkInDate'];
        $checkOutDate = $args['checkOutDate'];
        $totalPrice = 0;

        $user = User::findOrFail($args['userId']);
        if($user->groups()->where('groups.id',$groupId )->exists()){
            $group = Group::findOrFail($args['groupId']);
            
            # Validate if another user of the group already booked in the current date
            $currentDate = date('Y-m-d');
            if(Booking::where('target_id', $groupId)
                    ->whereDate('created_at', [$currentDate])
                    ->exists()) return;

            $newBooking = new Booking();
            $newBooking->check_in_date = $checkInDate;
            $newBooking->check_out_date = $checkOutDate;
            $newBooking->total_price =  $totalPrice;
            return $group->bookings()->save($newBooking);        
           
        }  
      
    }
}
