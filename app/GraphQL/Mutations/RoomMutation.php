<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;
use App\Enums\RoomStatus;
use App\Exports\RoomExport;
use App\Models\Room;
use App\Models\RoomType;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

final readonly class RoomMutation
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        // TODO implement the resolver
    }
    public function create(null $_, array $args)
    {
        // TODO implement the resolver
        $roomTypeId = RoomType::capacity($args["roomType"] ?? 1)->first()->id;
        try{
            $created = Room::create([
                "room_number" => $args["roomNumber"],
                "status" => $args["status"] ?? RoomStatus::AVAILABLE,
                "room_typeid" => $args["roomType"] ? $roomTypeId : null  
            ]);
        }catch(Exception $e){
            logger()->error("". $e->getMessage());
        }
        return $created;
    }
    public function update(null $_, array $args){
        $roomTypeId = RoomType::capacity($args["roomType"])->first()->id;
        $room = Room::findOrFail($args["id"]);
        try{
            $room->update([
                "room_number" => $args["roomNumber"] ?? $room->room_number,
                "status" => $args["status"] ?? RoomStatus::AVAILABLE,
                "room_typeid" => $args["roomType"] ? $roomTypeId : $room->room_typeid 
            ]);
        }catch(Exception $e){
            logger()->error("". $e->getMessage());
        }
        return $room;
    }
    public function delete(null $_, array $args){
        $room = Room::findOrFail($args["id"]);
        $room->delete();
        return $room;
    }
}
