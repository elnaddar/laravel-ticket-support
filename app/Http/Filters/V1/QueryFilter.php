<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected Builder $builder;
    protected $sortable = [];
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

            $sortable = $this->sortable;

            if (in_array($sortValue, $sortable)) {
                $columnName = $sortValue;
            } else if (array_key_exists($sortValue, $sortable)) {
                $columnName = $sortable[$sortValue];
            } else {
                continue;
            }

            $this->builder->orderBy($columnName, $sortDirection);
        }

        return $this->builder;
    }
}


//
