<x-app title='Lost Email Authentication'>
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
                            L
                        </div>
                        <h3>switch to backup code</h3>
                        <h4>enter your backup code below</h4>
                        <div class="formParent">
                            <x-form method="post">
                                <div class="formItem">
                                    <label for="code">backup code</label>
                                    <input type="number" id="code" autocomplete="off">
                                </div>
                                <div class="formItem">
                                    <button type="submit">verify</button>
                                </div>
                            </x-form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </x-slot>
    <x-slot:scripts>
        <script src="{{ asset('js/users/lost-email.js') }}"></script>
    </x-slot>
</x-app>