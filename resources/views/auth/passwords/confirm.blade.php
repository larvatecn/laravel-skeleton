@extends('layouts.app')

@section('title', __('Confirm Password'))

@section('content')
    <div class="login-box">
        <div class="container">
            <div class="row mx-0 justify-content-md-center">
                <div class="col-12 col-lg-9 text-center bg-white login_inside_box">
                    <h4 class="mt-0 mb-3 pb-0 pb-lg-4 pt-0 pt-lg-4 title">
                        <span>{{ __('Confirm Password') }}</span>
                    </h4>
                    <div class="login-form">
                        {{ __('Please confirm your password before continuing.') }}
                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf
                            <div class="form-group input-group mb-4">
                                <input id="password" type="password"
                                       class="form-control form-control-lg @error('password') is-invalid @enderror"
                                       name="password" placeholder="{{ __('Password') }}" required
                                       autocomplete="current-password">
                                @error('password')
                                <span class="invalid-feedback text-left" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Confirm Password') }}
                                </button>
                            </div>

                            <dl class="row">
                                @if (Route::has('password.request'))
                                    <dd class="text-right"><a href="{{ route('password.request') }}"
                                                              class="text-muted ml-3"><small>{{ __('Forgot Your Password?') }}</small></a>
                                    </dd>
                                @endif
                            </dl>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
