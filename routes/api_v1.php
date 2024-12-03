<?php

use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\AuthorTicketsController;
use Illuminate\Support\Facades\Route;

Route::apiResource("authors", AuthorController::class);
Route::apiResource("tickets", TicketController::class)
    ->except("update");
Route::controller(TicketController::class)
    ->prefix("tickets")
    ->name("tickets.")
    ->group(function () {
        Route::put("/{ticket}", "replace")->name("replace");
        Route::patch("/{ticket}", "update")->name("update");
    });

Route::apiResource(
    "authors.tickets",
    AuthorTicketsController::class,
    [
        "only" => ["index", "store", "destroy"]
    ]
);
Route::controller(AuthorTicketsController::class)
    ->prefix("authors/{author}/tickets")
    ->name("authors.tickets.")
    ->group(function () {
        Route::put("/{ticket}", "replace")->name("replace");
        Route::patch("/{ticket}", "update")->name("update");
    });



//
