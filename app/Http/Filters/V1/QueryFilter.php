<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected Builder $builder;

    public function __construct(protected Request $request) {}

    public function apply(Builder $builder)
    {
        $this->builder = $builder;
        foreach ($this->request->all() as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $builder;
    }

    public function filter(array $arr)
    {
        foreach ($arr as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    public function sort($value)
    {
        $sortValues = explode(",", $value);

        foreach ($sortValues as $sortValue) {
            $sortDirection = "asc";
            
            if ($sortValue[0] == '-') {
                $sortDirection = "desc";
                $sortValue = substr($sortValue, 1);
            }

            $this->builder->orderBy($sortValue, $sortDirection);
        }

        return $this->builder;
    }
}


//
