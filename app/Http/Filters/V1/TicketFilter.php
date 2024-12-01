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

    public function createdAt($value)
    {
        $dates = explode(',', $value);
        if (count($dates) > 1) {
            return $this->builder->whereBetween("created_at", $dates);
        }
        return $this->builder->whereDate("created_at", $value);
    }

    public function updatedAt($value)
    {
        $dates = explode(',', $value);
        if (count($dates) > 1) {
            return $this->builder->whereBetween("updated_at", $dates);
        }
        return $this->builder->whereDate("updated_at", $value);
    }

    public function include($value)
    {
        return $this->builder->with($value);
    }
}

//
