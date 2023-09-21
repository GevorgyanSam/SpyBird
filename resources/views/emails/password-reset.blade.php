<x-mail::layout>
    <h3>Dear {{ $Data['name'] }},</h3>
    <h4>Greetings from SpyBird!</h4>
    <h4>Your seamless chat experience matters to us, and we're here to make sure it stays secure. If you requested a password reset, no worries! We've got you covered with a unique link to regain access to your SpyBird account.</h4>
    <h4>To reset your password, simply click on the button below:</h4>
    <div class="formParent">
        <div class="formItem">
            <a href="#{{ $Data['token'] }}">
                <button>Reset Password</button>
            </a>
        </div>
    </div>
    <h4>If you didn't ask for a reset, ignore this email, and your account stays safe.</h4>
    <h4>Thanks for being a part of SpyBird's real-time connections!</h4>
    <h4>Best regards,</h4>
    <h4>The SpyBird Team</h4>
</x-mail::layout>