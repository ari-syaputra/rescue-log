@extends('layouts.app')

@section('title', 'Validasi Logistik Sub-Posko')

@section('content')
<div class="w-full space-y-6 pb-12">

    <!-- JUDUL HALAMAN -->
    <x-komando.validasi.header />

    <!-- NOTIFIKASI SUKSES / ERROR -->
    <x-komando.validasi.alert />

    <!-- FILTER & PENCARIAN -->
    <x-komando.validasi.filter />

    <!-- TABEL UTAMA -->
    <x-komando.validasi.table :pengajuans="$pengajuans" />

</div>
@endsection