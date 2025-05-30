@extends('layouts.app')

@section('title', 'Ajukan Peminjaman Ruang')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Ajukan Peminjaman Ruang</h1>
    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('bookings.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="room_id" class="form-label">Ruang Kuliah <span class="text-danger">*</span></label>
                        <select class="form-select @error('room_id') is-invalid @enderror" id="room_id" name="room_id" required>
                            <option value="">Pilih Ruang</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ old('room_id', request('room_id')) == $room->id ? 'selected' : '' }}
                                        data-capacity="{{ $room->capacity }}">
                                    {{ $room->name }} ({{ $room->code }}) - Kapasitas: {{ $room->capacity }} orang
                                </option>
                            @endforeach
                        </select>
                        @error('room_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="booking_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('booking_date') is-invalid @enderror" 
                                   id="booking_date" name="booking_date" value="{{ old('booking_date') }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('booking_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="start_time" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="end_time" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="purpose" class="form-label">Keperluan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('purpose') is-invalid @enderror" 
                                  id="purpose" name="purpose" rows="3" required>{{ old('purpose') }}</textarea>
                        <div class="form-text">Jelaskan secara detail keperluan peminjaman ruang.</div>
                        @error('purpose')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="participant_count" class="form-label">Jumlah Peserta <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('participant_count') is-invalid @enderror" 
                               id="participant_count" name="participant_count" value="{{ old('participant_count', 1) }}" 
                               min="1" required>
                        <div class="form-text">Pastikan jumlah peserta tidak melebihi kapasitas ruang.</div>
                        @error('participant_count')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('bookings.index') }}" class="btn btn-secondary me-md-2">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <strong>Perhatian:</strong>
                    <ul class="mb-0 ps-3">
                        <li>Peminjaman harus diajukan minimal 1 hari sebelumnya.</li>
                        <li>Pastikan jumlah peserta tidak melebihi kapasitas ruang.</li>
                        <li>Pengajuan akan diproses oleh admin.</li>
                        <li>Status peminjaman dapat dilihat di halaman Peminjaman.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roomSelect = document.getElementById('room_id');
        const participantCount = document.getElementById('participant_count');
        
        roomSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const capacity = selectedOption.getAttribute('data-capacity');
            
            if (capacity) {
                participantCount.setAttribute('max', capacity);
                document.querySelector('.form-text').innerHTML = 
                    `Pastikan jumlah peserta tidak melebihi kapasitas ruang (${capacity} orang).`;
            }
        });
    });
</script>
@endpush
@endsection