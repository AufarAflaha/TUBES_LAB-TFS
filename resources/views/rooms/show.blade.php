@extends('layouts.app')

@section('title', 'Detail Ruang - ' . $room->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Ruang: {{ $room->name }}</h1>
    <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Ruang</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>{{ $room->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kode:</strong></td>
                                <td>{{ $room->code }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kapasitas:</strong></td>
                                <td>{{ $room->capacity }} orang</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $room->status === 'available' ? 'success' : ($room->status === 'maintenance' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($room->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($room->description)
                <div class="mt-3">
                    <h6><strong>Deskripsi</strong></h6>
                    <p>{{ $room->description }}</p>
                </div>
                @endif

                @if($room->facilities)
                <div class="mt-3">
                    <h6><strong>Fasilitas</strong></h6>
                    <p>{{ $room->facilities }}</p>
                </div>
                @endif
            </div>
        </div>

        @if($room->bookings && $room->bookings->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Jadwal Peminjaman</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Peminjam</th>
                                <th>Keperluan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($room->bookings as $booking)
                            <tr>
                                <td>{{ $booking->booking_date->format('d/m/Y') }}</td>
                                <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ Str::limit($booking->purpose, 50) }}</td>
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
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(Auth::user()->isMahasiswa() && $room->status === 'available')
                        <a href="{{ route('bookings.create') }}?room_id={{ $room->id }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus"></i> Ajukan Peminjaman
                        </a>
                    @endif
                    
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('rooms.edit', $room) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Ruang
                        </a>
                        <form action="{{ route('rooms.destroy', $room) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus ruang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Hapus Ruang
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection