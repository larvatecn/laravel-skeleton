@extends('layouts.app')

@section('title', __('Bind Account'))

@section('content')
    <div class="login-box">
        <div class="container">
            <div class="row mx-0 justify-content-md-center">
                <div class="col-12 col-lg-9 text-center bg-white login_inside_box">
                    <h4 class="mt-0 mb-3 pb-0 pb-lg-4 pt-0 pt-lg-4 title">
                        <span>{{ __('Bind Account') }}</span>
                    </h4>
                    <div class="login-form">
                        <form id="login_form" class="form" method="POST" action="{{ route('login') }}" aria-label="{{ __('Binging') }}">
                            @csrf
                            <div class="form-group input-group mb-4">
                                <input id="account" type="text"
                                       class="form-control form-control-lg {{ $errors->has('account') ? ' is-invalid' : '' }}"
                                       name="account" value="{{ old('account') }}"
                                       placeholder="{{ __('PhoneOrEmail') }}" required autofocus>
                                @if ($errors->has('account'))
                                    <span class="invalid-feedback text-left" role="alert">
                                        <strong>{{ $errors->first('account') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group input-group mb-4">
                                <input id="password" type="password"
                                       class="form-control form-control-lg {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password" placeholder="{{ __('Password') }}" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback text-left" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="ticket" id="ticket" value="">
                                <input type="hidden" name="randstr" id="randstr" value="">
                                <button @if (config('app.env') != 'testing' && settings('user.enable_login_ticket')) type="button"
                                        @else type="submit" @endif
                                        id="TencentCaptcha" data-appid="{{settings('system.captcha_aid')}}"
                                        data-cbfn="captchaCallback"
                                        class="btn btn-block text-white btn-lg btn-login">{{ __('Bind Account') }}</button>
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

                    <div class="mt-3">
                        <a href="{{ route('register') }}" class="btn btn-block register_btn btn-lg text-muted bg-white">
                            没有账号？立即免费注册
                        </a>
                    </div>

                    <div class="mt-4 text-center text-muted">
                        绑定即表示你同意网站的
                        <a class="text-primary" href="{{ url('terms') }}" target="_blank">{{ __('Terms') }}</a> 和 <a
                            class="text-primary" href="{{ url('privacy') }}" target="_blank">{{ __('Privacy') }}</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if (config('app.env') != 'testing' && settings('user.enable_login_ticket'))
        <script src="https://ssl.captcha.qq.com/TCaptcha.js"></script>
        <script>
            window.captchaCallback = function (res) {
                if (res.ret === 0) {
                    document.getElementById("ticket").value = res.ticket;
                    document.getElementById("randstr").value = res.randstr;
                    document.getElementById('login_form').submit();
                }
            }
        </script>
    @endif
@endpush
