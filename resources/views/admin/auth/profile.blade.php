@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <div class="container-md py-4 px-md-5 d-flex justify-content-center">
            <div class="card shadow-sm card-profile">
                <div class="card-body p-4">
                    <div class="w-100 d-flex justify-content-between align-item-center mb-3">
                        <span class="text-gray-600 fs-5">Ubah Password</span>
                    </div>
                    <div class="card-content">
                        <form>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label text-gray-600 fw-bold" for="username">Username</label>
                                        <div class="input-group">
                                            <input type="text" readonly class="form-control" id="username" name="username" value="{{ Auth::user()->username }}">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-gray-600 fw-bold" for="password">Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" >
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-gray-600 fw-bold" for="confirm_password">Ulangi Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" >
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