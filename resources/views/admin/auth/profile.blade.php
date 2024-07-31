@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="container-md py-4 px-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="w-100 d-flex justify-content-between align-item-center mb-3">
                        <span class="text-gray-600 fs-5">Edit Profile</span>
                    </div>
                    <div class="card-content">
                        <form>
                            <div class="row mb-3">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-gray-600 fw-bold" for="nama">Nama</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="nama" name="nama" >
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-gray-600 fw-bold" for="email">Email</label>
                                        <div class="input-group">
                                            <input type="email" class="form-control" id="email" name="email" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-gray-600 fw-bold" for="username">Username</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="username" name="username" >
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-gray-600 fw-bold" for="password">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" >
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