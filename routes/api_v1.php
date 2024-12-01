<?php

use App\Models\Ticket;
use Illuminate\Support\Facades\Route;

Route::apiResource("tickets", Ticket::class);

//
