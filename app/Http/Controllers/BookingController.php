<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $bookings = Booking::with(['user', 'room'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $bookings = $user->bookings()
                ->with('room')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        
        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $rooms = Room::where('status', 'available')->get();
        return view('bookings.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'purpose' => 'required|string|max:500',
            'participant_count' => 'required|integer|min:1',
        ]);

        $room = Room::findOrFail($validated['room_id']);
        
        // Check room capacity
        if ($validated['participant_count'] > $room->capacity) {
            return back()->withErrors([
                'participant_count' => 'Jumlah peserta melebihi kapasitas ruang (' . $room->capacity . ' orang).'
            ])->withInput();
        }
        
        // Check availability
        if (!$room->isAvailable($validated['booking_date'], $validated['start_time'], $validated['end_time'])) {
            return back()->withErrors([
                'booking_date' => 'Ruang tidak tersedia pada waktu yang dipilih.'
            ])->withInput();
        }

        $validated['user_id'] = Auth::id();
        Booking::create($validated);

        return redirect()->route('bookings.index')
            ->with('success', 'Pengajuan peminjaman berhasil dibuat.');
    }

    public function show(Booking $booking)
    {
        // Cek apakah user bisa melihat booking ini
        if (Auth::user()->isMahasiswa() && $booking->user_id !== Auth::id()) {
            return redirect()->route('bookings.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat peminjaman ini.');
        }
        
        $booking->load(['user', 'room', 'approvedBy']);
        return view('bookings.show', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        // Cek apakah user adalah pemilik booking atau admin
        if (Auth::user()->isMahasiswa() && $booking->user_id !== Auth::id()) {
            return redirect()->route('bookings.index')
                ->with('error', 'Anda tidak memiliki akses untuk membatalkan peminjaman ini.');
        }

        // Hanya bisa dibatalkan jika status masih pending
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Peminjaman yang sudah diproses tidak dapat dibatalkan.');
        }

        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
    }

    public function approve(Request $request, Booking $booking)
    {
        // Cek apakah user adalah admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('bookings.index')
                ->with('error', 'Akses ditolak. Anda bukan admin.');
        }
        
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $booking->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Request $request, Booking $booking)
    {
        // Cek apakah user adalah admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('bookings.index')
                ->with('error', 'Akses ditolak. Anda bukan admin.');
        }
        
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $booking->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}