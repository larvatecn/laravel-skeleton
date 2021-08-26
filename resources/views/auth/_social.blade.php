<div class="login-sns mt-3">
    <div class="text-muted my-0 position-relative">
        <hr class="position-absolute w-100">
        <div class="d-inline-block bg-white px-3 position-relative" style="height: 28px; line-height: 28px;">
            使用以下账号登录
        </div>
    </div>

    <a href="{{ url('/auth/social/weibo') }}" class="my-3" data-no-instant>
        <img src="{{asset('img/social/weibo.png')}}" alt="wechat" data-toggle="tooltip" data-placement="top" title="" data-original-title="Use Weibo account to log in.">
    </a>
    <a href="{{ url('/auth/social/wechat') }}" class="my-3" data-no-instant>
        <img src="{{asset('img/social/wechat.png')}}" alt="wechat" data-toggle="tooltip" data-placement="top" title="" data-original-title="Use Wechat and scan the QR code to login in.">
    </a>
    <a href="{{ url('/auth/social/qq') }}" class="my-3" data-no-instant>
        <img src="{{asset('img/social/qq.png')}}" alt="github" data-toggle="tooltip" data-placement="top" title="" data-original-title="Use Github account to log in">
    </a>
    <a href="{{ url('/auth/social/alipay') }}" class="my-3" data-no-instant>
        <img src="{{asset('img/social/alipay.png')}}" alt="wechat" data-toggle="tooltip" data-placement="top" title="" data-original-title="Use Alipay account to log in.">
    </a>
    <a href="{{ url('/auth/social/baidu') }}" class="my-3" data-no-instant>
        <img src="{{asset('img/social/baidu.png')}}" alt="wechat" data-toggle="tooltip" data-placement="top" title="" data-original-title="Use Baidu account to log in.">
    </a>
    <a href="{{ url('/auth/social/github') }}" class="my-3" data-no-instant>
        <img src="{{asset('img/social/github.png')}}" alt="github" data-toggle="tooltip" data-placement="top" title="" data-original-title="Use Github account to log in.">
    </a>
</div>
