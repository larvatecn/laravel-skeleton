@extends('layouts.app')

@section('title', __('Register'))

@section('content')
    <div class="container py-6 login-box">
        <div class="row justify-content-center shadow ">
            <div class="col-12 col-sm-7 col-lg-7 col-xl-8 login-bg">
                <h4 class="mb-3">{{ __('Register') }}</h4>
                <form method="POST" action="{{ route('mobile.register.store') }}">
                    @csrf

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">+86</div>
                            </div>
                            <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" placeholder="{{ __('Mobile') }}" required autocomplete="mobile">
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" class="form-control @error('verify_code') is-invalid @enderror" name="verify_code" placeholder="{{ __('VerifyCode') }}" >
                        <div class="input-group-append">
                            <send-phone-verify-code phone_id="phone"></send-phone-verify-code>
                        </div>
                        @error('verify_code')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required autocomplete="new-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms"
                                   id="terms" {{ old('terms') ? 'checked' : '' }}>

                            <label class="form-check-label" for="terms">
                                同意 <a href="{{ url('terms') }}" target="_blank">服务条款</a> 和 <a href="{{ url('privacy') }}" target="_blank">隐私政策</a>
                            </label>
                            @error('terms')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="login-btn">
                            {{ __('Register') }}
                        </button>

                        @if (Route::has('login'))
                            <a class="btn btn-link" href="{{ route('login') }}">
                                {{ __('Already have an account?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="col-12 col-sm-5 col-lg-5 col-xl-4 bg-three">
                @include('auth._social')
            </div>
        </div>
    </div>

@endsection

@push('footer')
    <!-- 腾讯防水墙 -->
    <script src="https://ssl.captcha.qq.com/TCaptcha.js"></script>
@endpush
