<x-mail::layout>
    <h3>Dear {{ $Data->name }},</h3>
    <h4>Greetings from SpyBird!</h4>
    <h4>We've received your request to delete your SpyBird account. We want to ensure that you're taking this action intentionally.</h4>
    <h4>To proceed with the account deletion, please click on the button below:</h4>
    <div class="formParent">
        <div class="formItem">
            <a href="{{ route('account-termination', ['token' => $Data->token]) }}">
                <button>Delete Account</button>
            </a>
        </div>
    </div>
    <h4>If you didn't request to delete your account, please ignore this email, and your SpyBird account will remain safe and active.</h4>
    <h4>Please be aware that the account deletion link will be valid for the next 1 hour. After that, it will expire for security reasons.</h4>
    <h4>If you have any questions or need assistance, feel free to contact our support team.</h4>
    <h4>We appreciate you being a part of the SpyBird community, and we respect your choices.</h4>
    <h4>Best regards,</h4>
    <h4>The SpyBird Team</h4>
</x-mail::layout>