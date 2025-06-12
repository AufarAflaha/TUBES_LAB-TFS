@extends('layouts.app')

@section('title', 'Hapus Akun')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Hapus Akun</h1>
    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Peringatan</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    <p><strong>Perhatian!</strong> Tindakan ini tidak dapat dibatalkan.</p>
                    <p>Menghapus akun akan:</p>
                    <ul>
                        <li>Menghapus semua data pribadi Anda</li>
                        <li>Membatalkan semua peminjaman yang masih dalam status menunggu</li>
                        <li>Menghapus riwayat peminjaman Anda</li>
                        <li>Mengakhiri sesi login Anda</li>
                    </ul>
                </div>

                @error('active_bookings')
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror

                <form action="{{ route('profile.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Masukkan Password Anda untuk Konfirmasi</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirm" required>
                        <label class="form-check-label" for="confirm">
                            Saya mengerti bahwa tindakan ini tidak dapat dibatalkan
                        </label>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary me-md-2">Batal</a>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-user-times"></i> Hapus Akun Saya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Alternatif</h5>
            </div>
            <div class="card-body">
                <p>Jika Anda memiliki masalah dengan akun, pertimbangkan alternatif berikut:</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-edit"></i> Edit Profil
                    </a>
                    <a href="{{ route('profile.change-password') }}" class="btn btn-outline-warning">
                        <i class="fas fa-key"></i> Ubah Password
                    </a>
                    <a href="mailto:admin@telkomuniversity.ac.id" class="btn btn-outline-info">
                        <i class="fas fa-question-circle"></i> Hubungi Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
