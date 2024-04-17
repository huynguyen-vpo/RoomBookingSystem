<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;
use App\Enums\RoomStatus;
use App\Models\Room;
use App\Models\RoomType;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $roomTypeId = RoomType::capacity($args["room_type"])->first()->id;
        DB::beginTransaction();
        try{
            $created = Room::create([
                "room_number" => $args["room_number"],
                "status" => $args["status"] ?? RoomStatus::AVAILABLE,
                "room_typeid" => $roomTypeId
            ]);
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            logger()->error("". $e->getMessage());
        }
        return $created;
    }
}
