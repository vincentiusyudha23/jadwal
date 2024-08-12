@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="container-md py-2 px-md-5 d-flex flex-column justify-content-center">
            <nav aria-label="breadcrumb" class="p-0 mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Halaman Utama</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ubah Password</li>
                </ol>
            </nav>
            <div class="card shadow-sm card-profile">
                <div class="card-body p-4">
                    <div class="w-100 d-flex justify-content-between align-item-center mb-3">
                        <span class="text-gray-600 fs-5">Ubah Password</span>
                    </div>
                    @if ($errors->updatePassword->count() > 0)
                        <div class="alert alert-danger" role="alert">
                            <ul>
                                @foreach ((array) $errors->updatePassword->all() as $msg)
                                    <li>{{ $msg }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="card-content">
                        <form method="POST" action="{{ route('admin.profile.update.password') }}">
                            @csrf
                            @method('put')
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-gray-600 fw-bold" for="username">Username</label>
                                        <div class="input-group">
                                            <input type="text" readonly class="form-control" id="username"
                                                name="username" value="{{ Auth::user()->username }}">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-gray-600 fw-bold" for="password">Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-gray-600 fw-bold" for="confirm_password">Ulangi
                                            Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_password"
                                                name="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 d-flex justify-content-end align-items-center mb-2">
                                <button type="submit" class="btn btn-success fw-bold">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar-admin>
@endsection
