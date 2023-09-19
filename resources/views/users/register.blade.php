<x-app title='Register'>
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
                        <h3>sign up</h3>
                        <h4>follow the easy steps</h4>
                        <div class="formParent">
                            <x-form method="post" action="{{ route('user.register-auth') }}">
                                <div class="formItem">
                                    @error('name')
                                    <label for="name" class="error">{{ $message }}</label>
                                    @else
                                    <label for="name">Name</label>
                                    @enderror
                                    <input type="text" id="name" autocomplete="off" name="name" value="{{ old('name') }}">
                                </div>
                                <div class="formItem">
                                    @error('email')
                                    <label for="email" class="error">{{ $message }}</label>
                                    @else
                                    <label for="email">Email</label>
                                    @enderror
                                    <input type="email" id="email" autocomplete="off" name="email" value="{{ old('email') }}">
                                </div>
                                <div class="formItem">
                                    @error('password')
                                    <label for="password" class="error">{{ $message }}</label>
                                    @else
                                    <label for="password">Password</label>
                                    @enderror
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