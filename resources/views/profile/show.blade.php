@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Profil Saya</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Profil
        </a>
    </div>
</div>

@if($errors->has('active_bookings'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> {{ $errors->first('active_bookings') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Profil</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Nama:</strong>
                    </div>
                    <div class="col-sm-9">
                        {{ $user->name }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Email:</strong>
                    </div>
                    <div class="col-sm-9">
                        {{ $user->email }}
                    </div>
                </div>
                
                @if($user->role === 'mahasiswa')
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>NIM:</strong>
                    </div>
                    <div class="col-sm-9">
                        {{ $user->nim }}
                    </div>
                </div>
                @endif
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>No. Telepon:</strong>
                    </div>
                    <div class="col-sm-9">
                        {{ $user->phone ?? '-' }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Role:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                            {{ $user->role === 'admin' ? 'Administrator' : 'Mahasiswa' }}
                        </span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Bergabung:</strong>
                    </div>
                    <div class="col-sm-9">
                        {{ $user->created_at->format('d F Y') }}
                    </div>
                </div>
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
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit"></i> Edit Profil
                    </a>
                    <a href="{{ route('profile.change-password') }}" class="btn btn-outline-warning">
                        <i class="fas fa-key"></i> Ubah Password
                    </a>
                    <a href="{{ route('profile.confirm-delete') }}" class="btn btn-outline-danger">
                        <i class="fas fa-trash"></i> Hapus Akun
                    </a>
                </div>
            </div>
        </div>
        
        @if($user->role === 'mahasiswa')
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Statistik Peminjaman</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $user->bookings()->count() }}</h4>
                        <small>Total Peminjaman</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $user->bookings()->where('status', 'approved')->count() }}</h4>
                        <small>Disetujui</small>
                    </div>
                </div>
                <div class="row text-center mt-2">
                    <div class="col-6">
                        <h4 class="text-warning">{{ $user->bookings()->where('status', 'pending')->count() }}</h4>
                        <small>Menunggu</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-danger">{{ $user->bookings()->where('status', 'rejected')->count() }}</h4>
                        <small>Ditolak</small>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
