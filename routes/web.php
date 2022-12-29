<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SetPasswordController;
use App\Http\Controllers\StatusManageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkOrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    if (Auth::check())
    {
        if (Auth::user()->customer)
        {
            return to_route('customers.index');
        }
        elseif (Auth::user()->is_employee)
        {
            return to_route('assigned.index');
        }

        return to_route('users');
    }

    return to_route('login.index');
});

Route::controller(LoginController::class)->group(function(){

    Route::get('/login', 'index')->name('login.index');

    Route::post('/login', 'login')->name('login');

    Route::post('/logout', 'logout')->name('logout')
        ->middleware('auth');
});

Route::controller(SignUpController::class)->group(function(){

    Route::get('/signup', 'create')->name('signup.create');

    Route::post('signup/store', 'store')->name('signup.store');

});

Route::controller(SetPasswordController::class)->group(function(){

    Route::get('users/{user:slug}/set-password', 'index')->name('set-password');

    Route::post('users/{user:slug}/set-password/store', 'store')->name('set-password.store');
});

Route::middleware('auth')->group(function(){

    Route::controller(UserController::class)->group(function(){

        Route::get('/users', 'index')->name('users');

        Route::get('/users/create', 'create')->name('users.create');

        Route::post('/users/store', 'store')->name('users.store');

        Route::get('/users/{user:slug}/edit', 'edit')->name('users.edit');

        Route::put('/users/{user:slug}/update', 'update')->name('users.update');
    });

    Route::controller(CustomerController::class)->group(function(){

        Route::get('/work-orders', 'index')->name('customers.index');
    });

    Route::controller(WorkOrderController::class)->group(function(){

        Route::get('/work-orders/list', 'index')->name('work-orders.index');

        Route::get('/work-orders/create', 'create')->name('work-orders.create');

        Route::post('/work-orders/store', 'store')->name('work-orders.store');

        Route::get('/work-orders/{workOrder:slug}/show', 'show')->name('work-orders.show');
    });

    Route::controller(AssignmentController::class)->group(function(){

        Route::get('/assigned-work', 'index')->name('assigned.index');

        Route::post('/{workOrder:slug}/assigned-work', 'store')->name('assigned.store');
    });

});
