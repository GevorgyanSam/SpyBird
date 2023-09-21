<x-mail::layout>
    <h3>Dear {{ $Data['name'] }},</h3>
    <h4>Welcome to SpyBird!</h4>
    <h4>We're thrilled to have you as part of our chat community. To get started and make the most of your SpyBird experience, we need to verify your email address.</h4>
    <h4>Just one more step! Click the button below to verify your email:</h4>
    <div class="formParent">
        <div class="formItem">
            <a href="{{ $Data['token'] }}">
                <button>Verify Email</button>
            </a>
        </div>
    </div>
    <h4>If you didn't sign up for SpyBird or this email was sent in error, no worries – simply ignore it, and your account will remain safe.</h4>
    <h4>Thank you for choosing SpyBird for your real-time connections. We can't wait to see you online!</h4>
    <h4>Best regards,</h4>
    <h4>The SpyBird Team</h4>
</x-mail::layout>