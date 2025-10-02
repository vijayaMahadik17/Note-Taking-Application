<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;

Route::get('/health', fn() => response()->json(['ok' => true]));
Route::apiResource('notes', NoteController::class);
