<?php

use App\Http\Controllers\admin\DrawingsController;
use App\Http\Controllers\admin\IndexController;
use App\Http\Controllers\admin\LoginController;
use App\Http\Controllers\admin\MembersController;
use App\Http\Controllers\admin\RechargesController;
use App\Http\Controllers\admin\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin/main', function () {
    return view('admin.main');
});

Route::namespace("Admin")->domain(env('ADMIN_URL') ?? env('APP_URL'))->name("admin.")->prefix("admin")->middleware("language")->group(function () {
    Route::get("main", [IndexController::class, 'main'])->name("main");
    // fix 
    Route::post("fix/url", [IndexController::class, 'fix_url'])->name("fix.url");
    // recharges
    Route::get("recharges/confirm/{recharge}/{status}", [RechargesController::class, 'confirm'])->name("recharges.confirm");
    // 通过
    Route::post("recharges/confirm/{recharge}", [RechargesController::class, 'post_confirm'])->name("recharges.post_confirm");
    // 不通过
    Route::post("recharges/reject/{recharge}", [RechargesController::class, 'post_reject'])->name("recharges.post_reject");
    Route::get("recharges/payment/{recharge}", [RechargesController::class, 'payment_detail'])->name('recharges.payment_detail');
    Route::resource("recharges", RechargesController::class)->except("create", "store", "edit");

    // drawings
    Route::get("drawings/confirm/{drawing}/{status}", [DrawingsController::class, 'confirm'])->name("drawings.confirm");
    // 通过
    Route::post("drawings/confirm/{drawing}", [DrawingsController::class, 'post_confirm'])->name("drawings.post_confirm");
    // 不通过
    Route::post("drawings/reject/{drawing}", [DrawingsController::class, 'post_reject'])->name("drawings.post_reject");
    Route::resource("drawings", "DrawingsController")->except("create", "store", "edit");

    Route::get("drawings_setting_size", [DrawingsController::class, 'setting_size'])->name('drawings.setting_size');
    Route::post("drawings_setting_size", [DrawingsController::class, 'post_setting_size'])->name('drawings.post_setting_size');

    // User
    Route::get("user/export", [UsersController::class, 'export'])->name("users.export");
    Route::get("user/info", [UsersController::class, 'userinfo'])->name("user.info");
    Route::get("user/lang", [UsersController::class, 'lang'])->name("user.lang");
    Route::post("user/lang", [UsersController::class, 'post_lang'])->name("user.post_lang");
    Route::get("user/modify_pwd", [UsersController::class, 'modify_pwd'])->name("user.modify_pwd");
    Route::post("user/modify_pwd", [UsersController::class, 'post_modify_pwd'])->name("user.post_modify_pwd");
    Route::get("users/assign/{user}", [UsersController::class, 'assign'])->name("users.assign");
    Route::post("users/assign/{user}", [UsersController::class, 'post_assign'])->name("users.post_assign");

    Route::get("user/google", [UsersController::class, 'google_secret")->name("user.google']);
    Route::post("user/google", [UsersController::class, 'post_google_secret'])->name("user.post_google");
    Route::post("users/reset/google/{user}", [UsersController::class, 'post_reset_google'])->name("user.post_reset_google");
    // Logout 
    Route::any("logout", [LoginController::class, 'logout'])->name("logout");


    Route::resource("users", UsersController::class)->except("show");

    Route::resource("members", MembersController::class)->except('show');
});
