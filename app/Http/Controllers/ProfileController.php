<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        // Only validate NIM for students, and only allow editing if user is not a student
        if ($user->role === 'mahasiswa') {
            // Students cannot edit their NIM
            $rules['nim'] = ['required', 'string', 'max:20', 'unique:users,nim,' . $user->id];
        } else {
            // Admin users don't need NIM
            $rules['nim'] = ['nullable', 'string', 'max:20', 'unique:users,nim,' . $user->id];
        }

        $validated = $request->validate($rules);

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePassword()
    {
        return view('profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.'
            ]);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')->with('success', 'Password berhasil diubah.');
    }

    public function confirmDelete()
    {
        $user = Auth::user();
        
        // Check if user has active bookings
        $activeBookings = $user->bookings()->whereIn('status', ['pending', 'approved'])->count();
        
        if ($activeBookings > 0) {
            return redirect()->route('profile.show')->withErrors([
                'active_bookings' => 'Tidak dapat menghapus akun karena masih memiliki ' . $activeBookings . ' peminjaman aktif. Silakan batalkan atau tunggu hingga peminjaman selesai.'
            ]);
        }
        
        return view('profile.confirm-delete');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password tidak sesuai.'
            ]);
        }

        // Double check for active bookings before deletion
        $activeBookings = $user->bookings()->whereIn('status', ['pending', 'approved'])->count();
        
        if ($activeBookings > 0) {
            return back()->withErrors([
                'active_bookings' => 'Tidak dapat menghapus akun karena masih memiliki peminjaman aktif.'
            ]);
        }

        // Cancel all pending bookings before deleting user
        $user->bookings()->where('status', 'pending')->update([
            'status' => 'rejected',
            'admin_notes' => 'Dibatalkan karena pengguna menghapus akun'
        ]);

        Auth::logout();
        $user->delete();

        return redirect()->route('login')->with('success', 'Akun berhasil dihapus. Terima kasih telah menggunakan layanan kami.');
    }
}
