<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'total_price' => 'required|numeric|min:0',
        ]);

        Booking::create([
            'room_id' => $validated['room_id'],
            'renter_id' => auth()->id(),
            'total_price' => $validated['total_price'],
            'status' => 'pending',
            'agreement_accepted' => true,
        ]);

        return redirect()->route('home')->with('success', 'Booking submitted successfully!');
    }

    public function confirm($id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'confirmed';
        $booking->save();

        return back()->with('success', 'Booking confirmed.');
    }

    public function cancel($id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'cancelled';
        $booking->save();

        return back()->with('success', 'Booking cancelled.');
    }

    public function ownerDelivered($id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);
        $booking->owner_confirmed_delivery = true;

        if ($booking->renter_confirmed_delivery) {
            $booking->status = 'completed';
        }

        $booking->save();

        return back()->with('success', 'Owner delivery confirmed.');
    }

    public function renterDelivered($id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);

        if ($booking->renter_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $booking->renter_confirmed_delivery = true;

        if ($booking->owner_confirmed_delivery) {
            $booking->status = 'completed';
        }

        $booking->save();

        return back()->with('success', 'You have confirmed delivery.');
    }

    public function renterStopRequest($id): RedirectResponse
    {
        $booking = Booking::with('room')->findOrFail($id);
        $booking->renter_requested_stop = true;
        $booking->save();

        return back()->with('success', 'Renter stop request sent.');
    }

    public function ownerStopRequest($id): RedirectResponse
    {
        $booking = Booking::with('room')->findOrFail($id);
        $booking->owner_requested_stop = true;
        $booking->save();

        return back()->with('success', 'Owner stop request sent.');
    }

    public function renterStopCancel($id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);
        $booking->renter_requested_stop = false;
        $booking->save();

        return back()->with('success', 'Renter stop request canceled.');
    }

    public function renterStopConfirm($id): RedirectResponse
    {
        $booking = Booking::with('room')->findOrFail($id);
        $booking->renter_requested_stop = true;
        $booking->save();

        $this->checkIfBothStopped($booking);

        return back()->with('success', 'Renter confirmed stop.');
    }

    public function ownerStopCancel($id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);
        $booking->owner_requested_stop = false;
        $booking->save();

        return back()->with('success', 'Owner stop request canceled.');
    }

    public function ownerStopConfirm($id): RedirectResponse
    {
        $booking = Booking::with('room')->findOrFail($id);
        $booking->owner_requested_stop = true;
        $booking->save();

        $this->checkIfBothStopped($booking);

        return back()->with('success', 'Owner confirmed stop.');
    }

    private function checkIfBothStopped(Booking $booking)
    {
        if ($booking->renter_requested_stop && $booking->owner_requested_stop) {
            $booking->status = 'stopped';
            $booking->renter_confirmed_delivery = false;
            $booking->owner_confirmed_delivery = false;
            $booking->save();

            $booking->room->is_available = true;
            $booking->room->save();
        }
    }

    public function ownerDashboard()
    {
        $userId = auth()->id();

        $ownerBookings = Booking::with(['renter', 'room'])
            ->whereHas('room', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderByDesc('created_at')
            ->get();

        $renterBookings = Booking::with(['room'])
            ->where('renter_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        $rooms = Room::where('user_id', $userId)
            ->where('is_deleted', false)
            ->orderByDesc('created_at')
            ->get();

        $totalEarnings = Payment::whereHas('booking.room', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', 'paid')->sum('amount');

        $totalSpendings = Payment::whereHas('booking', function ($query) use ($userId) {
            $query->where('renter_id', $userId);
        })->where('status', 'paid')->sum('amount');

        return Inertia::render('Dashboard', [
            'ownerBookings' => $ownerBookings,
            'renterBookings' => $renterBookings,
            'totalEarnings' => (float) $totalEarnings,
            'totalSpendings' => (float) $totalSpendings,
            'rooms' => $rooms,
        ]);
    }
}
