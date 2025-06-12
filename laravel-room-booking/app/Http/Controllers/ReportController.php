<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Tambahkan ini
use Carbon\Carbon;

class ReportController extends Controller
{
    use AuthorizesRequests; // Tambahkan trait ini

    public function index()
    {
        // Only admin can access reports
        $this->authorize('viewReports', Booking::class);
        
        // Tambahkan statistik dasar untuk dashboard reports
        $totalBookings = Booking::count();
        $totalRooms = Room::count();
        $totalUsers = User::where('role', 'mahasiswa')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        
        return view('reports.index', compact('totalBookings', 'totalRooms', 'totalUsers', 'pendingBookings'));
    }
    
    public function bookingsByDate(Request $request)
    {
        $this->authorize('viewReports', Booking::class);
        
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $bookings = Booking::with(['user', 'room'])
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->orderBy('booking_date')
            ->get();
            
        $stats = [
            'total' => $bookings->count(),
            'approved' => $bookings->where('status', 'approved')->count(),
            'rejected' => $bookings->where('status', 'rejected')->count(),
            'pending' => $bookings->where('status', 'pending')->count(),
            'completed' => $bookings->where('status', 'completed')->count(),
        ];
        
        return view('reports.bookings-by-date', compact('bookings', 'stats', 'startDate', 'endDate'));
    }
    
    public function roomUsage(Request $request)
    {
        $this->authorize('viewReports', Booking::class);
        
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $roomUsage = Room::withCount(['bookings' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('booking_date', [$startDate, $endDate])
                  ->where('status', 'approved');
        }])
        ->orderBy('bookings_count', 'desc')
        ->get();
        
        // Calculate total hours for each room
        foreach ($roomUsage as $room) {
            $totalHours = 0;
            $bookings = $room->bookings()
                ->whereBetween('booking_date', [$startDate, $endDate])
                ->where('status', 'approved')
                ->get();
                
            foreach ($bookings as $booking) {
                $start = Carbon::parse($booking->start_time);
                $end = Carbon::parse($booking->end_time);
                $totalHours += $end->diffInHours($start);
            }
            
            $room->total_hours = $totalHours;
        }
        
        return view('reports.room-usage', compact('roomUsage', 'startDate', 'endDate'));
    }
    
    public function userActivity(Request $request)
    {
        $this->authorize('viewReports', Booking::class);
        
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $userActivity = User::where('role', 'mahasiswa')
            ->withCount(['bookings' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('booking_date', [$startDate, $endDate]);
            }])
            ->orderBy('bookings_count', 'desc')
            ->get();
            
        return view('reports.user-activity', compact('userActivity', 'startDate', 'endDate'));
    }
    
    public function export(Request $request)
    {
        $this->authorize('viewReports', Booking::class);
        
        $request->validate([
            'report_type' => 'required|in:bookings,room-usage,user-activity',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:csv,pdf',
        ]);
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $reportType = $request->report_type;
        $format = $request->format;
        
        // Here you would implement the export functionality
        // For now, we'll just redirect back with a success message
        
        return back()->with('success', 'Laporan berhasil diunduh.');
    }
}