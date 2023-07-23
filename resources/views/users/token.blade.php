<x-app title='Password Reset'>
    <x-slot:styles>
        <link rel="stylesheet" href="{{ asset('css/users/style.css') }}">
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
                        <div class="avatar">
                            <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/1.jpg">
                            W
                        </div>
                        <h3>william pearson</h3>
                        <h4>create new password for your account</h4>
                        <div class="formParent">
                            <x-form method="post">
                                <div class="formItem">
                                    <label for="password">New Password</label>
                                    <input type="password" id="password" autocomplete="off" autofocus>
                                    <i class="fa-solid fa-eye-slash" id="eye"></i>
                                </div>
                                <div class="formItem">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" id="password_confirmation" autocomplete="off">
                                    <i class="fa-solid fa-eye-slash" id="eye_confirmation"></i>
                                </div>
                                <div class="formItem">
                                    <button type="submit">create password</button>
                                </div>
                            </x-form>
                        </div>
                    </div>
                    <div class="linkParent">
                        <p>skip new password ? <a href="#">continue</a></p>
                    </div>
                </div>
            </div>
        </main>
    </x-slot>
    <x-slot:scripts>
        <script src="{{ asset('js/users/token.js') }}"></script>
    </x-slot>
</x-app>