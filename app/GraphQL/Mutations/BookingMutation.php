<?php

namespace App\GraphQL\Mutations;

use App\Events\BookingProcessed;
use App\Exceptions\CustomException;
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
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Throwable;

final class BookingMutation
{
    /**
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function createBookingByUser($_, array $args){
        $userId = Auth::id();
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
    
        $user = User::findOrFail($userId);

        try{
            DB::beginTransaction();
            $this->validateNumberOfRoom($numberOfPeople, $singleNumber, $doubleNumber, $tripleNumber,  $quarterNumber, $checkInDate,  $checkOutDate);


            # Add booking for user
            $newBooking = new Booking();
            $newBooking->check_in_date = $args['checkInDate'];
            $newBooking->check_out_date = $args['checkOutDate'];
            $newBooking->total_price =  0;
            $newBooking->user_id = $user->id;
            $user->bookings()->save($newBooking);
            $bookingId = $newBooking->id;      

            # Add booked room days
            $arrayDates = $this->getDatesFromRange($checkInDate, $checkOutDate, 'Y-m-d');
            foreach($arrayDates as $key => $value){
                $this->addBookedRoomDays($bookingId , $value, $singleTypeId,  $singleNumber);
                $this->addBookedRoomDays($bookingId , $value, $doubleTypeId,  $doubleNumber);
                $this->addBookedRoomDays($bookingId , $value, $tripleTypeId,  $tripleNumber);
                $this->addBookedRoomDays($bookingId , $value, $quarterTypeId,  $quarterNumber);
            }

            // Send email to client after receiving booking
            // dispatch(new SendBookingConfirmationEmailJob($user->id));
            event(new BookingProcessed($newBooking));
            return $newBooking;
            DB::commit();
        }
        catch(Throwable $e){
            DB::rollBack();
            throw new CustomException($e->getMessage());
        }        
        
    }

    public function createBookingByGroup($_, array $args){
    
        $executed = RateLimiter::attempt(
            'booking-by-group:'.$args['groupId'],
            $perMinute = 1,
            function() {
                return true;
            }
        );
        if (! $executed) {
            throw new CustomException(
            'Bad request by rate limiter: Another user in your group already booked');
        }
    
        $userId = Auth::id();
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


        $user = User::findOrFail($userId);
        if($user->groups()->where('groups.id',$groupId )->exists()){
            $group = Group::findOrFail($args['groupId']);

             # Validate if another user of the group already booked in the current date
             $currentDate = date('Y-m-d');
             if(Booking::where('target_id', $groupId)
                     ->whereDate('created_at', [$currentDate])
                     ->exists()){
                 throw new CustomException(
                     'Bad request: Another user in your group already booked'); 
            }
        }
        else{
            throw new CustomException(
                'User is not in group');  
        }

        try{
            DB::beginTransaction();
            $this->validateNumberOfRoom($numberOfPeople, $singleNumber, $doubleNumber, $tripleNumber,  $quarterNumber, $checkInDate,  $checkOutDate);

            # Add booking for group
            $newBooking = new Booking();
            $newBooking->check_in_date = $checkInDate;
            $newBooking->check_out_date = $checkOutDate;
            $newBooking->total_price =  $totalPrice;
            $newBooking->user_id = $user->id;
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

            // Send email to client after receiving booking
            // dispatch(new SendBookingConfirmationEmailJob($user->id));
            event(new BookingProcessed($newBooking));
            return $newBooking;
            DB::commit();
        }
        catch(Throwable $e){
            DB::rollBack();
            throw new CustomException($e->getMessage());
        }        
    }

    public function validateNumberOfRoom($numberOfPeople, $singleNumber, $doubleNumber, $tripleNumber,  $quarterNumber, $checkInDate,  $checkOutDate){
        // Validate capacity of booking
        if( $numberOfPeople > $singleNumber +  $doubleNumber*2 +  $tripleNumber*3 + $quarterNumber*4){
            throw new CustomException(
                'The number of peoples exceeds capacity');  
        }
        // Validate available rooms
        $arrayDates = $this->getDatesFromRange($checkInDate, $checkOutDate, 'Y-m-d');
        foreach($arrayDates as $key => $value){
            $availabelQuantity = AvailableQuantity::where('date', $value)->first();
            $singleRemainingQuantity = $availabelQuantity->single_remaining_quantity;
            $doubleRemainingQuantity = $availabelQuantity->double_remaining_quantity;
            $tripleRemainingQuantity = $availabelQuantity->triple_remaining_quantity;
            $quarterRemainingQuantity = $availabelQuantity->quarter_remaining_quantity;
      
            if( ($singleNumber >  $singleRemainingQuantity)
                || ($doubleNumber >  $doubleRemainingQuantity)
                || ($tripleNumber >  $tripleRemainingQuantity)
                || ($quarterNumber >  $quarterRemainingQuantity)){
                    return $this->suggestAnotherOption($numberOfPeople, $checkInDate, $checkOutDate);                   
                }  
            
            $availabelQuantity->update(['single_remaining_quantity' => $singleRemainingQuantity - $singleNumber,
            'double_remaining_quantity' => $doubleRemainingQuantity - $doubleNumber,
            'triple_remaining_quantity' => $tripleRemainingQuantity - $tripleNumber,
            'quarter_remaining_quantity' => $quarterRemainingQuantity - $quarterNumber]);
                 
        }
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

    public function suggestAnotherOption($numberOfPeople, $checkInDate, $checkOutDate){
        // Check if remaining quantity is valid between checkin date and checkout date
        $arrayDates = $this->getDatesFromRange($checkInDate, $checkOutDate, 'Y-m-d');
        $arrayResult = array();
        foreach($arrayDates as $key => $value){
            $availabelQuantity = AvailableQuantity::where('date', $value)->first();
            $singleRemainingQuantity = $availabelQuantity->single_remaining_quantity;
            $doubleRemainingQuantity = $availabelQuantity->double_remaining_quantity;
            $tripleRemainingQuantity = $availabelQuantity->triple_remaining_quantity;
            $quarterRemainingQuantity = $availabelQuantity->quarter_remaining_quantity;
            
            $remainingQuantity =  array(4 => $quarterRemainingQuantity,
                                        3 =>  $tripleRemainingQuantity,
                                        2 =>  $doubleRemainingQuantity,
                                        1 => $singleRemainingQuantity);
            
            $capacity = array(4,3,2,1);
            $result = array(4 => 0, 3 => 0, 2 => 0, 1 => 0);
            $i = 0;
            $cloneNumberOfPeople = $numberOfPeople;
            // Generate suggest option at each booking date
            while( $cloneNumberOfPeople > 0 && $i < sizeof($capacity)){
                if( $cloneNumberOfPeople >= $capacity[$i]){     
                    $numberOfRoom = min(intdiv($cloneNumberOfPeople,$capacity[$i]), $remainingQuantity[$capacity[$i]]);
                    $cloneNumberOfPeople -= $numberOfRoom*$capacity[$i];
                    $result[$capacity[$i]] = $numberOfRoom;
                }
                $i++;
            }

            // Push suggest option to result array
            array_push($arrayResult, $result);
        }

        // Check if all value of result array are equal, if not, client must choose another checkin date and checkout date
        $uniqueArray = array_unique($arrayResult, SORT_REGULAR);
        if(count($uniqueArray) == 1){
            throw new CustomException(
                'Suggest another options '.json_encode($this->customArray($uniqueArray[0])));
        }
        
       throw new CustomException(
        'Out of room! Please select another check in date and check out date!');  
    }

    public function customArray($array){
        return array("Quarter" => $array[4],
                    "Triple" => $array[3],
                    "Double" => $array[2],
                    "Single" => $array[1]);
    }
}

