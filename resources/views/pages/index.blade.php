<x-app title='Chat'>
    <x-slot:styles>
        <link rel="stylesheet" href="{{ asset('css/pages/style.css') }}">
    </x-slot>
    <x-slot:body>
        <nav>
            <div class="navParent">
                <div class="item">
                    <div class="logo">
                        <a href="{{ route('index') }}">
                            <x-logo color="rgb(39, 135, 245)"/>
                        </a>
                    </div>
                </div>
                <div class="item">
                    <ul>
                        <li class="active" id="chat">
                            <i class="fa-solid fa-comments"></i>
                        </li>
                        <li id="search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </li>
                        <li id="friends">
                            <i class="fa-solid fa-user-group"></i>
                        </li>
                        <li id="notifications">
                            <i class="fa-solid fa-bell"></i>
                        </li>
                    </ul>
                </div>
                <div class="item">
                    <ul>
                        <li class="mode">
                            <i class="fa-solid fa-moon"></i>
                        </li>
                        <li id="settings">
                            <i class="fa-solid fa-gear"></i>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </x-slot>
    <x-slot:scripts>
        <script src="{{ asset('js/pages/script.js') }}"></script>
    </x-slot>
</x-app>