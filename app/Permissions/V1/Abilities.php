<?php

namespace App\Permissions\V1;

use App\Enums\V1\Abilities\TicketAbility;
use App\Enums\V1\Abilities\UserAbility;
use App\Models\User;


final class Abilities
{
    private static function getAbilitiesValues(array $abilities)
    {
        return array_map(fn($ability) => $ability->value, $abilities);
    }

    public static function getAbilities(User $user)
    {
        if ($user->is_manager) {
            return self::getAbilitiesValues([
                TicketAbility::viewAny,
                TicketAbility::create,
                TicketAbility::update,
                TicketAbility::replace,
                TicketAbility::delete,

                UserAbility::create,
                UserAbility::update,
                UserAbility::replace,
                UserAbility::delete,
            ]);
        } else {
            return self::getAbilitiesValues([
                TicketAbility::viewOwn,
                TicketAbility::createOwn,
                TicketAbility::updateOwn,
                TicketAbility::deleteOwn,

                UserAbility::updateOwn,
                UserAbility::deleteOwn,
            ]);
        }
    }
}

//
