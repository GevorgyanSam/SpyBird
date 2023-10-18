<x-app title='Password Reset'>
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
                        <div class="avatar">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}">
                            @else
                                {{ $user->name[0] }}
                            @endif
                        </div>
                        <h3>{{ $user->name }}</h3>
                        <h4>create new password for your account</h4>
                        <div class="formParent">
                            <x-form id="form" method="post" action="{{ route('user.token-auth', ['token' => $token]) }}">
                                <input type="hidden" value="{{ $token }}" name="token">
                                <div class="formItem">
                                    <label for="password">New Password</label>
                                    <input type="password" id="password" autocomplete="off" name="password">
                                    <i class="fa-solid fa-eye-slash" id="eye"></i>
                                </div>
                                <div class="formItem">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" id="password_confirmation" autocomplete="off" name="password_confirmation">
                                    <i class="fa-solid fa-eye-slash" id="eye_confirmation"></i>
                                </div>
                                <div class="formItem">
                                    <button type="submit">create password</button>
                                </div>
                            </x-form>
                        </div>
                    </div>
                    <div class="linkParent">
                        <p>not your account ? <a href="{{ route('user.reset') }}">get back</a></p>
                    </div>
                </div>
            </div>
        </main>
    </x-slot>
    <x-slot:scripts>
        <script src="{{ asset('js/users/token.js') }}"></script>
    </x-slot>
</x-app>