<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use App\Models\Payment;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function checkout($bookingId)
    {
        $booking = Booking::with('room')->findOrFail($bookingId);

        if ($booking->status !== 'confirmed') {
            abort(403, 'Booking must be confirmed before payment.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = CheckoutSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Room booking at ' . $booking->room->location,
                    ],
                    'unit_amount' => (int) ($booking->total_price * 100), // Stripe requires cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['bookingId' => $booking->id]),
            'cancel_url' => route('dashboard'),
            'metadata' => [
                'booking_id' => $booking->id,
            ],
        ]);

        return redirect($session->url);
    }

    public function success($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        $booking->status = 'paid';
        $booking->save();

        $booking->room->is_available = false;
        $booking->room->save();

        Payment::create([
            'booking_id' => $booking->id,
            'stripe_payment_id' => 'test_' . uniqid(), // Ideally this comes from a webhook or session
            'amount' => $booking->total_price,
            'status' => 'paid',
            'paid_at' => Carbon::now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Payment successful!');
    }
}
