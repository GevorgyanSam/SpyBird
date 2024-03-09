<x-mail::layout>
    <h3>Dear {{ $Data->name }},</h3>
    <h4>Greetings from SpyBird!</h4>
    <h4>Your request to enable Two-Factor Authentication (2FA) for your SpyBird account has been successfully processed.</h4>
    <h4>As a security measure, we have generated 6 backup codes for you. Please keep these codes in a safe place as they can be used to access your account in case you lose access to your email.</h4>
    @foreach ($Data->codes as $code)
    <h4>
        <h3>{{ $code }}</h3>
    </h4>
    @endforeach
    <h4>If you have any questions or need assistance with 2FA or account recovery, feel free to contact our support team.</h4>
    <h4>We appreciate you taking steps to enhance the security of your SpyBird account. Thank you for being a part of our community.</h4>
    <h4>Best regards,</h4>
    <h4>The SpyBird Team</h4>
</x-mail::layout>