<x-app title='Two Factor Authentication'>
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
                        <h3>hi, william pearson</h3>
                        <h4>enter the verification code sent to <br> s*****5@gmail.com</h4>
                        <div class="formParent">
                            <x-form method="post">
                                <div class="formItem">
                                    <label for="code">Verification Code</label>
                                    <input type="number" id="code" autocomplete="off" autofocus>
                                </div>
                                <div class="formItem">
                                    <button type="submit">verify</button>
                                </div>
                            </x-form>
                        </div>
                    </div>
                    <div class="linkParent">
                        <p>lost your email ? <a href="{{ route('user.lost-email') }}">use backup codes</a></p>
                    </div>
                </div>
            </div>
        </main>
    </x-slot>
    <x-slot:scripts>
        <script src="{{ asset('js/users/two-factor.js') }}"></script>
    </x-slot>
</x-app>