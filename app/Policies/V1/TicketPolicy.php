<?php

namespace App\Policies\V1;

use App\Enums\V1\Abilities\TicketAbility;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->tokenCan(TicketAbility::viewAny->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(TicketAbility::viewOwn->value)) {
            return $user->id === $ticket->user_id;
        }
        return $user->tokenCan(TicketAbility::viewAny->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->tokenCan(TicketAbility::create->value) ||
            $user->tokenCan(TicketAbility::createOwn->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(TicketAbility::updateOwn->value)) {
            return $user->id === $ticket->user_id;
        }
        return $user->tokenCan(TicketAbility::update->value);
    }

    /**
     * Determine whether the user can replace the model.
     */
    public function replace(User $user, Ticket $ticket): bool
    {
        return $user->tokenCan(TicketAbility::replace->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(TicketAbility::deleteOwn->value)) {
            return $user->id === $ticket->user_id;
        }
        return $user->tokenCan(TicketAbility::delete->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return false;
    }
}
