<?php

namespace App\Http\Filters\V1;

class TicketFilter extends QueryFilter
{
    public function status($values)
    {
        return $this->builder->whereIn('status', explode(',', $values));
    }

    public function title($value)
    {
        $likeStr = str_replace("*", "%", $value);
        return $this->builder->whereLike("title", $likeStr);
    }

    public function include($value)
    {
        return $this->builder->with($value);
    }
}

//
