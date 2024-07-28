@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <style>
    </style>
@endpush

@section('content')
    <x-navbar-admin :name="Auth::user()->name">
        <h1>HALO</h1>
    </x-navbar-admin>
@endsection