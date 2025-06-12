@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Mahasiswa</h1>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total Peminjaman</h5>
                        <h2>{{ $stats['total_bookings'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Menunggu</h5>
                        <h2>{{ $stats['pending_bookings'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Disetujui</h5>
                        <h2>{{ $stats['approved_bookings'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Ditolak</h5>
                        <h2>{{ $stats['rejected_bookings'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Peminjaman Terbaru</h5>
                <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($recentBookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Ruang</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                <tr>
                                    <td>{{ $booking->room->name }}</td>
                                    <td>{{ $booking->booking_date->format('d/m/Y') }}</td>
                                    <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                                    <td>
                                        <span class="badge bg-{{ $booking->status_color }}">
                                            {{ $booking->status_text }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Belum ada peminjaman.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajukan Peminjaman
                    </a>
                    <a href="{{ route('rooms.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-door-open"></i> Lihat Ruang Tersedia
                    </a>
                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-history"></i> Riwayat Peminjaman
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
