<?php

namespace App\Enums\Users;

enum UserStatus: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}
