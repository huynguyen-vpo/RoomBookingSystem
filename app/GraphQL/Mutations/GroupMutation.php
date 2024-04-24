<?php

namespace App\GraphQL\Mutations;

use App\Models\Group;

final class GroupMutation
{
    /**
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function create($_, array $args){
        $group = Group::create([
            'name' => $args['name']
        ]);
        return $group;
    }

    public function delete($_, array $args){
        $group = Group::findOrFail($args['groupId']);
        $group->delete();
        return $group;
    }

    public function update($_, array $args){
        $group = Group::findOrFail($args['groupId']);
        $group->update(["name" => $args['name']]);
        return $group;
    }
}
