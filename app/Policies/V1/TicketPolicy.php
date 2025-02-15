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
        return $user->tokenAble(TicketAbility::viewAny);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->tokenAble(TicketAbility::viewOwn)) {
            return $user->id === $ticket->user_id;
        }
        return $user->tokenAble(TicketAbility::viewAny);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->tokenAbleAny(TicketAbility::create, TicketAbility::createOwn);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->tokenAble(TicketAbility::updateOwn)) {
            return $user->id === $ticket->user_id;
        }
        return $user->tokenAble(TicketAbility::update);
    }

    /**
     * Determine whether the user can replace the model.
     */
    public function replace(User $user, Ticket $ticket): bool
    {
        return $user->tokenAble(TicketAbility::replace);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        if ($user->tokenAble(TicketAbility::deleteOwn)) {
            return $user->id === $ticket->user_id;
        }
        return $user->tokenAble(TicketAbility::delete);
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
