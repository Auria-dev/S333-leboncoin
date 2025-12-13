@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="card shadow col-md-6 mx-auto">
        <div class="card-header bg-primary text-white">Vérification Mobile</div>
        <div class="card-body text-center">
            <p>Code envoyé au <strong>{{ auth()->user()->telephone }}</strong>.</p>

            @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
            @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

            <form action="{{ route('traiter.verification.telephone') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="text" name="code_sms" class="form-control text-center fs-3" maxlength="4" placeholder="XXXX" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Valider</button>
            </form>
        </div>
    </div>
</div>
@endsection