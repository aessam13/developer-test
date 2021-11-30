<?php

use App\Http\Controllers\AchievementsController;
use Illuminate\Support\Facades\Route;

Route::get('/users/{user}/achievements', [AchievementsController::class, 'index']);
