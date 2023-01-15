<?php

use App\Http\Controllers\BookingCarController;
use App\Http\Controllers\BookingRoomController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::get('/reset-password', [UserController::class, 'resetPasswordShow']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);

Route::get('/', [HomeController::class, 'index']);

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    // HOME
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // UBAH PASSWORD
    Route::get('users/change-password', [UserController::class, 'changepassword'])->name('password.change');
    Route::post('users/change-password', [UserController::class, 'storepassword'])->name('password.store');

    // UBAH DATA
    Route::get('users/change-data', [UserController::class, 'changedata'])->name('data.change');
    Route::post('users/change-data', [UserController::class, 'storedata'])->name('data.store');

    //MOBIL
    Route::get('vehicles/data', [CarController::class, 'showData'])->name('vehicles.data');
    Route::resource('vehicles', CarController::class);

    //RUANGAN
    Route::get('rooms/data', [RoomController::class, 'showData'])->name('rooms.data');
    Route::resource('rooms', RoomController::class);

    //USER
    Route::get('drivers', [UserController::class, 'driver'])->name('users.driver');
    Route::get('drivers/data', [UserController::class, 'showDataDriver'])->name('users.datadriver');
    Route::get('users/data', [UserController::class, 'showData'])->name('users.data');
    Route::resource('users', UserController::class);

    //DRIVER

    //BOOKING
    //BOOKING RUANGAN
    Route::get('booking-room', [BookingRoomController::class, 'index'])->name('bookingroom.index');
    Route::get('booking-room/all', [BookingRoomController::class, 'all'])->name('bookingroom.all');
    Route::get('booking-room/all/data', [BookingRoomController::class, 'showDataAll'])->name('bookingroom.dataall');
    Route::delete('booking-room/data/{id}', [BookingRoomController::class, 'destroy'])->name('bookingroom.destroy');
    Route::get('booking-room/data', [BookingRoomController::class, 'showData'])->name('bookingroom.data');
    Route::post('booking-room/data', [BookingRoomController::class, 'store'])->name('bookingroom.store');
    Route::get('booking-room/{id}/edit', [BookingRoomController::class, 'edit'])->name('bookingroom.edit');

    //BOOKING KENDARAAN
    Route::get('booking-vehicle', [BookingCarController::class, 'index'])->name('bookingcar.index');
    Route::delete('booking-vehicle/data/{id}', [BookingCarController::class, 'destroy'])->name('bookingcar.destroy');
    Route::get('booking-vehicle/data', [BookingCarController::class, 'showData'])->name('bookingcar.data');
    Route::post('booking-vehicle/data', [BookingCarController::class, 'store'])->name('bookingcar.store');
    Route::get('booking-vehicle/{id}/edit', [BookingCarController::class, 'edit'])->name('bookingcar.edit');

    //MONITORING
    Route::get('booking-room/today', [BookingRoomController::class, 'today'])->name('bookingroom.today');
    Route::get('booking-room/today/data', [BookingRoomController::class, 'showDataToday'])->name('bookingroom.datatoday');
    Route::get('booking-vehicle/today', [BookingCarController::class, 'today'])->name('bookingcar.today');
    Route::get('booking-vehicle/today/data', [BookingCarController::class, 'showDataToday'])->name('bookingcar.datatoday');

    //APPROVE
    //APPROVE RUANGAN
    Route::get('booking-room/approve', [BookingRoomController::class, 'approve'])->name('bookingroom.approve');
    Route::post('booking-room/approve', [BookingRoomController::class, 'storeApprove'])->name('bookingroom.storeapprove');
    Route::get('booking-room/approve/data', [BookingRoomController::class, 'approveData'])->name('bookingroom.approvedata');

    //APPROVE KENDARAAN
    Route::get('booking-vehicle/approve', [BookingCarController::class, 'approve'])->name('bookingcar.approve');
    Route::post('booking-vehicle/approve', [BookingCarController::class, 'storeApprove'])->name('bookingcar.storeapprove');
    Route::get('booking-vehicle/approve/data', [BookingCarController::class, 'approveData'])->name('bookingcar.approvedata');

    //REPORT
    // Route::get('tamu/report', [GuestController::class, 'reportsearch'])->name('report.search');
    // Route::get('tamu/report/cetak', [GuestController::class, 'reportprint'])->name('report.print');
});

