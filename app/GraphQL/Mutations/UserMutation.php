<?php

namespace App\GraphQL\Mutations;

use App\Models\User;

final class UserMutation
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function create($_, array $args){
        $user = User::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'password' => $args['password'],
        ]);
        return $user;
    }

    public function update($_, array $args){
        $user = User::findOrFail($args['userId']);
        $user->update(['name' => $args['name']]);
        return $user;
    }

    public function delete($_, array $args){
        $user = User::findOrFail($args['userId']);
        $user->delete();
        return $user;
    }

    public function addUserToGroup($_, array $args){
        $user = User::findOrFail($args['userId']);
        $user->groups()->attach($args['groupId']);
        return $user;
    }
}
