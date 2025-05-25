<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h4><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>Selamat datang, {{ Auth::user()->name }}!</h5>
                        <p class="text-muted">Email: {{ Auth::user()->email }}</p>
                        <p>Anda berhasil login ke sistem. Ini adalah halaman dashboard yang hanya bisa diakses oleh user yang sudah login.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="bg-light p-4 rounded">
                            <i class="fas fa-user-circle fa-5x text-primary mb-3"></i>
                            <h6>Member sejak:</h6>
                            <small class="text-muted">{{ Auth::user()->created_at->format('d M Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                <h5>Profile</h5>
                <p>Kelola informasi profile Anda</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-cog fa-3x text-success mb-3"></i>
                <h5>Settings</h5>
                <p>Atur konfigurasi akun Anda</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-chart-bar fa-3x text-warning mb-3"></i>
                <h5>Reports</h5>
                <p>Lihat laporan dan statistik</p>
            </div>
        </div>
    </div>
</div>
@endsection