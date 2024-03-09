<x-app title='Password Reset'>
    <x-slot:styles>
        <link rel="stylesheet" href="{{ asset('css/users/style.css') }}">
    </x-slot>
    <x-slot:body>
        <x-notification />
        <x-loading />
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
                        <h3>password reset</h3>
                        <h4>enter your email to reset password</h4>
                        <div class="formParent">
                            <x-form id="form" method="post" action="{{ route('user.reset-auth') }}">
                                <div class="formItem">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" autocomplete="off" name="email">
                                </div>
                                <div class="formItem">
                                    <button type="submit">send reset link</button>
                                </div>
                            </x-form>
                        </div>
                    </div>
                    <div class="linkParent">
                        <p>Return to login page ? <a href="{{ route('user.login') }}">continue</a></p>
                    </div>
                </div>
            </div>
        </main>
    </x-slot>
    <x-slot:scripts>
        <script src="{{ asset('js/users/password-reset.js') }}"></script>
    </x-slot>
</x-app>