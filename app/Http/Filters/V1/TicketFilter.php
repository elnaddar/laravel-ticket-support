<?php

namespace App\Http\Filters\V1;

class TicketFilter extends QueryFilter
{
    public function status($value)
    {
        return $this->builder->where('status', $value);
    }

    public function include($value)
    {
        return $this->builder->with($value);
    }
}

//
