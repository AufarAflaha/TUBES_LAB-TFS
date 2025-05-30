@extends('layouts.app')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Daftar Peminjaman</h1>
    @if(Auth::user()->isMahasiswa())
        <a href="{{ route('bookings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajukan Peminjaman
        </a>
    @endif
</div>

@if($bookings->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            @if(Auth::user()->isAdmin())
                                <th>Peminjam</th>
                            @endif
                            <th>Ruang</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Keperluan</th>
                            <th>Peserta</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            @if(Auth::user()->isAdmin())
                                <td>
                                    <div>
                                        <strong>{{ $booking->user->name }}</strong><br>
                                        <small class="text-muted">{{ $booking->user->nim }}</small>
                                    </div>
                                </td>
                            @endif
                            <td>
                                <div>
                                    <strong>{{ $booking->room->name }}</strong><br>
                                    <small class="text-muted">{{ $booking->room->code }}</small>
                                </div>
                            </td>
                            <td>{{ $booking->booking_date->format('d/m/Y') }}</td>
                            <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                            <td>{{ Str::limit($booking->purpose, 50) }}</td>
                            <td>{{ $booking->participant_count }} orang</td>
                            <td>
                                <span class="badge bg-{{ $booking->status_color }}">
                                    {{ $booking->status_text }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    
                                    @if(Auth::user()->isAdmin() && $booking->status === 'pending')
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                data-bs-toggle="modal" data-bs-target="#approveModal{{ $booking->id }}">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $booking->id }}">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $bookings->links() }}
        </div>
    @endif

    <!-- Modal untuk Approve/Reject (Admin only) -->
    @if(Auth::user()->isAdmin())
        @foreach($bookings as $booking)
            @if($booking->status === 'pending')
                <!-- Approve Modal -->
                <div class="modal fade" id="approveModal{{ $booking->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Setujui Peminjaman</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('bookings.approve', $booking) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <p>Yakin ingin menyetujui peminjaman ruang <strong>{{ $booking->room->name }}</strong> 
                                       oleh <strong>{{ $booking->user->name }}</strong>?</p>
                                    <div class="mb-3">
                                        <label for="admin_notes" class="form-label">Catatan Admin (Opsional)</label>
                                        <textarea class="form-control" name="admin_notes" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success">Setujui</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Reject Modal -->
                <div class="modal fade" id="rejectModal{{ $booking->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tolak Peminjaman</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('bookings.reject', $booking) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <p>Yakin ingin menolak peminjaman ruang <strong>{{ $booking->room->name }}</strong> 
                                       oleh <strong>{{ $booking->user->name }}</strong>?</p>
                                    <div class="mb-3">
                                        <label for="admin_notes" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="admin_notes" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Tolak</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif

@else
    <div class="text-center py-5">
        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
        <h4 class="text-muted">Belum Ada Peminjaman</h4>
        @if(Auth::user()->isMahasiswa())
            <p class="text-muted">Silakan ajukan peminjaman ruang kuliah.</p>
            <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajukan Peminjaman Pertama
            </a>
        @else
            <p class="text-muted">Belum ada pengajuan peminjaman dari mahasiswa.</p>
        @endif
    </div>
@endif
@endsection