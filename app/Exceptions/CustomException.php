<?php

namespace App\Exceptions;

use Exception;

final class CustomException extends Exception
{
     /** @var @string */

     public function __construct(string $message)
     {
       parent::__construct($message);
     }

     /**
      * Returns true when exception message is safe to be displayed to a client.
      */
     public function isClientSafe(): bool
     {
         return true;
     }
 
    
}
