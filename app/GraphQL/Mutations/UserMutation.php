<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;

final readonly class UserMutation
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        // TODO implement the resolver
    }

    public function create(null $_, array $args){
        $user = User::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'password' => $args['password'],
        ]);
        return $user;
    }

    public function update(null $_, array $args){
        $user = User::findOrFail($args['userId']);
        $user->update(['name' => $args['name']]);
        return $user;
    }

    public function delete(null $_, array $args){
        $user = User::findOrFail($args['userId']);
        $user->delete();
        return $user;
    }

    public function addUserToGroup(null $_, array $args){
        $user = User::findOrFail($args['userId']);
        $user->groups()->attach($args['groupId']);
        return $user;
    }
}
