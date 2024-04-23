<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\BookingProcessed;
use App\Http\Requests\BookingByGroupRequest;
use App\Http\Requests\BookingByUserRequest;
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

class BookingController extends Controller
{
    public function createBookingByUser(BookingByUserRequest $request){  
        $numberOfPeople = $request['numberOfPeople'];
        $singleNumber = $request['singleNumber'];
        $doubleNumber = $request['doubleNumber'];
        $tripleNumber = $request['tripleNumber'];
        $quarterNumber = $request['quarterNumber'];
        $checkInDate = $request['checkInDate'];
        $checkOutDate = $request['checkOutDate'];
        
        $singleTypeId = RoomType::where('type', 'single')->first()->id;
        $doubleTypeId = RoomType::where('type', 'double')->first()->id;
        $tripleTypeId = RoomType::where('type', 'triple')->first()->id;
        $quarterTypeId = RoomType::where('type', 'quarter')->first()->id;

        if(!$this->validateNumberOfRoom($numberOfPeople, $singleNumber, $doubleNumber, $tripleNumber,  $quarterNumber, $checkInDate,  $checkOutDate)){
            return response()->json(['Another option' => $this->suggestAnotherOption($numberOfPeople)], 200);
        }

        # Add booking for user
        $user = User::findOrFail($request['userId']);
        $newBooking = new Booking();
        $newBooking->check_in_date = $request['checkInDate'];
        $newBooking->check_out_date = $request['checkOutDate'];
        $newBooking->total_price =  0;
        $newBooking->user_id = $user->id;
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

    public function createBookingByGroup(BookingByGroupRequest $request){
        $groupId = $request['groupId'];
        $userId =  $request['userId'];
        $numberOfPeople = $request['numberOfPeople'];
        $singleNumber = $request['singleNumber'];
        $doubleNumber = $request['doubleNumber'];
        $tripleNumber = $request['tripleNumber'];
        $quarterNumber = $request['quarterNumber'];
        $checkInDate = $request['checkInDate'];
        $checkOutDate = $request['checkOutDate'];

        $totalPrice = 0;

        $singleTypeId = RoomType::where('type', 'single')->first()->id;
        $doubleTypeId = RoomType::where('type', 'double')->first()->id;
        $tripleTypeId = RoomType::where('type', 'triple')->first()->id;
        $quarterTypeId = RoomType::where('type', 'quarter')->first()->id;

        if(!$this->validateNumberOfRoom($numberOfPeople, $singleNumber, $doubleNumber, $tripleNumber,  $quarterNumber, $checkInDate,  $checkOutDate)){
            return response()->json(['Another option' => $this->suggestAnotherOption($numberOfPeople)], 200);
        }

        $user = User::findOrFail($userId);
        if($user->groups()->where('groups.id',$groupId )->exists()){
            $group = Group::findOrFail($request['groupId']);
            
            # Validate if another user of the group already booked in the current date
            $currentDate = date('Y-m-d');
            if(Booking::where('target_id', $groupId)
                    ->whereDate('created_at', [$currentDate])
                    ->exists()){   
                return response()->json(['message' => 'Another user in your group already booked'], 400);
            }

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
            return  $newBooking;
        }  
        return response()->json(['message' => 'User is not in group'], 404);
      
    }

    public function validateNumberOfRoom($numberOfPeople, $singleNumber, $doubleNumber, $tripleNumber,  $quarterNumber, $checkInDate,  $checkOutDate){
        // Validate capacity of booking
        if( $numberOfPeople > $singleNumber +  $doubleNumber*2 +  $tripleNumber*3 + $quarterNumber*4){
           return false;
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

    public function suggestAnotherOption($numberOfPeople){
        $capacity = array(4,3,2,1);
        $result = array(4 => 0, 3 => 0, 2 => 0, 1 => 0);
        $i = 0;
        while($numberOfPeople > 0 && $i < sizeof($capacity)){
            if($numberOfPeople >= $capacity[$i]){
                $numberOfRoom = floor($numberOfPeople / $capacity[$i]);
                $numberOfPeople = $numberOfPeople % $capacity[$i];
                $result[$capacity[$i]] += $numberOfRoom;
            }
            $i++;
        }
         return $result;
    }
}
