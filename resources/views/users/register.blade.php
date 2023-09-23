<x-app title='Register'>
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
                        <h3>sign up</h3>
                        <h4>follow the easy steps</h4>
                        <div class="formParent">
                            <x-form id="form" method="post" action="{{ route('user.register-auth') }}">
                                <div class="formItem">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" autocomplete="off" name="name">
                                </div>
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
                                    <button type="submit">create account</button>
                                </div>
                                <div class="policy">creating an account implies acceptance of our <a href="{{ route('privacy.terms') }}">terms</a> and <a href="{{ route('privacy.policy') }}">privacy policy</a>.</div>
                            </x-form>
                        </div>
                    </div>
                    <div class="linkParent">
                        <p>already have an account ? <a href="{{ route('user.login') }}">sign in</a></p>
                    </div>
                </div>
            </div>
        </main>
    </x-slot>
    <x-slot:scripts>
        <script src="{{ asset('js/users/register.js') }}"></script>
    </x-slot>
</x-app>