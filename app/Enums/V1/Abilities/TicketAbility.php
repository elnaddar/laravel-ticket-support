<?php

namespace App\Enums\V1\Abilities;

enum TicketAbility: string
{
    case viewAny = 'ticket:view-any';
    case create = 'ticket:create';
    case replace = 'ticket:replace';
    case update = 'ticket:update';
    case delete = 'ticket:delete';

    case viewOwn = 'ticket:own:view';
    case updateOwn = 'ticket:own:update';
    case deleteOwn = 'ticket:own:delete';
}

//
