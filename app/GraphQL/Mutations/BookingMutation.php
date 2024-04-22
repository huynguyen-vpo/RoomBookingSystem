<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Events\BookingProcessed;
use App\Models\AvailableQuantity;
use App\Models\BookedRoomDay;
use App\Models\Booking;
use App\Models\Group;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;


final readonly class BookingMutation
{

    
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        // TODO implement the resolver
    }



    public function createBookingByUser(null $_, array $args){  
        $numberOfPeople = $args['numberOfPeople'];
        $singleNumber = $args['singleNumber'];
        $doubleNumber = $args['doubleNumber'];
        $tripleNumber = $args['tripleNumber'];
        $quarterNumber = $args['quarterNumber'];
        $checkInDate = $args['checkInDate'];
        $checkOutDate = $args['checkOutDate'];
        
        $singleTypeId = RoomType::where('type', 'single')->first()->id;
        $doubleTypeId = RoomType::where('type', 'double')->first()->id;
        $tripleTypeId = RoomType::where('type', 'triple')->first()->id;
        $quarterTypeId = RoomType::where('type', 'quarter')->first()->id;

        if(!$this->validateNumberOfRoom($numberOfPeople, $singleNumber, $doubleNumber, $tripleNumber,  $quarterNumber, $checkInDate,  $checkOutDate)){
            return false;
        }

        # Add booking for user
        $user = User::findOrFail($args['userId']);
        $newBooking = new Booking();
        $newBooking->check_in_date = $args['checkInDate'];
        $newBooking->check_out_date = $args['checkOutDate'];
        $newBooking->total_price =  0;
        $user->bookings()->save($newBooking);
        $bookingId = $newBooking->id;

        // Send email to client after creating booking
        event(new BookingProcessed($newBooking));
      
        # Add booked room days
        $arrayDates = $this->getDatesFromRange($checkInDate, $checkOutDate, 'Y-m-d');
        foreach($arrayDates as $key => $value){
            $this->addBookedRoomDays($bookingId , $value, $singleTypeId,  $singleNumber);
            $this->addBookedRoomDays($bookingId , $value, $doubleTypeId,  $doubleNumber);
            $this->addBookedRoomDays($bookingId , $value, $tripleTypeId,  $tripleNumber);
            $this->addBookedRoomDays($bookingId , $value, $quarterTypeId,  $quarterNumber);
        }
    
        return $newBooking;
        
    }

    public function createBookingByGroup(null $_, array $args){
        $groupId = $args['groupId'];
        $numberOfPeople = $args['numberOfPeople'];
        $singleNumber = $args['singleNumber'];
        $doubleNumber = $args['doubleNumber'];
        $tripleNumber = $args['tripleNumber'];
        $quarterNumber = $args['quarterNumber'];
        $checkInDate = $args['checkInDate'];
        $checkOutDate = $args['checkOutDate'];
        $totalPrice = 0;

        $singleTypeId = RoomType::where('type', 'single')->first()->id;
        $doubleTypeId = RoomType::where('type', 'double')->first()->id;
        $tripleTypeId = RoomType::where('type', 'triple')->first()->id;
        $quarterTypeId = RoomType::where('type', 'quarter')->first()->id;

        if(!$this->validateNumberOfRoom($numberOfPeople, $singleNumber, $doubleNumber, $tripleNumber,  $quarterNumber, $checkInDate,  $checkOutDate)){
            return false;
        }

        $user = User::findOrFail($args['userId']);
        if($user->groups()->where('groups.id',$groupId )->exists()){
            $group = Group::findOrFail($args['groupId']);
            
            # Validate if another user of the group already booked in the current date
            $currentDate = date('Y-m-d');
            if(Booking::where('target_id', $groupId)
                    ->whereDate('created_at', [$currentDate])
                    ->exists()) return;

            # Add booking for group
            $newBooking = new Booking();
            $newBooking->check_in_date = $checkInDate;
            $newBooking->check_out_date = $checkOutDate;
            $newBooking->total_price =  $totalPrice;
            $group->bookings()->save($newBooking); 
            $bookingId = $newBooking->id;       
           
            # Add booked room days
            $arrayDates = $this->getDatesFromRange($checkInDate, $checkOutDate, 'Y-m-d');
            foreach($arrayDates as $key => $value){
                $this->addBookedRoomDays($bookingId , $value, $singleTypeId,  $singleNumber);
                $this->addBookedRoomDays($bookingId , $value, $doubleTypeId,  $doubleNumber);
                $this->addBookedRoomDays($bookingId , $value, $tripleTypeId,  $tripleNumber);
                $this->addBookedRoomDays($bookingId , $value, $quarterTypeId,  $quarterNumber);
            }
            return  $newBooking;
        }  
        return false;
      
    }

    public function validateNumberOfRoom($numberOfPeople, $singleNumber, $doubleNumber, $tripleNumber,  $quarterNumber, $checkInDate,  $checkOutDate){
        // Validate capacity of booking
        if( $numberOfPeople > $singleNumber +  $doubleNumber*2 +  $tripleNumber*3 + $quarterNumber*4){
            return;
        }
        // Validate available rooms
        $arrayDates = $this->getDatesFromRange($checkInDate, $checkOutDate, 'Y-m-d');
        DB::beginTransaction();
        foreach($arrayDates as $key => $value){
            $availabelQuantity = AvailableQuantity::whereDate('date', $value)->first();
            $singleRemainingQuantity = $availabelQuantity->value('single_remaining_quantity');
            $doubleRemainingQuantity = $availabelQuantity->value('double_remaining_quantity');
            $tripleRemainingQuantity = $availabelQuantity->value('triple_remaining_quantity');
            $quarterRemainingQuantity = $availabelQuantity->value('quarter_remaining_quantity');
      
            if( ($singleNumber >  $singleRemainingQuantity)
                || ($doubleNumber >  $doubleRemainingQuantity)
                || ($tripleNumber >  $tripleRemainingQuantity)
                || ($quarterNumber >  $quarterRemainingQuantity)){
                    DB::rollBack();
                    return false;
                }  
            
            $availabelQuantity->update(['single_remaining_quantity' => $singleRemainingQuantity - $singleNumber,
            'double_remaining_quantity' => $doubleRemainingQuantity - $doubleNumber,
            'triple_remaining_quantity' => $tripleRemainingQuantity - $tripleNumber,
            'quarter_remaining_quantity' => $quarterRemainingQuantity - $quarterNumber]);
                 
        }
        DB::commit();
        return true;
        
    }

    public function getDatesFromRange($start, $end, $format = 'Y-m-d'){
        $start = Carbon::parse($start);
        $end = Carbon::parse($end)->addDay();
        $arrayDates = array();
        $period = new DatePeriod($start, new DateInterval('P1D'), $end); 
        foreach($period as $date) {                  
            $arrayDates[] = $date->format($format);  
        } 
        return $arrayDates; 
    }

    public function addBookedRoomDays($bookingId, $bookingDate, $roomTypeId, $numberOfRoom){
        $availableRoomIdOfType = $this->getAvailableRoomIdOfType($bookingDate, $roomTypeId);
        for($i = 0; $i < $numberOfRoom; $i++){
            $bookedRoomDay = new BookedRoomDay();
            $bookedRoomDay->booking_id = $bookingId;
            $bookedRoomDay->room_id = $availableRoomIdOfType[$i];
            $bookedRoomDay->booking_date = $bookingDate;
            $bookedRoomDay->price_per_day = 0;
            $bookedRoomDay->save();
        }
    }

    public function getAvailableRoomIdOfType($bookingDate, $roomTypeId){
        $allRoomsIdOfType = Room::where('room_typeid', $roomTypeId)
                            ->pluck('id')
                            ->toArray();
        $bookedRoomIdOfType = BookedRoomDay::whereDate('booking_date', $bookingDate)
                                                    ->whereRelation('room.type','id', $roomTypeId)
                                                    ->pluck('id')
                                                    ->toArray();;                                         
        $availableRoomIdOfType = array_diff($allRoomsIdOfType, $bookedRoomIdOfType);
        return   $availableRoomIdOfType;                                      
    }


}
