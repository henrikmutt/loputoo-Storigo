<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'to_room_id' => 'required|exists:rooms,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review = Review::create([
            'from_user_id' => auth()->id(),
            'to_user_id' => auth()->id(),
            'to_room_id' => $validated['to_room_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_deleted' => false,
        ]);

        return redirect()->back()->with('success', 'Review added successfully!');
    }
}
