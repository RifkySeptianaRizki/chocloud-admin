@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1>Selamat Datang, {{ Auth::user()->name }}!</h1>
    <p>Ini adalah halaman utama panel admin Chocloud. Silakan kelola konten website Anda melalui menu di samping.</p>
@endsection