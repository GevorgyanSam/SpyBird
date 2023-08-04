<x-app title='Chat'>
    <x-slot:styles>
        <link rel="stylesheet" href="{{ asset('css/pages/style.css') }}">
    </x-slot>
    <x-slot:body>

        <!-- ----- ----- ---------- ----- ----- -->
        <!-- ----- ----- Navigation ----- ----- -->
        <!-- ----- ----- ---------- ----- ----- -->

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
                        <li class="active" id="chat" title="Chat">
                            <i class="fa-solid fa-comments">
                                <div class="count">4</div>
                            </i>
                        </li>
                        <li id="search" title="Search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </li>
                        <li id="friends" title="Friends">
                            <i class="fa-solid fa-user-group"></i>
                        </li>
                        <li id="notifications" title="Notifications">
                            <i class="fa-solid fa-bell">
                                <div class="count">9+</div>
                            </i>
                        </li>
                    </ul>
                </div>
                <div class="item">
                    <ul>
                        <li class="mode" title="Color Mode">
                            <i class="fa-solid fa-moon"></i>
                        </li>
                        <li id="settings" title="Settings">
                            <i class="fa-solid fa-gear"></i>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- ----- ----- ----- ----- ----- -->
        <!-- ----- ----- Aside ----- ----- -->
        <!-- ----- ----- ----- ----- ----- -->

        <aside>
            <div class="asideParent">

            </div>
        </aside>

        <!-- ----- ----- ---- ----- ----- -->
        <!-- ----- ----- Main ----- ----- -->
        <!-- ----- ----- ---- ----- ----- -->

        <main>
            <div class="mainParent">
                <div class="content">
                    <div class="item">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <div class="item">
                        <h3>Pick a person from left menu, <br> and start your conversation.</h3>
                    </div>
                </div>
            </div>
        </main>
    </x-slot>
    <x-slot:scripts>
        <script src="{{ asset('js/pages/script.js') }}"></script>
    </x-slot>
</x-app>