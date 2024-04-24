<?php

namespace App\GraphQL\Mutations;

use App\Models\Room;
use App\Models\RoomType;
use Exception;

final class RoomMutation
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function create($_, array $args)
    {
        // TODO implement the resolver
        $roomTypeId = RoomType::capacity($args["roomType"] ?? 1)->first()->id;
        try{
            $created = Room::create([
                "room_number" => $args["roomNumber"],
                "status" => $args["status"] ?? "Available",
                "room_typeid" => $args["roomType"] ? $roomTypeId : null  
            ]);
        }catch(Exception $e){
            logger()->error("". $e->getMessage());
        }
        return $created;
    }
    public function update($_, array $args){
        $roomTypeId = RoomType::capacity($args["roomType"])->first()->id;
        $room = Room::findOrFail($args["id"]);
        try{
            $room->update([
                "room_number" => $args["roomNumber"] ?? $room->room_number,
                "status" => $args["status"] ?? "Available",
                "room_typeid" => $args["roomType"] ? $roomTypeId : $room->room_typeid 
            ]);
        }catch(Exception $e){
            logger()->error("". $e->getMessage());
        }
        return $room;
    }
    public function delete($_, array $args){
        $room = Room::findOrFail($args["id"]);
        $room->delete();
        return $room;
    }
}
