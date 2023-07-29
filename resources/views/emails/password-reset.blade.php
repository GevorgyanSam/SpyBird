<x-app title='Password Reset'>
    <x-slot:styles>
        <link rel="stylesheet" href="{{ asset('css/emails/email.css') }}">
    </x-slot>
    <x-slot:body>
        <nav>
            <div class="navParent">
                <div class="logo">
                    <a href="{{ route('user.login') }}">
                        <x-logo color="rgb(39, 135, 245)"/>
                    </a>
                </div>
            </div>
        </nav>
        <main>
            <div class="container">
                <div class="item">
                    <div class="box">
                        <h3>Dear William,</h3>
                        <h4>Greetings from SpyBird!</h4>
                        <h4>Your seamless chat experience matters to us, and we're here to make sure it stays secure. If you requested a password reset, no worries! We've got you covered with a unique link to regain access to your SpyBird account.</h4>
                        <h4>To reset your password, simply click on the button below:</h4>
                        <div class="formParent">
                            <div class="formItem">
                                <a href="#">
                                    <button>Reset Password</button>
                                </a>
                            </div>
                        </div>
                        <h4>If you didn't ask for a reset, ignore this email, and your account stays safe.</h4>
                        <h4>Thanks for being a part of SpyBird's real-time connections!</h4>
                        <h4>Best regards,</h4>
                        <h4>The SpyBird Team</h4>
                    </div>
                </div>
            </div>
        </main>
    </x-slot>
</x-app>