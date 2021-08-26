@extends('layouts.app')

@section('title', __('Reset Password'))

@section('content')
    <div class="login-box">
        <div class="container">
            <div class="row mx-0 justify-content-md-center">
                <div class="col-12 col-lg-9 text-center bg-white login_inside_box">
                    <h4 class="mt-0 mb-3 pb-0 pb-lg-4 pt-0 pt-lg-4 title">
                        <span>{{ __('Reset Password') }}</span>
                    </h4>
                    <div class="login-form">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group input-group mb-4">
                                <input id="email" type="email"
                                       class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       name="email" value="{{ $email ?? old('email') }}"
                                       placeholder="{{ __('E-Mail Address') }}" required autocomplete="email" autofocus>
                                @error('email')
                                <span class="invalid-feedback text-left" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group input-group mb-4">
                                <input id="password" type="password"
                                       class="form-control form-control-lg @error('password') is-invalid @enderror"
                                       name="password" placeholder="{{ __('Password') }}" required
                                       autocomplete="new-password">
                                @error('password')
                                <span class="invalid-feedback text-left" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group input-group mb-4">
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required
                                       autocomplete="new-password">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
