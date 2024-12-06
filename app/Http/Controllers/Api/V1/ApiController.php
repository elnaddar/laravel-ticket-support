<?php

namespace App\Http\Controllers\Api\V1;

use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    use ApiResponses;
    public function include(string $relationship): bool
    {
        $param = request()->get("include");

        if (!isset($param)) {
            return false;
        }

        $includeValues = explode(",", strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }

    protected function isAble($ability, $targetModel)
    {
        return Gate::authorize($ability, $targetModel);
    }
}
