@extends('layouts.app')

@section('title', 'Laporan Aktivitas Mahasiswa - Sistem Peminjaman Ruang')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan Aktivitas Mahasiswa</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <form action="{{ route('reports.export') }}" method="POST">
                @csrf
                <input type="hidden" name="report_type" value="user-activity">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <div class="input-group">
                    <select name="format" class="form-select">
                        <option value="csv">CSV</option>
                        <option value="pdf">PDF</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </form>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Laporan Aktivitas Mahasiswa: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jumlah Peminjaman</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($userActivity as $user)
                    <tr>
                        <td>{{ $user->nim }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->bookings_count }}</td>
                        <td>
                            <a href="{{ route('bookings.index', ['user_id' => $user->id]) }}" class="btn btn-sm btn-outline-primary">
                                Lihat Peminjaman
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data aktivitas mahasiswa dalam rentang waktu ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Grafik Aktivitas Mahasiswa</h5>
    </div>
    <div class="card-body">
        <canvas id="userActivityChart" width="400" height="200"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('userActivityChart').getContext('2d');
    const userActivityChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($userActivity->pluck('name')->toArray()) !!},
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: {!! json_encode($userActivity->pluck('bookings_count')->toArray()) !!},
                backgroundColor: 'rgba(200, 16, 46, 0.7)',
                borderColor: 'rgba(200, 16, 46, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>
@endpush
