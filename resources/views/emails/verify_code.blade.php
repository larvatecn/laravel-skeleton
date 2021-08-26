@component('mail::message')

{{__("Your verification code is: :verifyCode.",['verifyCode'=>$verifyCode])}}

{{__("The verification code is valid within 10 minutes. In order to protect the security of your account, please do not disclose the verification code information to others.")}}

@endcomponent
