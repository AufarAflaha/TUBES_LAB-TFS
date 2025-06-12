<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $stats = [
                'total_rooms' => Room::count(),
                'total_users' => User::where('role', 'mahasiswa')->count(),
                'pending_bookings' => Booking::where('status', 'pending')->count(),
                'approved_bookings' => Booking::where('status', 'approved')->count(),
            ];
            
            $recentBookings = Booking::with(['user', 'room'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            return view('dashboard.admin', compact('stats', 'recentBookings'));
        } else {
            $stats = [
                'total_bookings' => $user->bookings()->count(),
                'pending_bookings' => $user->bookings()->where('status', 'pending')->count(),
                'approved_bookings' => $user->bookings()->where('status', 'approved')->count(),
                'rejected_bookings' => $user->bookings()->where('status', 'rejected')->count(),
            ];
            
            $recentBookings = $user->bookings()
                ->with('room')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            return view('dashboard.mahasiswa', compact('stats', 'recentBookings'));
        }
    }
}
