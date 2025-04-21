<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Review;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;

class RoomController extends Controller
{
    // Return all rooms
    public function index()
    {
        $rooms = Room::withAvg('reviews', 'rating')->get();

        return response()->json($rooms);
    }

    public function show($id)
    {
        $room = Room::findOrFail($id);

        $reviews = Review::with('from_user')
            ->where('to_room_id', $room->id)
            ->where('is_deleted', false)
            ->latest()
            ->get();

        return inertia('rooms/Show', [
            'room' => $room,
            'reviews' => $reviews
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'length' => 'required|numeric|min:0.1',
            'width' => 'required|numeric|min:0.1',
            'height' => 'required|numeric|min:0.1',
            'price_per_day' => 'nullable|numeric|min:0',
            'price_per_month' => 'required|numeric|min:0',
            'images.*' => 'image|max:2048',
        ]);


        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('rooms', 'public');
            }
        }

        Room::create([
            'user_id' => auth()->id(),
            'location' => $validated['location'],
            'description' => $validated['description'],
            'length' => $validated['length'],
            'width' => $validated['width'],
            'height' => $validated['height'],
            'size' => round($validated['length'] * $validated['width'] * $validated['height'], 2),
            'price_per_day' => $validated['price_per_day'],
            'price_per_month' => $validated['price_per_month'] ?? null,
            'images' => $images,
            'is_available' => true,
            'is_deleted' => false,
        ]);

        return redirect()->route('home')->with('success', 'Room added!');
    }

    public function makeUnavailable($id): RedirectResponse
    {
        $room = Room::where('user_id', auth()->id())->findOrFail($id);
        $room->is_available = false;
        $room->save();

        return back()->with('success', 'Room marked as unavailable.');
    }

    public function makeAvailable($id): RedirectResponse
    {
        $room = Room::where('user_id', auth()->id())->findOrFail($id);
        $room->is_available = true;
        $room->save();

        return back()->with('success', 'Room marked as available.');
    }

    public function destroy($id): RedirectResponse
    {
        $room = Room::where('user_id', auth()->id())->findOrFail($id);
        $room->is_deleted = true;
        $room->save();

        return back()->with('success', 'Room deleted successfully.');
    }

}

