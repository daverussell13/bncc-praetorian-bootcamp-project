<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

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
    return view('welcome');
});

Route::prefix("user")->name("user.")->group(function() {

    Route::middleware(["guest:web", "prevent-back-history"])->group(function() {
        Route::view("/login", "user.login")->name("login");
        Route::view("/register", "user.register")->name("register");
        Route::post("/register", [UserController::class, "postRegister"]);
        Route::post("/login", [UserController::class, "postLogin"]);
    });

    Route::middleware(["auth:web", "prevent-back-history"])->group(function() {
        Route::view("/home", "user.home")->name("home");
        Route::post("/logout", [UserController::class, "logout"])->name("logout");
    });

});
