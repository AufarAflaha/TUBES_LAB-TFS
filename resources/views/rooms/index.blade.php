@extends('layouts.app')

@section('title', 'Daftar Ruang Kuliah')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Daftar Ruang Kuliah</h1>
    @if(Auth::user()->isAdmin())
        <a href="{{ route('rooms.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Ruang
        </a>
    @endif
</div>

<div class="row">
    @forelse($rooms as $room)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title">{{ $room->name }}</h5>
                        <span class="badge bg-{{ $room->status === 'available' ? 'success' : ($room->status === 'maintenance' ? 'warning' : 'danger') }}">
                            {{ ucfirst($room->status) }}
                        </span>
                    </div>
                    <p class="card-text">
                        <strong>Kode:</strong> {{ $room->code }}<br>
                        <strong>Kapasitas:</strong> {{ $room->capacity }} orang<br>
                        @if($room->description)
                            <strong>Deskripsi:</strong> {{ Str::limit($room->description, 100) }}
                        @endif
                    </p>
                    @if($room->facilities)
                        <p class="card-text">
                            <strong>Fasilitas:</strong> {{ Str::limit($room->facilities, 80) }}
                        </p>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('rooms.show', $room) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('rooms.edit', $room) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('Yakin ingin menghapus ruang ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Belum Ada Ruang Kuliah</h4>
                <p class="text-muted">Silakan tambahkan ruang kuliah terlebih dahulu.</p>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Ruang Pertama
                    </a>
                @endif
            </div>
        </div>
    @endforelse
</div>

@if($rooms->hasPages())
    <div class="d-flex justify-content-center">
        {{ $rooms->links() }}
    </div>
@endif
@endsection