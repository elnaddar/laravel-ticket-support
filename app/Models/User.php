<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\V1\Abilities\TicketAbility;
use App\Enums\V1\Abilities\UserAbility;
use App\Http\Filters\V1\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }

    public function tokenAble(TicketAbility|UserAbility $ability)
    {
        return $this->tokenCan($ability->value);
    }

    public function tokenAbleAny(TicketAbility|UserAbility ...$abilities)
    {
        foreach ($abilities as $ability) {
            if ($this->tokenAble($ability)) {
                return true;
            }
        }
        return false;
    }

    public function tokenAbleAll(TicketAbility|UserAbility ...$abilities)
    {
        foreach ($abilities as $ability) {
            if (!$this->tokenAble($ability)) {
                return false;
            }
        }
        return true;
    }
}
