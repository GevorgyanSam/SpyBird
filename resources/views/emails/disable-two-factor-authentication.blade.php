<x-mail::layout>
    <h3>Dear {{ $Data['name'] }},</h3>
    <h4>Greetings from SpyBird!</h4>
    <h4>We have received your request to disable two-factor authentication (2FA) for your SpyBird account. We understand that security settings may change and we are here to help you through this process.</h4>
    <h4>To continue disabling 2FA, click the button below:</h4>
    <div class="formParent">
        <div class="formItem">
            <a href="{{ route('disable-two-factor', ['token' => $Data['token']]) }}">
                <button>Disable 2FA</button>
            </a>
        </div>
    </div>
    <h4>If this request was not initiated by you, simply do nothing and your account will remain safe.</h4>
    <h4>Please note that the 2FA deactivation link will be valid for the next 1 hour. After this, it will expire for security reasons.</h4>
    <h4>If you have any questions or need assistance with the deactivation process, please contact our support team. Your safety and peace of mind are our top priorities.</h4>
    <h4>We appreciate your trust in SpyBird and are ready to help you in any way we can.</h4>
    <h4>Best regards,</h4>
    <h4>The SpyBird Team</h4>
</x-mail::layout>