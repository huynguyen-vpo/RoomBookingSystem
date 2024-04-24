<?php

namespace App\GraphQL\Queries;

use App\Models\Booking;

final class BookingQuery
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        // TODO implement the resolver
    }

    public function filter(null $_, array $args){
        $booking = Booking::query();
  
        if (isset($args['filter'])) {
            $filter = $args['filter'];
          if (isset($filter['checkInDate'])) {
            $booking->whereDate('check_in_date', $filter['checkInDate']);
          }
    
          if (isset($filter['checkOutDate'])) {
              $booking->whereDate('check_out_date', $filter['checkOutDate']);
          }
    
          if (isset($filter['targetId'])) {
              $booking->where('target_id', $filter['targetId']);
          }
         }   
          return $booking->get();
      }
}
