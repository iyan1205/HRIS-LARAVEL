@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Karyawan</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Form Pencarian -->
        <form action="{{ route('karyawan.index2') }}" method="GET" class="d-flex w-100">
            <input type="text" name="search" class="form-control me-2" 
                   placeholder="Cari nama, NIK, departemen, atau jabatan..." 
                   value="{{ request('search') }}">
            <select name="entries" class="form-select me-2" onchange="this.form.submit()">
                <option value="10" {{ $entries == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $entries == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $entries == 50 ? 'selected' : '' }}>50</option>
            </select>
            <button class="btn btn-primary" type="submit">Cari</button>
        </form>
    </div>

    <!-- Tabel Karyawan -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NIK</th>
                <th>Nama</th>
                <th>Departemen</th>
                <th>Jabatan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($karyawans as $karyawan)
                <tr>
                    <td>{{ $karyawan->nik }}</td>
                    <td>{{ $karyawan->name }}</td>
                    <td>{{ $karyawan->departemen->name ?? '-' }}</td>
                    <td>{{ $karyawan->jabatan->name ?? '-' }}</td>
                    <td>{{ $karyawan->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Data tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-between">
        <p>Menampilkan {{ $karyawans->count() }} dari {{ $karyawans->total() }} data</p>
        <div>
            {{ $karyawans->appends(request()->all())->links() }}
        </div>
    </div>
</div>
@endsection
