@extends('layouts.app')

@section('title', 'Laporan - Sistem Peminjaman Ruang')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan</h1>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Laporan Peminjaman Berdasarkan Tanggal</h5>
                <p class="card-text">Lihat data peminjaman ruang dalam rentang waktu tertentu.</p>
                
                <form action="{{ route('reports.bookings-by-date') }}" method="GET">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required value="{{ date('Y-m-d', strtotime('-30 days')) }}">
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required value="{{ date('Y-m-d') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Lihat Laporan</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Laporan Penggunaan Ruang</h5>
                <p class="card-text">Lihat statistik penggunaan ruang dalam rentang waktu tertentu.</p>
                
                <form action="{{ route('reports.room-usage') }}" method="GET">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required value="{{ date('Y-m-d', strtotime('-30 days')) }}">
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required value="{{ date('Y-m-d') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Lihat Laporan</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Laporan Aktivitas Mahasiswa</h5>
                <p class="card-text">Lihat statistik aktivitas peminjaman ruang oleh mahasiswa.</p>
                
                <form action="{{ route('reports.user-activity') }}" method="GET">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required value="{{ date('Y-m-d', strtotime('-30 days')) }}">
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required value="{{ date('Y-m-d') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Lihat Laporan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
