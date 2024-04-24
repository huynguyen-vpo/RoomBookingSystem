<?php

namespace App\GraphQL\Queries;

use App\Models\User;

final class UserQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }

    public function filter(null $_, array $args){
        $user = User::query();
  
        if (isset($args['filter'])) {
          $filter = $args['filter'];
          if (isset($filter['id'])) {
              $user->where('id', $filter['id']);
          }
    
          if (isset($filter['email'])) {
              $user->where('email', $filter['email']);
          }
    
          if (isset($filter['name'])) {
              $user->where('name', $filter['name']);
          }
         }   
          return $user->get();
      }
}
