<?php

namespace App\Http\Filters\V1;

class AuthorFilter extends QueryFilter
{
    public function id($values)
    {
        return $this->builder->whereIn('id', explode(',', $values));
    }

    public function name($value)
    {
        $likeStr = str_replace("*", "%", $value);
        return $this->builder->whereLike("name", $likeStr);
    }

    public function email($value)
    {
        $likeStr = str_replace("*", "%", $value);
        return $this->builder->whereLike("email", $likeStr);
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
