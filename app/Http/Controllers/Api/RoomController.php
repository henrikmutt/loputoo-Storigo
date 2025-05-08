<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Review;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    // Return all rooms
    public function index()
    {
        $rooms = Room::withAvg('reviews', 'rating')->get();

        return response()->json($rooms);
    }

    public function show(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $reviews = Review::with('from_user')
            ->where('to_room_id', $room->id)
            ->where('is_deleted', false)
            ->latest()
            ->get();

        return inertia('rooms/Show', [
            'room' => $room,
            'reviews' => $reviews,
            'hideRentButton' => $request->input('hideRentButton', false)
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

    public function softDelete($id)
    {
        $room = Room::where('user_id', auth()->id())->findOrFail($id);
        $room->is_deleted = true;
        $room->save();

        return back()->with('success', 'Room deleted.');
    }


    public function update(Request $request, $id)
    {
        $room = Room::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'length' => 'required|numeric|min:0.1',
            'width' => 'required|numeric|min:0.1',
            'height' => 'required|numeric|min:0.1',
            'price_per_day' => 'nullable|numeric|min:0',
            'price_per_month' => 'nullable|numeric|min:0',
            'imagesToDelete' => 'array',
            'imagesToDelete.*' => 'string',
            'newImages.*' => 'image|max:2048',
        ]);

        $room->update([
            'location' => $validated['location'],
            'description' => $validated['description'],
            'length' => $validated['length'],
            'width' => $validated['width'],
            'height' => $validated['height'],
            'size' => round($validated['length'] * $validated['width'] * $validated['height'], 2),
            'price_per_day' => $validated['price_per_day'] ?? null,
            'price_per_month' => $validated['price_per_month'] ?? null,
        ]);

        $existingImages = $room->images ?? [];
        $imagesToDelete = $request->input('imagesToDelete', []);
        $room->images = array_values(array_diff($existingImages, $imagesToDelete));
        foreach ($imagesToDelete as $image) {
            Storage::disk('public')->delete($image);
        }

        if ($request->hasFile('newImages')) {
            $newImages = [];
            foreach ($request->file('newImages') as $file) {
                $newImages[] = $file->store('rooms', 'public');
            }
            $room->images = array_merge($room->images ?? [], $newImages);
        }

        $room->save();

        return redirect()->back()->with('success', 'Room updated successfully.');
    }


}

