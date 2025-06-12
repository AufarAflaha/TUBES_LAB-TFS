<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::paginate(10);
        return view('rooms.index', compact('rooms'));
    }

    public function show(Room $room)
    {
        $room->load(['bookings' => function($query) {
            $query->where('booking_date', '>=', now()->toDateString())
                  ->where('status', '!=', 'rejected')
                  ->orderBy('booking_date')
                  ->orderBy('start_time');
        }]);
        
        return view('rooms.show', compact('room'));
    }

    public function create()
    {
        // Cek apakah user adalah admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('rooms.index')
                ->with('error', 'Akses ditolak. Anda bukan admin.');
        }
        
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        // Cek apakah user adalah admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('rooms.index')
                ->with('error', 'Akses ditolak. Anda bukan admin.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:rooms',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'status' => 'required|in:available,maintenance,unavailable',
        ]);

        Room::create($validated);

        return redirect()->route('rooms.index')
            ->with('success', 'Ruang berhasil ditambahkan.');
    }

    public function edit(Room $room)
    {
        // Cek apakah user adalah admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('rooms.index')
                ->with('error', 'Akses ditolak. Anda bukan admin.');
        }
        
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        // Cek apakah user adalah admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('rooms.index')
                ->with('error', 'Akses ditolak. Anda bukan admin.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:rooms,code,' . $room->id,
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'status' => 'required|in:available,maintenance,unavailable',
        ]);

        $room->update($validated);

        return redirect()->route('rooms.index')
            ->with('success', 'Ruang berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        // Cek apakah user adalah admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('rooms.index')
                ->with('error', 'Akses ditolak. Anda bukan admin.');
        }
        
        if ($room->bookings()->where('status', '!=', 'rejected')->exists()) {
            return back()->with('error', 'Tidak dapat menghapus ruang yang memiliki peminjaman aktif.');
        }
        
        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Ruang berhasil dihapus.');
    }
}