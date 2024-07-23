@extends('layouts.master')

@section('title', 'Login')

@push('styles')
    <style>
        .side-left>img {
            width: 200px;
            height: 200px;
        }

        .side-right>.login-card {
            width: 50%;
        }

        .side-right .btn {
            color: rgba(var(--bs-primary-rgb), 1);
        }

        .side-right .btn:hover {
            background-color: rgba(var(--bs-primary-rgb), 1);
            border: 0;
            color: #fff;
        }

        @media (max-width: 575.98px) {
            .side-left>img {
                width: 150px;
                height: 150px;
            }

            .side-right>.login-card {
                width: 70%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row w-100 vh-100 m-0 p-0">
        <div class="col-lg-6 lg-h-100">
            <div class="side-left w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                <img src="{{ assets('img/logo-1.png') }}" alt="image" />
                <h2 class="text-primary mt-2 fw-bold">PT. WIRA GRIYA</h2>
            </div>
        </div>
        <div class="col-lg-6 bg-primary lg-h-100">
            <div class="side-right w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                <div class="login-card">
                    <div class="w-100 text-center mb-4">
                        <h4 class="text-white">LOGIN ADMIN</h4>
                    </div>
                    @if ($errors->get('username') || $errors->get('password'))
                        <div class="alert alert-danger" role="alert">
                            A simple danger alert—check it out!
                        </div>
                    @endif
                    <form class="w-100" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" aria-describedby="basic-addon3 basic-addon4"
                                    name="username" placeholder="Username">
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="password" class="form-control" aria-describedby="basic-addon3 basic-addon4"
                                    name="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="w-100 mb-3">
                            <button id="show-password" class="btn btn-transparent" type="button" style="border: 0px;">
                                <input type="checkbox" class="form-check-input">
                                <span class="text-white px-2">Show Password</span>
                            </button>
                        </div>
                        <div class="w-100">
                            <button class="btn btn-light w-100" type="submit">
                                <span class="fw-bold">SIGN IN</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('#show-password').on('click', function(){
                var el = $(this);
                var checked = el.find('input[type="checkbox"]');

                if(checked.prop('checked')){
                    checked.attr('checked', false);
                    $('input[name="password"]').attr('type', 'password');
                } else {
                    checked.attr('checked', 'checked');
                    $('input[name="password"]').attr('type', 'text');
                }
            });
        });
    </script>
@endpush
