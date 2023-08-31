<x-app title='Room'>
    <x-slot:styles>
        <link rel="stylesheet" href="{{ asset('css/pages/room.css') }}">
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
                        <li data-name="search" class="search" title="Search" data-content="searchParent">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </li>
                        <li data-name="chat" class="chat" title="Chat" data-content="chatParent">
                            <i class="fa-solid fa-comments">
                                <div class="count">2</div>
                            </i>
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
                            <i class="fa-solid fa-comments">
                                <div class="count">2</div>
                            </i>
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
                    <div>
                        <div class="profile">
                            <div>
                                <div class="avatar">
                                    <img src="https://offsetcode.com/themes/messenger/2.2.0/assets/img/avatars/1.jpg">
                                </div>
                            </div>
                            <div class="profileInfo">
                                <h4>william pearson</h4>
                                <div class="email">william@gmail.com</div>
                            </div>
                            <div class="logout" title="Logout">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </div>
                        </div>
                        <div class="container">
                            <h2>account</h2>
                            <div class="box">
                                <div class="static">
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
                                        <x-form>
                                            <div class="formItem">
                                                <label for="avatar">avatar</label>
                                                <input type="file" id="avatar">
                                            </div>
                                            <div class="formItem">
                                                <label for="name">name</label>
                                                <input type="text" id="name" autocomplete="off">
                                            </div>
                                            <div class="formItem">
                                                <button type="submit">save</button>
                                            </div>
                                        </x-form>
                                    </div>
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
                            <h2>security</h2>
                            <div class="box">
                                <div class="static">
                                    <div class="visible">
                                        <div>
                                            <h3>password</h3>
                                            <h4>password change request</h4>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-key"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="static">
                                    <label for="verification">
                                        <div class="visible">
                                            <div>
                                                <h3>two-step verification</h3>
                                                <h4>toggle two-step verification</h4>
                                            </div>
                                            <div>
                                                <input type="checkbox" class="checkbox" id="verification">
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <h2>devices</h2>
                            <div class="box">
                                <div class="static devices">
                                    <div class="visible">
                                        <div>
                                            <h3>linux - armenia, yerevan</h3>
                                            <h4>26 aug 10:52</h4>
                                        </div>
                                        <div>
                                            <div class="active"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="static devices">
                                    <div class="visible">
                                        <div>
                                            <h3>windows - armenia, armavir</h3>
                                            <h4>25 aug 10:52</h4>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-trash"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="static devices">
                                    <div class="visible">
                                        <div>
                                            <h3>iPhone - armenia, paraqar</h3>
                                            <h4>16 sep 10:52</h4>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-trash"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="static devices">
                                    <div class="visible">
                                        <div>
                                            <h3>samsung - armenia, musaler</h3>
                                            <h4>5 jan 10:52</h4>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-trash"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <h2>other</h2>
                            <div class="box">
                                <div class="static danger">
                                    <div class="visible">
                                        <div>
                                            <h3>log out</h3>
                                            <h4>signing out of your account</h4>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="static danger">
                                    <div class="visible">
                                        <div>
                                            <h3>lock screen</h3>
                                            <h4>your screen will be locked</h4>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-lock"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="static danger">
                                    <div class="visible">
                                        <div>
                                            <h3>delete account</h3>
                                            <h4>request account termination</h4>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-user-slash"></i>
                                        </div>
                                    </div>
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
                
            </div>
        </main>
    </x-slot>
    <x-slot:scripts>
        <script src="{{ asset('js/pages/room.js') }}"></script>
    </x-slot>
</x-app>