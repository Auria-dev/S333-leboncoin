@extends('layout') {{-- Mets ici le nom de ton layout principal --}}

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">Vérification SMS</div>
                <div class="card-body text-center">
                    <p>Un code a été envoyé au <strong>{{ auth()->user()->telephone }}</strong>.</p>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('traiter.verification.telephone') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="code_sms" class="form-control text-center fs-2" 
                                   maxlength="4" placeholder="XXXX" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Valider</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection