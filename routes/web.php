<?php

use App\Http\Controllers\AchievementsController;
use Illuminate\Support\Facades\Route;

Route::controller(AchievementsController::class)->group(function () {
    Route::get('/users/{user}/achievements', 'index');
});
