<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ChatController extends Controller
{
    // Show chat between renter and owner for a booking
    public function show($bookingId)
    {
        $booking = Booking::with('room')->findOrFail($bookingId);

        if (!in_array($booking->status, ['paid', 'completed'])) {
            abort(403, 'Chat is only available after payment.');
        }

        $user = Auth::user();
        $ownerId = $booking->room->user_id;
        $renterId = $booking->renter_id;

        if ($user->id !== $ownerId && $user->id !== $renterId) {
            abort(403, 'Unauthorized');
        }

        $conversation = Conversation::firstOrCreate([
            'user_one_id' => min($ownerId, $renterId),
            'user_two_id' => max($ownerId, $renterId),
        ]);

        $messages = $conversation->messages()->with('sender')->orderBy('created_at')->get();

        return response()->json([
            'messages' => $messages,
            'conversationId' => $conversation->id,
            'userId' => $user->id,
        ]);
    }

    // Store a new message
    public function store(Request $request, $conversationId)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'conversation_id' => $conversationId,
            'sender_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        return response()->json($message->load('sender'));
    }
}
