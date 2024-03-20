<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;

Route::controller(LeadController::class)->group(function () {
    Route::post("/update", "update");
    Route::post("/add", "add");
});

