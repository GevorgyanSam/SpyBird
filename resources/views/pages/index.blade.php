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
                        <li id="search" title="Search" data-content="searchParent">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </li>
                        <li id="chat" title="Chat" data-content="chatParent">
                            <i class="fa-solid fa-comments">
                                <div class="count">2</div>
                            </i>
                        </li>
                        <li id="friends" title="Friends" data-content="friendsParent">
                            <i class="fa-solid fa-user-group"></i>
                        </li>
                        <li id="notifications" title="Notifications" data-content="notificationsParent">
                            <i class="fa-solid fa-bell"></i>
                        </li>
                    </ul>
                </div>
                <div class="item">
                    <ul>
                        <li class="mode" title="Color Mode">
                            <i class="fa-solid fa-moon"></i>
                        </li>
                        <li id="settings" title="Settings" data-content="settingsParent">
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
                    <x-form method="post">
                        <div class="formItem">
                            <input type="text" placeholder="Search People">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </x-form>
                    <div class="switchParent">
                        <div class="familiar active">suggested contacts</div>
                        <div class="nearby">people nearby</div>
                    </div>
                    <div class="personParent">
                        <div class="person">
                            <div>
                                <div class="avatar active">
                                    <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/1.jpg">
                                </div>
                            </div>
                            <div class="personInfo">
                                <a href="#">
                                    <h4>william pearson</h4>
                                </a>
                                <div class="status">online</div>
                            </div>
                            <div class="personSettings">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                <div class="dropdownMenu">
                                    <div class="dropdownItem">send message</div>
                                    <div class="dropdownItem">send friend request</div>
                                    <div class="line"></div>
                                    <div class="dropdownItem danger">block user</div>
                                </div>
                            </div>
                        </div>
                        <div class="person">
                            <div>
                                <div class="avatar active">
                                    j
                                </div>
                            </div>
                            <div class="personInfo">
                                <a href="#">
                                    <h4>james</h4>
                                </a>
                                <div class="status">online</div>
                            </div>
                            <div class="personSettings">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                <div class="dropdownMenu">
                                    <div class="dropdownItem">send message</div>
                                    <div class="dropdownItem">send friend request</div>
                                    <div class="line"></div>
                                    <div class="dropdownItem danger">block user</div>
                                </div>
                            </div>
                        </div>
                        <div class="person">
                            <div>
                                <div class="avatar">
                                    <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/2.jpg">
                                </div>
                            </div>
                            <div class="personInfo">
                                <a href="#">
                                    <h4>ollie chandler</h4>
                                </a>
                                <div class="status">last seen 3 hours ago</div>
                            </div>
                            <div class="personSettings">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                <div class="dropdownMenu">
                                    <div class="dropdownItem">send message</div>
                                    <div class="dropdownItem">send friend request</div>
                                    <div class="line"></div>
                                    <div class="dropdownItem danger">block user</div>
                                </div>
                            </div>
                        </div>
                        <div class="person">
                            <div>
                                <div class="avatar">
                                    m
                                </div>
                            </div>
                            <div class="personInfo">
                                <a href="#">
                                    <h4>mike</h4>
                                </a>
                                <div class="status">last seen long time ago</div>
                            </div>
                            <div class="personSettings">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                <div class="dropdownMenu">
                                    <div class="dropdownItem">send message</div>
                                    <div class="dropdownItem">send friend request</div>
                                    <div class="line"></div>
                                    <div class="dropdownItem danger">block user</div>
                                </div>
                            </div>
                        </div>
                        <div class="person">
                            <div>
                                <div class="avatar active">
                                    <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/1.jpg">
                                </div>
                            </div>
                            <div class="personInfo">
                                <a href="#">
                                    <h4>william pearson</h4>
                                </a>
                                <div class="status">online</div>
                            </div>
                            <div class="personSettings">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                <div class="dropdownMenu">
                                    <div class="dropdownItem">send message</div>
                                    <div class="dropdownItem">send friend request</div>
                                    <div class="line"></div>
                                    <div class="dropdownItem danger">block user</div>
                                </div>
                            </div>
                        </div>
                        <div class="person">
                            <div>
                                <div class="avatar active">
                                    j
                                </div>
                            </div>
                            <div class="personInfo">
                                <a href="#">
                                    <h4>james</h4>
                                </a>
                                <div class="status">online</div>
                            </div>
                            <div class="personSettings">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                <div class="dropdownMenu">
                                    <div class="dropdownItem">send message</div>
                                    <div class="dropdownItem">send friend request</div>
                                    <div class="line"></div>
                                    <div class="dropdownItem danger">block user</div>
                                </div>
                            </div>
                        </div>
                        <div class="person">
                            <div>
                                <div class="avatar">
                                    <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/2.jpg">
                                </div>
                            </div>
                            <div class="personInfo">
                                <a href="#">
                                    <h4>ollie chandler</h4>
                                </a>
                                <div class="status">last seen 3 hours ago</div>
                            </div>
                            <div class="personSettings">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                <div class="dropdownMenu">
                                    <div class="dropdownItem">send message</div>
                                    <div class="dropdownItem">send friend request</div>
                                    <div class="line"></div>
                                    <div class="dropdownItem danger">block user</div>
                                </div>
                            </div>
                        </div>
                        <div class="person">
                            <div>
                                <div class="avatar">
                                    m
                                </div>
                            </div>
                            <div class="personInfo">
                                <a href="#">
                                    <h4>mike</h4>
                                </a>
                                <div class="status">last seen long time ago</div>
                            </div>
                            <div class="personSettings">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                <div class="dropdownMenu">
                                    <div class="dropdownItem">send message</div>
                                    <div class="dropdownItem">send friend request</div>
                                    <div class="line"></div>
                                    <div class="dropdownItem danger">block user</div>
                                </div>
                            </div>
                        </div>
                        <div class="person">
                            <div>
                                <div class="avatar active">
                                    <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/1.jpg">
                                </div>
                            </div>
                            <div class="personInfo">
                                <a href="#">
                                    <h4>william pearson</h4>
                                </a>
                                <div class="status">online</div>
                            </div>
                            <div class="personSettings">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                <div class="dropdownMenu">
                                    <div class="dropdownItem">send message</div>
                                    <div class="dropdownItem">send friend request</div>
                                    <div class="line"></div>
                                    <div class="dropdownItem danger">block user</div>
                                </div>
                            </div>
                        </div>
                        <div class="person">
                            <div>
                                <div class="avatar active">
                                    j
                                </div>
                            </div>
                            <div class="personInfo">
                                <a href="#">
                                    <h4>james</h4>
                                </a>
                                <div class="status">online</div>
                            </div>
                            <div class="personSettings">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                <div class="dropdownMenu">
                                    <div class="dropdownItem">send message</div>
                                    <div class="dropdownItem">send friend request</div>
                                    <div class="line"></div>
                                    <div class="dropdownItem danger">block user</div>
                                </div>
                            </div>
                        </div>
                        <div class="person">
                            <div>
                                <div class="avatar">
                                    <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/2.jpg">
                                </div>
                            </div>
                            <div class="personInfo">
                                <a href="#">
                                    <h4>ollie chandler</h4>
                                </a>
                                <div class="status">last seen 3 hours ago</div>
                            </div>
                            <div class="personSettings">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                <div class="dropdownMenu">
                                    <div class="dropdownItem">send message</div>
                                    <div class="dropdownItem">send friend request</div>
                                    <div class="line"></div>
                                    <div class="dropdownItem danger">block user</div>
                                </div>
                            </div>
                        </div>
                        <div class="person">
                            <div>
                                <div class="avatar">
                                    m
                                </div>
                            </div>
                            <div class="personInfo">
                                <a href="#">
                                    <h4>mike</h4>
                                </a>
                                <div class="status">last seen long time ago</div>
                            </div>
                            <div class="personSettings">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                <div class="dropdownMenu">
                                    <div class="dropdownItem">send message</div>
                                    <div class="dropdownItem">send friend request</div>
                                    <div class="line"></div>
                                    <div class="dropdownItem danger">block user</div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                        <a href="#">
                            <div class="chatItem">
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
                                <div class="group">
                                    <div class="name">
                                        <x-logo color="rgb(39, 135, 245)"></x-logo>
                                        <span>WEB Dev Group</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="chatItem">
                                <div class="chat">
                                    <div>
                                        <div class="avatar active">
                                            <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/1.jpg">
                                        </div>
                                    </div>
                                    <div class="chatInfo">
                                        <div class="name">william pearson</div>
                                        <div class="time">12:45</div>
                                        <div class="message">I'm going to meet my friend of mine at the departments stores as soon as possible.</div>
                                        <div class="unread">
                                            <div class="count">2</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="chatItem">
                                <div class="chat">
                                    <div>
                                        <div class="avatar active">
                                            a
                                        </div>
                                    </div>
                                    <div class="chatInfo">
                                        <div class="name">adam black</div>
                                        <div class="time">12:45</div>
                                        <div class="message">I'm going to meet my friend of mine at the departments stores as soon as possible.</div>
                                        <div class="unread"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="chatItem">
                                <div class="chat">
                                    <div>
                                        <div class="avatar">
                                            <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/3.jpg">
                                        </div>
                                    </div>
                                    <div class="chatInfo">
                                        <div class="name">jack</div>
                                        <div class="time">12:45</div>
                                        <div class="message">I'm going to meet my friend of mine at the departments stores as soon as possible.</div>
                                        <div class="unread"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="chatItem">
                                <div class="chat">
                                    <div>
                                        <div class="avatar active">
                                            c
                                        </div>
                                    </div>
                                    <div class="chatInfo">
                                        <div class="name">chris</div>
                                        <div class="time">12:45</div>
                                        <div class="message">I'm going to meet my friend of mine at the departments stores as soon as possible.</div>
                                        <div class="unread"></div>
                                    </div>
                                </div>
                                <div class="group">
                                    <div class="name">
                                        <x-logo color="rgb(39, 135, 245)"></x-logo>
                                        <span>Another Group Name</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="chatItem">
                                <div class="chat">
                                    <div>
                                        <div class="avatar active">
                                            a
                                        </div>
                                    </div>
                                    <div class="chatInfo">
                                        <div class="name">adam black</div>
                                        <div class="time">12:45</div>
                                        <div class="message">I'm going to meet my friend of mine at the departments stores as soon as possible.</div>
                                        <div class="unread"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="chatItem">
                                <div class="chat">
                                    <div>
                                        <div class="avatar">
                                            <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/3.jpg">
                                        </div>
                                    </div>
                                    <div class="chatInfo">
                                        <div class="name">jack</div>
                                        <div class="time">12:45</div>
                                        <div class="message">I'm going to meet my friend of mine at the departments stores as soon as possible.</div>
                                        <div class="unread"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="chatItem">
                                <div class="chat">
                                    <div>
                                        <div class="avatar active">
                                            c
                                        </div>
                                    </div>
                                    <div class="chatInfo">
                                        <div class="name">chris</div>
                                        <div class="time">12:45</div>
                                        <div class="message">I'm going to meet my friend of mine at the departments stores as soon as possible.</div>
                                        <div class="unread"></div>
                                    </div>
                                </div>
                                <div class="group">
                                    <div class="name">
                                        <x-logo color="rgb(39, 135, 245)"></x-logo>
                                        <span>Another Group Name</span>
                                    </div>
                                </div>
                            </div>
                        </a>
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
                        <x-form method="post">
                            <div class="formItem">
                                <input type="text" placeholder="Search People">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                        </x-form>
                        <div class="personParent">
                            <div class="person">
                                <div>
                                    <div class="avatar active">
                                        <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/1.jpg">
                                    </div>
                                </div>
                                <div class="personInfo">
                                    <a href="#">
                                        <h4>william pearson</h4>
                                    </a>
                                    <div class="status">online</div>
                                </div>
                                <div class="personSettings">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                    <div class="dropdownMenu">
                                        <div class="dropdownItem">send message</div>
                                        <div class="dropdownItem">remove from friends</div>
                                        <div class="line"></div>
                                        <div class="dropdownItem danger">block user</div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                            <button>clear all</button>
                        </div>
                        <div class="report">
                            <div class="notice">
                                <div>
                                    <div class="avatar">
                                        <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/1.jpg">
                                    </div>
                                </div>
                                <div class="content">
                                    <div class="name">ollie chandler</div>
                                    <div class="time">12:45</div>
                                    <div class="message">sent you a friend request.</div>
                                    <div class="remove">
                                        <i class="fa-solid fa-trash"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="request">
                                <button class="reject">reject</button>
                                <button class="confirm">confirm</button>
                            </div>
                        </div>
                        <div class="report">
                            <div class="notice">
                                <div>
                                    <div class="avatar">
                                        <i class="fa-solid fa-unlock-keyhole"></i>
                                    </div>
                                </div>
                                <div class="content">
                                    <div class="name">password changed</div>
                                    <div class="time">12:45</div>
                                    <div class="message">password updated successfully.</div>
                                    <div class="remove">
                                        <i class="fa-solid fa-trash"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="report">
                            <div class="notice">
                                <div>
                                    <div class="avatar">
                                        <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/2.jpg">
                                    </div>
                                </div>
                                <div class="content">
                                    <div class="name">jack</div>
                                    <div class="time">12:45</div>
                                    <div class="message">updated profile picture.</div>
                                    <div class="remove">
                                        <i class="fa-solid fa-trash"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="report">
                            <div class="notice">
                                <div>
                                    <div class="avatar">
                                        <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/3.jpg">
                                    </div>
                                </div>
                                <div class="content">
                                    <div class="name">adam black</div>
                                    <div class="time">12:45</div>
                                    <div class="message">sent you a friend request.</div>
                                    <div class="remove">
                                        <i class="fa-solid fa-trash"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="request">
                                <button class="reject">reject</button>
                                <button class="confirm">confirm</button>
                            </div>
                        </div>
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