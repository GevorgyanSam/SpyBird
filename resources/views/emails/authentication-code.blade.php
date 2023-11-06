<x-mail::layout>
    <h3>Dear {{ $Data->name }},</h3>
    <h4>Greetings from SpyBird!</h4>
    <h4>Your verification code for login is:</h4>
    <h4>
        <h3>{{ $Data->code }}</h3>
    </h4>
    <h4>This code will expire in 20 minutes.</h4>
    <h4>If you did not request this verification code, someone may know your password. In that case, we strongly recommend that you change your password immediately to secure your account.</h4>
    <h4>Thanks for using SpyBird for conversations.</h4>
    <h4>Best regards,</h4>
    <h4>The SpyBird Team</h4>
</x-mail::layout>