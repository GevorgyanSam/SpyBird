<x-mail::layout>
    <h3>Dear {{ $Data['name'] }},</h3>
    <h4>Greetings from SpyBird!</h4>
    <h4>We wanted to inform you that we've detected a new login to your account. For your reference, here are the login details:</h4>
    <div>
        <div>
            <h4>Device</h4>
            <h3>{{ $Data['device'] }}</h3>
        </div>
        <div>
            <h4>Location</h4>
            <h3>{{ $Data['location'] }}</h3>
        </div>
        <div>
            <h4>Date and Time</h4>
            <h3>{{ $Data['date'] }}</h3>
        </div>
    </div>
    <h4>If this login was made by you, there is no action required, and you can disregard this message. However, if this login is not familiar to you, we strongly recommend changing your account password immediately to secure your account.</h4>
    <h4>Your account's security is our top priority, and we are here to assist you. If you have any questions or need support regarding this login or your account's safety, please feel free to contact our support team or simply reply to this email.</h4>
    <h4>Thanks for using SpyBird for conversations.</h4>
    <h4>Best regards,</h4>
    <h4>The SpyBird Team</h4>
</x-mail::layout>