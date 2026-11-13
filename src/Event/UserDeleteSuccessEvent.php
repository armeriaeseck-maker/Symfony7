<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class UserDeleteSuccessEvent extends Event
{
       public function __construct(private string $email) {}

       public function getEmail(): string
       {
              return $this->email;
       }
}
