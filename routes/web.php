<?php

use App\Http\Controllers\Api\RoomController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;

Route::get('/', [PublicController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {
    Route::get('/rooms/create', function () {
        return Inertia::render('rooms/Create');
    })->name('rooms.create');

    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::patch('/rooms/{id}/unavailable', [RoomController::class, 'makeUnavailable'])->name('rooms.unavailable');
    Route::patch('/rooms/{id}/available', [RoomController::class, 'makeAvailable'])->name('rooms.available');
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    Route::patch('/bookings/{id}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::patch('/bookings/{id}/owner-delivered', [BookingController::class, 'ownerDelivered'])->name('bookings.ownerDelivered');
    Route::patch('/bookings/{id}/renter-delivered', [BookingController::class, 'renterDelivered'])->name('bookings.renter-delivered');

    Route::get('/bookings/{bookingId}/checkout', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/bookings/{bookingId}/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');

    Route::patch('/bookings/{id}/renter-stop', [BookingController::class, 'renterStopRequest'])->name('bookings.renter.stop');
    Route::patch('/bookings/{id}/owner-stop', [BookingController::class, 'ownerStopRequest'])->name('bookings.owner.stop');
    Route::patch('/bookings/{id}/renter-stop-cancel', [BookingController::class, 'renterStopCancel'])->name('bookings.renter.stop.cancel');
    Route::patch('/bookings/{id}/renter-stop-confirm', [BookingController::class, 'renterStopConfirm'])->name('bookings.renter.stop.confirm');
    Route::patch('/bookings/{id}/owner-stop-cancel', [BookingController::class, 'ownerStopCancel'])->name('bookings.owner.stop.cancel');
    Route::patch('/bookings/{id}/owner-stop-confirm', [BookingController::class, 'ownerStopConfirm'])->name('bookings.owner.stop.confirm');

    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

    Route::get('/bookings/{bookingId}/chat', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversationId}/message', [ChatController::class, 'store'])->name('chat.message.store');

    Route::get('/dashboard', [BookingController::class, 'ownerDashboard'])->name('dashboard');

});

Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
