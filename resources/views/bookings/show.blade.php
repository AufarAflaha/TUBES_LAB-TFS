@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Peminjaman</h1>
    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Peminjaman</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Informasi Ruang</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Ruang:</strong></td>
                                <td>{{ $booking->room->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kode:</strong></td>
                                <td>{{ $booking->room->code }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kapasitas:</strong></td>
                                <td>{{ $booking->room->capacity }} orang</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Informasi Waktu</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td>{{ $booking->booking_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Waktu:</strong></td>
                                <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                            </tr>
                            <tr>
                                <td><strong>Diajukan:</strong></td>
                                <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Informasi Peminjam</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>{{ $booking->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>NIM:</strong></td>
                                <td>{{ $booking->user->nim }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $booking->user->email }}</td>
                            </tr>
                            @if($booking->user->phone)
                            <tr>
                                <td><strong>Telepon:</strong></td>
                                <td>{{ $booking->user->phone }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Status Peminjaman</h6>
                        <div class="mb-3">
                            <span class="badge bg-{{ $booking->status_color }} fs-6">
                                {{ $booking->status_text }}
                            </span>
                        </div>
                        
                        @if($booking->status !== 'pending')
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Diproses oleh:</strong></td>
                                    <td>{{ $booking->approvedBy->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Pada:</strong></td>
                                    <td>{{ $booking->approved_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted">Keperluan</h6>
                    <p>{{ $booking->purpose }}</p>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted">Jumlah Peserta</h6>
                    <p>{{ $booking->participant_count }} orang</p>
                </div>

                @if($booking->admin_notes)
                <div class="mb-4">
                    <h6 class="text-muted">Catatan Admin</h6>
                    <div class="alert alert-{{ $booking->status === 'approved' ? 'success' : 'danger' }}">
                        {{ $booking->admin_notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Aksi</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(Auth::user()->isAdmin() && $booking->status === 'pending')
                        <button type="button" class="btn btn-success" 
                                data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="fas fa-check"></i> Setujui Peminjaman
                        </button>
                        <button type="button" class="btn btn-danger" 
                                data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times"></i> Tolak Peminjaman
                        </button>
                    @endif

                    @if(Auth::user()->isMahasiswa() && $booking->status === 'pending')
                        <form action="#" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-times-circle"></i> Batalkan Pengajuan
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('rooms.show', $booking->room) }}" class="btn btn-outline-primary">
                        <i class="fas fa-door-open"></i> Lihat Detail Ruang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Approve (Admin only) -->
@if(Auth::user()->isAdmin() && $booking->status === 'pending')
    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1">
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
    <div class="modal fade" id="rejectModal" tabindex="-1">
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
@endsection