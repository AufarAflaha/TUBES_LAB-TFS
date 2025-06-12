@extends('layouts.app')

@section('title', 'Laporan Penggunaan Ruang - Sistem Peminjaman Ruang')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan Penggunaan Ruang</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <form action="{{ route('reports.export') }}" method="POST">
                @csrf
                <input type="hidden" name="report_type" value="room-usage">
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
        <h5 class="mb-0">Laporan Penggunaan Ruang: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Kode Ruang</th>
                        <th>Nama Ruang</th>
                        <th>Kapasitas</th>
                        <th>Jumlah Peminjaman</th>
                        <th>Total Jam Penggunaan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roomUsage as $room)
                    <tr>
                        <td>{{ $room->code }}</td>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->capacity }} orang</td>
                        <td>{{ $room->bookings_count }}</td>
                        <td>{{ $room->total_hours }} jam</td>
                        <td>
                            <span class="badge bg-{{ $room->status == 'available' ? 'success' : ($room->status == 'maintenance' ? 'warning' : 'danger') }}">
                                {{ $room->status == 'available' ? 'Tersedia' : ($room->status == 'maintenance' ? 'Maintenance' : 'Tidak Tersedia') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data penggunaan ruang dalam rentang waktu ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ruang Paling Sering Digunakan</h5>
            </div>
            <div class="card-body">
                <canvas id="roomUsageChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Total Jam Penggunaan</h5>
            </div>
            <div class="card-body">
                <canvas id="roomHoursChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Room Usage Chart
    const roomUsageCtx = document.getElementById('roomUsageChart').getContext('2d');
    const roomUsageChart = new Chart(roomUsageCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($roomUsage->pluck('name')->toArray()) !!},
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: {!! json_encode($roomUsage->pluck('bookings_count')->toArray()) !!},
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
    
    // Room Hours Chart
    const roomHoursCtx = document.getElementById('roomHoursChart').getContext('2d');
    const roomHoursChart = new Chart(roomHoursCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($roomUsage->pluck('name')->toArray()) !!},
            datasets: [{
                label: 'Total Jam',
                data: {!! json_encode($roomUsage->pluck('total_hours')->toArray()) !!},
                backgroundColor: [
                    'rgba(200, 16, 46, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(200, 16, 46, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        }
    });
});
</script>
@endpush
