<?php

use Illuminate\Support\Facades\Route;
use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return response()->redirectTo(backpack_url('project'));
})->name('home');
