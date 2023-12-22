<x-app title='Room'>
    <x-slot:styles>
        <link rel="stylesheet" href="{{ asset('css/pages/room.css') }}">
    </x-slot>
    <x-slot:body>
        <x-notification />
        <x-loading />

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
                        <li data-name="search" class="search" title="Search" data-content="searchParent">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </li>
                        <li data-name="chat" class="chat" title="Chat" data-content="chatParent">
                            <i class="fa-solid fa-comments"></i>
                        </li>
                        <li data-name="friends" class="friends" title="Friends" data-content="friendsParent">
                            <i class="fa-solid fa-user-group"></i>
                        </li>
                        <li data-name="notifications" class="notifications" title="Notifications" data-content="notificationsParent">
                            <i class="fa-solid fa-bell"></i>
                        </li>
                    </ul>
                </div>
                <div class="item">
                    <ul>
                        <li class="mode" title="Color Mode">
                            <i class="fa-solid fa-moon"></i>
                        </li>
                        <li data-name="settings" class="settings" title="Settings" data-content="settingsParent">
                            <i class="fa-solid fa-gear"></i>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mobileNavParent">
                <div class="item">
                    <ul>
                        <li data-name="search" class="search" title="Search" data-content="searchParent">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </li>
                        <li data-name="friends" class="friends" title="Friends" data-content="friendsParent">
                            <i class="fa-solid fa-user-group"></i>
                        </li>
                        <li data-name="chat" class="chat" title="Chat" data-content="chatParent">
                            <i class="fa-solid fa-comments"></i>
                        </li>
                        <li data-name="notifications" class="notifications" title="Notifications" data-content="notificationsParent">
                            <i class="fa-solid fa-bell"></i>
                        </li>
                        <li data-name="settings" class="settings" title="Settings" data-content="settingsParent">
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

        <!-- ----- ----- ------ ----- ----- -->
        <!-- ----- ----- Search ----- ----- -->
        <!-- ----- ----- ------ ----- ----- -->

                <div class="searchParent">
                    <h3>Search</h3>
                    <x-form id="searchContacts" method="post" action="{{ route('search-contacts') }}">
                        <div class="formItem">
                            <input type="text" placeholder="Search People" name="search" autocomplete="off">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </x-form>
                    <div class="switchParent">
                        <div data-name="familiar" class="familiar active">suggested contacts</div>
                        <div data-name="nearby" class="nearby">people nearby</div>
                    </div>
                    <div class="personParent"></div>
                </div>

        <!-- ----- ----- ---- ----- ----- -->
        <!-- ----- ----- Chat ----- ----- -->
        <!-- ----- ----- ---- ----- ----- -->

                <div class="chatParent">
                    <h3>Chat</h3>
                    <div>
                        <x-form method="post">
                            <div class="formItem">
                                <input type="text" placeholder="Search Messages">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                        </x-form>
                        <div class="contentParent"></div>
                    </div>
                    <div class="emptyParent">
                        <div class="content">
                            <div class="item">
                                <i class="fa-solid fa-comments"></i>
                            </div>
                            <div class="item">
                                <h3>Ready to chat? <br> Select someone from your friends list and start a conversation!</h3>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- ----- ----- ------- ----- ----- -->
        <!-- ----- ----- Friends ----- ----- -->
        <!-- ----- ----- ------- ----- ----- -->

                <div class="friendsParent">
                    <h3>Friends</h3>
                    <div>
                        <x-form id="searchFriends" method="post" action="{{ route('search-friends') }}">
                            <div class="formItem">
                                <input type="text" placeholder="Search People" name="search" autocomplete="off">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                        </x-form>
                        <div class="personParent"></div>
                    </div>
                    <div class="emptyParent">
                        <div class="content">
                            <div class="item">
                                <i class="fa-solid fa-user-group"></i>
                            </div>
                            <div class="item">
                                <h3>No friends yet? <br> You can start by looking for people to hang out with.</h3>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- ----- ----- ------------- ----- ----- -->
        <!-- ----- ----- Notifications ----- ----- -->
        <!-- ----- ----- ------------- ----- ----- -->

                <div class="notificationsParent">
                    <h3>Notifications</h3>
                    <div>
                        <div class="recentParent">
                            <div class="recent">recent</div>
                            <x-form id="clearNotifications" method="post" action="{{ route('clear-notifications') }}">
                                <button type="submit">clear all</button>
                            </x-form>
                        </div>
                        <div class="reportParent"></div>
                    </div>
                    <div class="emptyParent">
                        <div class="content">
                            <div class="item">
                                <i class="fa-solid fa-bell"></i>
                            </div>
                            <div class="item">
                                <h3>Keep an eye on your notifications to stay in the loop and never miss out on important updates and messages.</h3>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- ----- ----- -------- ----- ----- -->
        <!-- ----- ----- Settings ----- ----- -->
        <!-- ----- ----- -------- ----- ----- -->

                <div class="settingsParent">
                    <h3>Settings</h3>
                    <div>
                        <div class="profile">
                            <div>
                                <div class="avatar">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}">
                                    @else
                                        {{ auth()->user()->name[0] }}
                                    @endif
                                </div>
                            </div>
                            <div class="profileInfo">
                                <h4>{{ auth()->user()->name }}</h4>
                                <div class="email">{{ auth()->user()->email }}</div>
                            </div>
                            <x-form class="logout-form" method="post" action="{{ route('logout') }}">
                                <button class="logout" title="Logout">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                </button>
                            </x-form>
                        </div>
                        <div class="container">
                            <h2>features</h2>
                            <div class="box">
                                <div class="static spy">
                                    <label for="spy">
                                        <div class="visible">
                                            <div>
                                                <h3>spy mode</h3>
                                                <h4>switch to spy mode</h4>
                                            </div>
                                            <div>
                                                <input type="checkbox" class="checkbox" id="spy">
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="static invisible">
                                    <label for="invisible">
                                        <div class="visible">
                                            <div>
                                                <h3>invisible mode</h3>
                                                <h4>switch to invisible mode</h4>
                                            </div>
                                            <div>
                                                <input type="checkbox" class="checkbox" id="invisible" {{ auth()->user()->invisible ? "checked" : null }}>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <h2>account</h2>
                            <div class="box">
                                <div class="accordion">
                                    <div class="visible">
                                        <div>
                                            <h3>profile settings</h3>
                                            <h4>change your profile settings</h4>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-angle-right"></i>
                                        </div>
                                    </div>
                                    <div class="hidden">
                                        <x-form id="updateProfile" method="post" action="{{ route('update-profile') }}" enctype="multipart/form-data">
                                            <div class="formItem">
                                                <label for="avatar">avatar</label>
                                                <input type="file" id="avatar" name="avatar" accept=".jpg, .jpeg, .png, .webp">
                                            </div>
                                            <div class="formItem">
                                                <label for="name">name</label>
                                                <input type="text" id="name" name="name" autocomplete="off">
                                            </div>
                                            <div class="formItem">
                                                <button type="submit">save</button>
                                            </div>
                                        </x-form>
                                    </div>
                                </div>
                                <div class="static activity">
                                    <label for="activity">
                                        <div class="visible">
                                            <div>
                                                <h3>activity status</h3>
                                                <h4>toggle activity status</h4>
                                            </div>
                                            <div>
                                                <input type="checkbox" class="checkbox" id="activity" {{ auth()->user()->activity ? "checked" : null }}>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="static theme">
                                    <div class="visible">
                                        <div>
                                            <h3>appearance</h3>
                                            <h4>choose light or dark theme</h4>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-moon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <h2>view</h2>
                            <div class="box">
                                <div class="static fullScreen">
                                    <div class="visible">
                                        <div>
                                            <h3>full screen</h3>
                                            <h4>switch to full screen mode</h4>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-expand"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <h2>security</h2>
                            <div class="box">
                                <div class="static reset">
                                    <x-form id="passwordReset" method="post" action="{{ route('password-reset') }}">
                                        <div class="visible">
                                            <div>
                                                <h3>password</h3>
                                                <h4>password change request</h4>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-key"></i>
                                            </div>
                                        </div>
                                    </x-form>
                                </div>
                                <div class="static verification">
                                    <label for="verification">
                                        <div class="visible">
                                            <div>
                                                <h3>two-factor authentication</h3>
                                                <h4>switch two-factor authentication</h4>
                                            </div>
                                            <div>
                                                <input type="checkbox" class="checkbox" id="verification" {{ auth()->user()->two_factor_authentication ? "checked" : null }}>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <h2>devices</h2>
                            <div class="box">
                                @foreach ($devices as $device)
                                <div class="static devices">
                                    <div class="visible">
                                        <div>
                                            <h3>{{ $device['platform'] }} - {{ $device['location'] }}</h3>
                                            <h4>{{ $device['date'] }}</h4>
                                        </div>
                                        <div>
                                            @if ($device['status'])
                                            <div class="active"></div>
                                            @else
                                            <i class="fa-solid fa-trash"></i>
                                            <input type="hidden" name="device-link" value="{{ $device['link'] }}">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="container">
                            <h2>other</h2>
                            <div class="box">
                                <div class="static danger">
                                    <x-form class="logout-form" method="post" action="{{ route('logout') }}">
                                        <div class="visible">
                                            <div>
                                                <h3>log out</h3>
                                                <h4>signing out of your account</h4>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                            </div>
                                        </div>
                                    </x-form>
                                </div>
                                @if (auth()->user()->two_factor_authentication)
                                <div class="static danger">
                                    <x-form class="lockscreen-form" method="post" action="{{ route('request-lockscreen') }}">
                                        <div class="visible">
                                            <div>
                                                <h3>lock screen</h3>
                                                <h4>your screen will be locked</h4>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-lock"></i>
                                            </div>
                                        </div>
                                    </x-form>
                                </div>
                                @endif
                                <div class="static danger">
                                    <x-form class="delete-account-form" method="post" action="{{ route('delete-account') }}">
                                        <div class="visible">
                                            <div>
                                                <h3>delete account</h3>
                                                <h4>request account termination</h4>
                                            </div>
                                            <div>
                                                <i class="fa-solid fa-user-slash"></i>
                                            </div>
                                        </div>
                                    </x-form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ----- ----- ---- ----- ----- -->
        <!-- ----- ----- Main ----- ----- -->
        <!-- ----- ----- ---- ----- ----- -->

        <main>
            <div class="mainParent">
                <div class="roomParent">
                    <div class="header">
                        <div class="back">
                            <a href="{{ route('index') }}">
                                <div class="iconParent">
                                    <i class="fa-solid fa-angle-left"></i>
                                </div>
                            </a>
                        </div>
                        <div class="profile">
                            <div>
                                <div class="avatar active">
                                    <img src="https://imgs.search.brave.com/gQWYzwf_Qnt-Xf0GFD7hKey-F6mXq4jTdtBPhd5u8ew/rs:fit:860:0:0/g:ce/aHR0cHM6Ly9mcmVl/cG5naW1nLmNvbS9z/dGF0aWMvaW1nL3lv/dXR1YmUucG5n">
                                </div>
                            </div>
                            <div>
                                <div class="info">
                                    <h2>william pearson</h2>
                                    <h3>online</h3>
                                </div>
                            </div>
                        </div>
                        <div class="settings">
                            <div class="dropdownParent">
                                <i class="fa-solid fa-ellipsis"></i>
                                <div class="dropdownMenu">
                                    <div class="dropdownItem">delete chat</div>
                                    <div class="line"></div>
                                    <div class="dropdownItem danger">block user</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="main">
                        <div class="emptyParent">
                            <div class="box">
                                <i class="fa-solid fa-paper-plane"></i>
                                <h3>no messages yet, <br> start the conversation!</h3>
                            </div>
                        </div>
                        <div class="chatArea">
                            <div class="message-date">sep 9</div>
                            <div class="message message-left">
                                <div class="content">
                                    Lorem ipsum dolor sit amet
                                </div>
                                <div class="content-date">16:00</div>
                            </div>
                            <div class="message message-left">
                                <div class="content">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta modi sit vero repellendus! Itaque, ipsum?
                                </div>
                                <div class="content-date">16:01</div>
                            </div>
                            <div class="message message-right">
                                <div class="content">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta modi sit vero repellendus! Itaque, ipsum?
                                </div>
                                <div class="content-date">16:05</div>
                            </div>
                            <div class="message message-right">
                                <div class="content">
                                    Lorem ipsum dolor sit amet
                                </div>
                                <div class="content-date">16:10</div>
                            </div>
                            <div class="message message-left">
                                <div class="content">
                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Consectetur fugit expedita illum ipsa doloremque veniam quis eaque, optio, itaque aperiam quaerat quas commodi ut distinctio adipisci esse ab voluptates accusamus.
                                </div>
                                <div class="content-date">16:15</div>
                            </div>
                            <div class="message message-right">
                                <div class="content">
                                    i trust you
                                </div>
                                <div class="content-date">16:20</div>
                            </div>
                            <div class="message message-left">
                                <div class="content">
                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Consectetur fugit expedita
                                </div>
                                <div class="content-date">
                                    16:25
                                    <div class="liked">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="message message-left">
                                <div class="content">
                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Consectetur fugit expedita
                                </div>
                                <div class="content-date">
                                    16:25
                                    <div class="liked">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="message message-right">
                                <div class="content">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores, unde!
                                </div>
                                <div class="content-date">
                                    <div class="liked">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                    16:20
                                </div>
                            </div>
                            <div class="message message-right">
                                <div class="content">
                                    Lorem, ipsum.
                                </div>
                                <div class="content-date">16:20</div>
                            </div>
                            <div class="message-date">sep 10</div>
                            <div class="message message-right">
                                <div class="content">
                                    hey how are you ?
                                </div>
                                <div class="content-date">12:20</div>
                            </div>
                            <div class="message message-left">
                                <div class="content">
                                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Placeat molestias aut ut? Non saepe ullam nesciunt deleniti perspiciatis quo corrupti.
                                </div>
                                <div class="content-date">16:24</div>
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <x-form method="post">
                            <div class="formParent">
                                <input type="text" placeholder="type your message ..." autocomplete="off">
                                <button type="submit">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </button>
                            </div>
                        </x-form>
                    </div>
                </div>
            </div>
        </main>
    </x-slot>
    <x-slot:scripts>
        <script src="{{ asset('js/pages/room.js') }}"></script>
    </x-slot>
</x-app>