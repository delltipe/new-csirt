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
}

.login-title {
    font-family: var(--font-display);
    font-size: 28px;
    font-weight: 900;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    margin: 0;
    text-align: center;
}

.login-body {
    padding: 40px;
}

.alert-error {
    background: var(--alert-bg);
    border-left: 4px solid var(--alert);
    color: #991b1b;
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
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1 class="login-title">Admin Login</h1>
        </div>
        <div class="login-body">
            @if($errors->any())
                <div class="alert-error">
                    {{ $errors->first() }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-input" id="email" name="email" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-input" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-submit">Login</button>
            </form>
        </div>
    </div>
</div>
@endsection