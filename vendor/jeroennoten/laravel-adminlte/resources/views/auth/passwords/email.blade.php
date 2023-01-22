@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@php( $password_email_url = View::getSection('password_email_url') ?? config('adminlte.password_email_url', 'password/email') )

@if (config('adminlte.use_route_url', false))
    @php( $password_email_url = $password_email_url ? route($password_email_url) : '' )
@else
    @php( $password_email_url = $password_email_url ? url($password_email_url) : '' )
@endif

{{-- @section('auth_header', __('adminlte::adminlte.password_reset_message')) --}}

@section('auth_body')
    <div class="mb-3 row d-flex justify-content-center">
        <img src="{{ asset(config('adminlte.logo_img')) }}" height="100">
    </div>
    <label class="row d-flex justify-content-center">
        {{ __('adminlte::adminlte.recovery_message') }}
    </label>
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ $password_email_url }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Send reset link button --}}
        <div class="col-13">
            <button type=submit class="btn btn-block btn-dark">
            {{-- {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}"> --}}
                {{-- <span class="fas fa-sign-in-alt"></span> --}}
                {{-- {{ __('adminlte::adminlte.sign_in') }} --}}
                {{ __('adminlte::adminlte.send_recovery_link') }}
            </button>
        </div>

    </form>
    <p class="mt-2 mb-3">
        <a href="{{ "/login" }}">
            {{ __('adminlte::adminlte.back') }}
        </a>
    </p>
@stop
