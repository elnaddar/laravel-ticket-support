<?php

use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\AuthorController;
use Illuminate\Support\Facades\Route;

Route::apiResource("authors", AuthorController::class);
Route::apiResource("tickets", TicketController::class);

//
