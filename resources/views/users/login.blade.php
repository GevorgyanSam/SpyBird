<x-app title='Login'>
    <x-slot:styles>
        <link rel="stylesheet" href="{{ asset('css/users/style.css') }}">
    </x-slot>
    <x-slot:body>
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
                        <h3>sign in</h3>
                        <h4>login to your account</h4>
                        <div class="formParent">
                            <x-form id="form" method="post" action="{{ route('user.login-auth') }}">
                                <div class="formItem">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" autocomplete="off" name="email">
                                </div>
                                <div class="formItem">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" autocomplete="off" name="password">
                                    <i class="fa-solid fa-eye-slash" id="eye"></i>
                                </div>
                                <div class="formItem">
                                    <button type="submit">sign in</button>
                                </div>
                            </x-form>
                        </div>
                    </div>
                    <div class="linkParent">
                        <p>don't have an account yet ? <a href="{{ route('user.register') }}">sign up</a></p>
                        <a href="{{ route('user.reset') }}">forgot password ?</a>
                    </div>
                </div>
            </div>
        </main>
    </x-slot>
    <x-slot:scripts>
        <script src="{{ asset('js/users/login.js') }}"></script>
    </x-slot>
</x-app>