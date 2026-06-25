@extends('layouts.app')
@section('title', 'Verifica tu Correo Electrónico')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card p-4" style="max-width: 500px; width: 100%;">
        <div class="text-center mb-4">
            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--es-red), #e8051a); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 1.5rem;">
                <i class="bi bi-envelope-check-fill"></i>
            </div>
            <h2 class="mt-3" style="font-weight: 700; color: var(--es-dark); font-size: 1.5rem;">Verifica tu Correo</h2>
        </div>

        <div class="text-center mb-4" style="color: #6b7280; font-size: 0.95rem;">
            <p>¡Gracias por registrarte! Antes de comenzar, debes verificar tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar.</p>
            <p>Si no recibiste el correo, con gusto te enviaremos otro.</p>
        </div>

        @if (session('message'))
            <div class="alert alert-success text-center">
                {{ session('message') }}
            </div>
        @endif

        <div class="d-flex justify-content-center mt-2">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary px-4 py-2 text-white">
                    <i class="bi bi-send-fill me-2"></i>Reenviar Correo de Verificación
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
