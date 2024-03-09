<x-app title='Lock Screen'>
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
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}">
                            @else
                                {{ auth()->user()->name[0] }}
                            @endif
                        </div>
                        <h3>{{ auth()->user()->name }}</h3>
                        <h4>enter your password to unlock the screen</h4>
                        <div class="formParent">
                            <x-form id="form" method="post" action="{{ route('user.lockscreen-auth') }}">
                                <div class="formItem">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" autocomplete="off" name="password">
                                    <i class="fa-solid fa-eye-slash" id="eye"></i>
                                </div>
                                <div class="formItem">
                                    <button type="submit">unlock</button>
                                </div>
                            </x-form>
                        </div>
                    </div>
                    <div class="linkParent">
                        <p>forgot your password ? <a id="logout">logout</a></p>
                    </div>
                </div>
            </div>
        </main>
    </x-slot>
    <x-slot:scripts>
        <script src="{{ asset('js/users/lockscreen.js') }}"></script>
    </x-slot>
</x-app>