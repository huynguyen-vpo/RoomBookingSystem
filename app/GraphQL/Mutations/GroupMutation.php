<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Group;

final readonly class GroupMutation
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        // TODO implement the resolver
    }

    public function create(null $_, array $args){
        $group = Group::create([
            'name' => $args['name']
        ]);
        return $group;
    }

    public function delete(null $_, array $args){
        $group = Group::findOrFail($args['groupId']);
        $group->delete();
        return $group;
    }

    public function update(null $_, array $args){
        $group = Group::findOrFail($args['groupId']);
        $group->update(["name" => $args['name']]);
        return $group;
    }
}
