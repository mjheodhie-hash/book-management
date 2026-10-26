<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

// Book Management API Routes
Route::apiResource('books', BookController::class);