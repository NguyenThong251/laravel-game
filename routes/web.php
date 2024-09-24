<?php

use App\Http\Controllers\admin\MembersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::resource("members", MembersController::class)->except('show');
