<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

Route::get('match/{property_id}', [SearchController::class, 'getMatched']);
