@extends('layouts.app')

@section('content')
<style>
.login-container {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    background: var(--mist);
}

.login-card {
    background: var(--white);
    border: 2px solid var(--border);
    max-width: 460px;
    width: 100%;
    padding: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.login-header {
    background: var(--navy-dim);
    padding: 32px 40px;
    border-bottom: 3px solid var(--navy);
    text-align: center;
}

.login-title {
    font-family: var(--font-display);
    font-size: 28px;
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    margin: 0;
}

.login-sub {
    font-size: 13px;
    font-weight: 300;
    color: rgba(255,255,255,0.6);
    margin-top: 6px;
}

.login-body {
    padding: 40px;
}

.alert-error {
    background: var(--alert-bg);
    border-left: 4px solid var(--alert);
    color: var(--alert-dark);
    padding: 14px 16px;
    margin-bottom: 24px;
    font-size: 14px;
    font-weight: 500;
}

.form-group {
    margin-bottom: 24px;
}

.form-label {
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--ink);
    margin-bottom: 8px;
    display: block;
}

.form-label .req { color: var(--alert); margin-left: 2px; }

.form-input {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid var(--border);
    font-family: var(--font-body);
    font-size: 15px;
    color: var(--ink);
    transition: border-color var(--ease);
}

.form-input:focus {
    outline: none;
    border-color: var(--navy);
}

.form-input.is-invalid {
    border-color: var(--alert);
}

.field-error {
    font-size: 12px;
    color: var(--alert);
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.btn-submit {
    width: 100%;
    background: var(--navy);
    color: var(--white);
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 16px;
    border: none;
    cursor: pointer;
    transition: background var(--ease);
}

.btn-submit:hover {
    background: var(--navy-dim);
}

.login-footer {
    margin-top: 20px;
    text-align: center;
    font-size: 13px;
    color: var(--mid);
}

.login-footer a {
    color: var(--navy);
    font-weight: 600;
    text-decoration: none;
    border-bottom: 1px solid var(--navy);
}
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1 class="login-title">Masuk</h1>
            <p class="login-sub">Portal Pelaporan Insiden JakartaProv-CSIRT</p>
        </div>
        <div class="login-body">
            @if($errors->any())
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email <span class="req">*</span></label>
                    <input type="email" class="form-input @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                    <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi <span class="req">*</span></label>
                    <input type="password" class="form-input @error('password') is-invalid @enderror" id="password" name="password" required>
                    @error('password')
                    <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn-submit">Masuk</button>
            </form>

            <p class="login-footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
