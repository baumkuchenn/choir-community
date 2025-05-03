@extends('layouts.bootstrap-only')

@foreach($tickets as $ticket)
    <div class="card shadow col-12 mb-3">
        <div class="card-body text-center">
            <p class="mb-0 fw-bold">{{ $ticket->ticket_type->nama }} #{{ $ticket->number }}</p>
            <hr>
            <img src="{{ asset('storage/' . $ticket->barcode_image) }}" alt="Barcode" style="width: 100%;">
            <p>{{ $ticket->barcode_code }}</p>
        </div>
    </div>
@endforeach