<?php

namespace App\Enums\V1\Abilities;

enum UserAbility: string
{
    case create = 'user:create';
    case replace = 'user:replace';
    case update = 'user:update';
    case delete = 'user:delete';

    case updateOwn = 'user:own:update';
    case deleteOwn = 'user:own:delete';
}

//
