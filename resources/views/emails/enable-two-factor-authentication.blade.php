<x-mail::layout>
    <h3>Dear {{ $Data['name'] }},</h3>
    <h4>Greetings from SpyBird!</h4>
    <h4>We've received your request to enable Two-Factor Authentication (2FA) for your SpyBird account. Security is important to us, and we want to ensure that you're taking this step intentionally.</h4>
    <h4>To proceed with enabling 2FA, please click on the button below:</h4>
    <div class="formParent">
        <div class="formItem">
            <a href="{{ route('enable-two-factor', ['token' => $Data['token']]) }}">
                <button>Enable 2FA</button>
            </a>
        </div>
    </div>
    <h4>If you didn't request to enable 2FA, please ignore this email, and your account will remain as it is. Your SpyBird account's security is our priority.</h4>
    <h4>Please be aware that the 2FA activation link will be valid for the next 1 hour. After that, it will expire for security reasons.</h4>
    <h4>If you have any questions or need assistance with 2FA, feel free to contact our support team.</h4>
    <h4>We appreciate you taking steps to enhance the security of your SpyBird account. Thank you for being a part of our community.</h4>
    <h4>Best regards,</h4>
    <h4>The SpyBird Team</h4>
</x-mail::layout>