@extends('layouts.app')

@section('title', __('Register'))

@section('content')
    <div class="login-box">
        <div class="container">
            <div class="row mx-0 justify-content-md-center">
                <div class="col-12 col-lg-9 text-center bg-white login_inside_box">
                    <h4 class="mt-0 mb-3 pb-0 pb-lg-4 pt-0 pt-lg-4 title">
                        <span>{{ __('Register') }}</span>
                    </h4>
                    <div class="login-form">
                        <form id="register_form" class="form" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-group input-group mb-4">
                                <input id="username" type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" placeholder="{{ __('Username') }}" required autocomplete="username" autofocus>
                                @error('username')
                                <span class="invalid-feedback text-left" role="alert">
                                        <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group input-group mb-4">
                                <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" required autocomplete="email">
                                @error('email')
                                <span class="invalid-feedback text-left" role="alert">
                                        <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group input-group mb-4">
                                <input id="password" type="password"
                                       class="form-control form-control-lg @error('password') is-invalid @enderror"
                                       name="password" placeholder="{{ __('Password') }}" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback text-left" role="alert">
                                        <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group input-group mb-4">
                                <input id="password-confirm" type="password" class="form-control form-control-lg" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required autocomplete="new-password">
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="terms" value="1">
                                <input type="hidden" name="ticket" id="ticket" value="">
                                <input type="hidden" name="randstr" id="randstr" value="">
                                <button
                                    @if (config('app.env') != 'testing' && settings('user.enable_login_ticket')) type="button"
                                    @else type="submit" @endif class="btn btn-block text-white btn-lg btn-login" id="TencentCaptcha"
                                    data-appid="{{settings('system.captcha_aid')}}" data-cbfn="captchaCallback">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('login') }}" class="btn btn-block register_btn btn-lg text-muted bg-white">
                            {{ __('Already have an account?') }}
                        </a>
                    </div>

                    <div class="mt-4 text-center text-muted">
                        注册即表示你同意网站的
                        <a class="text-primary" href="{{ url('terms') }}" target="_blank">{{ __('Terms') }}</a> 和 <a
                            class="text-primary" href="{{ url('privacy') }}" target="_blank">{{ __('Privacy') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    @if (config('app.env') != 'testing' && settings('user.enable_login_ticket'))
        <!-- 腾讯防水墙 -->
        <script src="https://ssl.captcha.qq.com/TCaptcha.js"></script>
        <script>
            window.captchaCallback = function (res) {
                if (res.ret === 0) {
                    document.getElementById("ticket").value = res.ticket;
                    document.getElementById("randstr").value = res.randstr;
                    document.getElementById('register_form').submit();
                }
            }
        </script>
    @endif
@endpush
