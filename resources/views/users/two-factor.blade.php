<x-app title='Two Factor Authentication'>
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
                            @if($credentials->avatar)
                                <img src="{{ asset('storage/' . $credentials->avatar) }}">
                            @else
                                {{ $credentials->name[0] }}
                            @endif
                        </div>
                        <h3>hi, {{ $credentials->name }}</h3>
                        <h4>enter the verification code sent to <br> <span>{{ $credentials->masked }}</span></h4>
                        <div class="formParent">
                            <x-form id="form" method="post" action="{{ route('user.two-factor-auth') }}">
                                <div class="formItem">
                                    <label for="code">Verification Code</label>
                                    <input type="number" id="code" autocomplete="off" name="code">
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