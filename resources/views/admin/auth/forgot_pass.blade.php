@extends('layouts.master')

@section('title', 'Lupa Password')

@push('styles')
    <style>
        .logo{
            width: 45px;
            height: auto;
        }
        .Otp::-webkit-inner-spin-button, 
        .Otp::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .Otp{
            max-width: 5rem;
            -moz-appearance: textfield;
        }
    </style>
@endpush

@section('content')
<section class="container vh-100">
    <livewire:forgot-password/>
</section>
@endsection