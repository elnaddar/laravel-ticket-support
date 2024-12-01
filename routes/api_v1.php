<?php

use App\Models\Ticket;
use Illuminate\Support\Facades\Route;

Route::get("/tickets", function () {
    return Ticket::all();
});
