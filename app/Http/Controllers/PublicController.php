<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class PublicController extends Controller
{
    public function index()
    {
        $rooms = Room::where('is_available', true)
                     ->where('is_deleted', false)
                     ->get();

        return inertia('Home', [
            'rooms' => $rooms
        ]);
    }
}
