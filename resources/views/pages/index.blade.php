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
                                <div class="count">2</div>
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
                <div class="chatParent">
                    <h3>Chat</h3>
                    <x-form method="post">
                        <div class="formItem">
                            <input type="text" placeholder="Search Messages">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </x-form>
                    <a href="#">
                        <div class="chat">
                            <div>
                                <div class="avatar active">
                                    <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/1.jpg">
                                </div>
                            </div>
                            <div class="chatInfo">
                                <div class="name">william pearson</div>
                                <div class="time">12:45</div>
                                <div class="message">Hello! Yeah, I'm going to meet friend of mine at the departments stores now.</div>
                                <div class="unread">
                                    <div class="count">4</div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="chat">
                            <div>
                                <div class="avatar active">
                                    <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/2.jpg">
                                </div>
                            </div>
                            <div class="chatInfo">
                                <div class="name">ollie chandler</div>
                                <div class="time">12:45</div>
                                <div class="message">I'm going to meet my friend of mine at the departments stores as soon as possible.</div>
                                <div class="unread">
                                    <div class="count">2</div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="chat">
                            <div>
                                <div class="avatar active">
                                    m
                                </div>
                            </div>
                            <div class="chatInfo">
                                <div class="name">mike</div>
                                <div class="time">12:45</div>
                                <div class="message">Hello! Yeah, I'm going to meet friend of mine at the departments stores now.</div>
                                <div class="unread"></div>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="chat">
                            <div>
                                <div class="avatar">
                                    <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/3.jpg">
                                </div>
                            </div>
                            <div class="chatInfo">
                                <div class="name">nick</div>
                                <div class="time">12:45</div>
                                <div class="message">I'm going to meet my friend of mine at the departments stores as soon as possible.</div>
                                <div class="unread"></div>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="chat">
                            <div>
                                <div class="avatar">
                                    j
                                </div>
                            </div>
                            <div class="chatInfo">
                                <div class="name">james</div>
                                <div class="time">12:45</div>
                                <div class="message">I'm going to meet my friend</div>
                                <div class="unread"></div>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="chat">
                            <div>
                                <div class="avatar active">
                                    <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/4.jpg">
                                </div>
                            </div>
                            <div class="chatInfo">
                                <div class="name">robert</div>
                                <div class="time">12:45</div>
                                <div class="message">I'm going to meet my friend of mine at the departments stores as soon as possible.</div>
                                <div class="unread"></div>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="chat">
                            <div>
                                <div class="avatar">
                                    j
                                </div>
                            </div>
                            <div class="chatInfo">
                                <div class="name">james</div>
                                <div class="time">12:45</div>
                                <div class="message">I'm going to meet my friend</div>
                                <div class="unread"></div>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="chat">
                            <div>
                                <div class="avatar active">
                                    <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/4.jpg">
                                </div>
                            </div>
                            <div class="chatInfo">
                                <div class="name">robert</div>
                                <div class="time">12:45</div>
                                <div class="message">I'm going to meet my friend of mine at the departments stores as soon as possible.</div>
                                <div class="unread"></div>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="chat">
                            <div>
                                <div class="avatar">
                                    <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/3.jpg">
                                </div>
                            </div>
                            <div class="chatInfo">
                                <div class="name">nick</div>
                                <div class="time">12:45</div>
                                <div class="message">I'm going to meet my friend of mine at the departments stores as soon as possible.</div>
                                <div class="unread"></div>
                            </div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="chat">
                            <div>
                                <div class="avatar active">
                                    m
                                </div>
                            </div>
                            <div class="chatInfo">
                                <div class="name">mike</div>
                                <div class="time">12:45</div>
                                <div class="message">Hello! Yeah, I'm going to meet friend of mine at the departments stores now.</div>
                                <div class="unread"></div>
                            </div>
                        </div>
                    </a>
                </div>
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