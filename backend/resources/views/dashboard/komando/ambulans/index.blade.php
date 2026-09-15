@extends('layouts.app')

@section('title', 'Response Center SOS Ambulans - Posko Komando')

@section('content')
<div class="space-y-6 pb-12">

    <!-- Flash Message Alert -->
    <x-komando.ambulans.alert />

    <!-- Header Section -->
    <x-komando.ambulans.header />

    <!-- 4 Stat Cards Emergency (Bulat Sempurna & Heroicons) -->
    <x-komando.ambulans.stats :stats="$stats" />

    <!-- Tabel Utama Response Center -->
    <x-komando.ambulans.table :requests="$requests" />

</div>

<!-- Modal Plotting Ambulans & RS Rujukan -->
<x-komando.ambulans.modal-assign :armadaStandby="$armadaStandby" />

@push('scripts')
<script>
    function openModalAssign(id, kodeSos, namaPasien) {
        document.getElementById('formAssign').action = `/komando/ambulans/${id}/assign`;
        document.getElementById('assignKodeSos').innerText = kodeSos;
        document.getElementById('assignNamaPasien').innerText = namaPasien;
        document.getElementById('modalAssign').classList.remove('hidden');
    }

    function closeModalAssign() {
        document.getElementById('modalAssign').classList.add('hidden');
    }
</script>
@endpush
@endsection