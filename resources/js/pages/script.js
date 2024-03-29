// ------ -------- ------- ---- -----------
// Import Settings Methods From Components.
// ------ -------- ------- ---- -----------
import * as SettingsComponent from "./components/settings";
// ------ ------ ------- ---- -----------
// Import Search Methods From Components.
// ------ ------ ------- ---- -----------
import * as SearchComponent from "./components/search";
// ------ ------------- ------- ---- -----------
// Import Notifications Methods From Components.
// ------ ------------- ------- ---- -----------
import * as NotificationsComponent from "./components/notifications";
// ------ ------- ------- ---- -----------
// Import Friends Methods From Components.
// ------ ------- ------- ---- -----------
import * as FriendsComponent from "./components/friends";
// ------ ---- ------- ---- -----------
// Import Chat Methods From Components.
// ------ ---- ------- ---- -----------
import * as ChatComponent from "./components/chat";

// ------- -------- -------
// Execute Settings Methods
// ------- -------- -------

Object.values(SettingsComponent).forEach((method) => method());

// ------- ------ -------
// Execute Search Methods
// ------- ------ -------

SearchComponent.switchSearch();
SearchComponent.searchContacts();

// ------- ------- -------
// Execute Friends Methods
// ------- ------- -------

FriendsComponent.searchFriends();
FriendsComponent.getNewFriends();
setInterval(FriendsComponent.getNewFriends, 3000);

// ------- ---- -------
// Execute Chat Methods
// ------- ---- -------

ChatComponent.searchChats();
ChatComponent.getNewChats();
setInterval(ChatComponent.getNewChats, 1000);

// ------- ------------- -------
// Execute Notifications Methods
// ------- ------------- -------

NotificationsComponent.clearNotifications();
NotificationsComponent.checkNewNotifications();
setInterval(NotificationsComponent.checkNewNotifications, 3000);

// ---- ------ -- --- -------- --- --- ----- -------
// This Method Is For Changing The App Aside Content
// ---- ------ -- --- -------- --- --- ----- -------

function changePages() {
    const pages = {
        actions: $("nav li:not(.mode)"),
        content: $(".asideParent > div"),
    };

    const setActivePage = (pageId) => {
        const btn = $(`.${pageId}`);
        pages.actions.removeClass("active");
        btn.addClass("active");
        const content = $(`.${btn.data("content")}`);
        pages.content.removeClass("active");
        content.addClass("active");
        sessionStorage.setItem("current-page", pageId);
    };

    let current = sessionStorage.getItem("current-page");
    if (current == undefined) {
        current = "chat";
        sessionStorage.setItem("current-page", current);
    }

    getContent(current);
    setActivePage(current);

    pages.actions.click(function () {
        if (!$(this).hasClass("active")) {
            const page = $(this).data("name");
            getContent(page);
            setActivePage(page);
        }
    });
}

changePages();

// ---- ------ -- --- ------- --- --- ----- ---- -------
// This Method Is For Getting The App Aside Page Content
// ---- ------ -- --- ------- --- --- ----- ---- -------

export function getContent(page) {
    if (page === "search") {
        let search = $(".searchParent .switchParent > div.active").data("name");
        let searchInput = $("form#searchContacts input[name=search]").val();
        if (!searchInput) {
            if (search === "familiar") {
                SearchComponent.getSuggestedContacts();
            } else if (search === "nearby") {
                SearchComponent.getNearbyContacts();
            }
        }
    } else if (page === "notifications") {
        NotificationsComponent.getNotifications();
    } else if (page === "friends") {
        let friendsInput = $("form#searchFriends input[name=search]").val();
        if (!friendsInput) {
            FriendsComponent.getFriends();
        }
    } else if (page === "chat") {
        let chatInput = $("form#searchChats input[name=search]").val();
        if (!chatInput) {
            ChatComponent.getChats();
        }
    }
}

// ---- ------ -- --- -------- --- ------ --------
// This Method Is For Toggling App Search Dropdown
// ---- ------ -- --- -------- --- ------ --------

export function toggleDropdown() {
    const dropdown = {
        menu: $(".dropdownMenu"),
        btn: $(".personSettings, .dropdownParent"),
    };

    dropdown.btn.click(function () {
        let dropdownMenu = $(this).children(".dropdownMenu");
        if (dropdownMenu.hasClass("active")) {
            return false;
        }
        closeDropdownMenu();
        let id = dropdownMenu.data("user-id");
        if ($(this).hasClass("personSettings")) {
            asideDropdown(id);
        } else if ($(this).hasClass("dropdownParent")) {
            roomDropdown(id);
        }
        dropdownMenu.addClass("active");
    });

    $(document).click(function (e) {
        let target = $(e.target);
        if (
            !target.hasClass("personSettings") &&
            !target.hasClass("fa-solid fa-ellipsis-vertical") &&
            !target.hasClass("fa-solid fa-ellipsis") &&
            !target.hasClass("line") &&
            !target.hasClass("dropdownMenu") &&
            !target.hasClass("dropdownParent")
        ) {
            closeDropdownMenu();
        }
    });
}

toggleDropdown();

// ---- ------ -- --- ------- -------- ----
// This Method Is For Closing Dropdown Menu
// ---- ------ -- --- ------- -------- ----

function closeDropdownMenu() {
    let dropdownMenu = $(".dropdownMenu");
    dropdownMenu.removeClass("active");
}

// ---- ------ -- --- -------- -- -------- ----
// This Method Is For Clicking On Dropdown Item
// ---- ------ -- --- -------- -- -------- ----

function dropdownItem() {
    let item = $(".dropdownMenu .dropdownItem");
    item.click(function () {
        closeDropdownMenu();
        let id = $(this).parent(".dropdownMenu").data("user-id");
        let job = $(this).data("job");
        switch (job) {
            case "sendMessage":
                sendMessage(id);
                break;
            case "sendFriendRequest":
                sendFriendRequest(id);
                break;
            case "removeFromFriends":
                removeFromFriends(id);
                FriendsComponent.removeFromFriendsAnimation(
                    $(this).parents(".person")
                );
                break;
            case "unblockUser":
                unblockUser(id);
                break;
            case "blockUser":
                blockUser(id);
                break;
            case "deleteChat":
                deleteChat(id);
                break;
        }
    });
}

// ---- ------ -- --- ------- ------------ ------- -----
// This Method Is For Getting Relationship Between Users
// ---- ------ -- --- ------- ------------ ------- -----

function asideDropdown(id) {
    $.ajax({
        url: `/get-aside-dropdown-data/${id}`,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            setAsideDropdownMenu(response, id);
        },
        error: function (error) {
            location.reload();
        },
    });
}

// ---- ------ -- --- ------- ------------ ------- -----
// This Method Is For Getting Relationship Between Users
// ---- ------ -- --- ------- ------------ ------- -----

function roomDropdown(id) {
    $.ajax({
        url: `/get-room-dropdown-data/${id}`,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            setRoomDropdownMenu(response, id);
        },
        error: function (error) {
            location.reload();
        },
    });
}

// ---- ------ -- --- ------- ---- -- --- -------- ----
// This Method Is For Setting Data In The Dropdown Menu
// ---- ------ -- --- ------- ---- -- --- -------- ----

function setAsideDropdownMenu(data, id) {
    let dropdownMenu = $(`.dropdownMenu[data-user-id=${id}]`);
    let message =
        '<div class="dropdownItem" data-job="sendMessage">send message</div>';
    let friend = "";
    switch (data.friend) {
        case "request":
            friend =
                '<div class="dropdownItem" data-job="sendFriendRequest">send friend request</div>';
            break;
        case "remove":
            friend =
                '<div class="dropdownItem" data-job="removeFromFriends">remove from friends</div>';
            break;
    }
    let blocked = "";
    switch (data.blocked) {
        case "block":
            blocked =
                '<div class="dropdownItem danger" data-job="blockUser">block user</div>';
            break;
        case "unblock":
            blocked =
                '<div class="dropdownItem danger" data-job="unblockUser">unblock user</div>';
            break;
    }
    let content = `
        ${message}
        ${friend}
        <div class="line"></div>
        ${blocked}
    `;
    dropdownMenu.html(content);
    dropdownItem();
}

// ---- ------ -- --- ------- ---- -- --- -------- ----
// This Method Is For Setting Data In The Dropdown Menu
// ---- ------ -- --- ------- ---- -- --- -------- ----

function setRoomDropdownMenu(data, id) {
    let dropdownMenu = $(`.dropdownMenu[data-user-id=${id}]`);
    let deleteChat =
        '<div class="dropdownItem" data-job="deleteChat">delete chat</div>';
    let blocked = "";
    switch (data.blocked) {
        case "block":
            blocked =
                '<div class="dropdownItem danger" data-job="blockUser">block user</div>';
            break;
        case "unblock":
            blocked =
                '<div class="dropdownItem danger" data-job="unblockUser">unblock user</div>';
            break;
    }
    let content = `
        ${deleteChat}
        <div class="line"></div>
        ${blocked}
    `;
    dropdownMenu.html(content);
    dropdownItem();
}

// ---- ------ -- --- ------- -------
// This Method Is For Sending Message
// ---- ------ -- --- ------- -------

function sendMessage(id) {
    $.ajax({
        url: `/send-message/${id}`,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.room_id) {
                let id = response.room_id;
                sessionStorage.removeItem("current-page");
                location.href = `/room/${id}`;
            } else {
                location.reload();
            }
        },
        error: function (error) {
            location.reload();
        },
    });
}

// ---- ------ -- --- ------- ------ ------- -- ----- ----
// This Method Is For Sending Friend Request To Other User
// ---- ------ -- --- ------- ------ ------- -- ----- ----

function sendFriendRequest(id) {
    $.ajax({
        url: `/send-friend-request/${id}`,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {},
        error: function (error) {
            location.reload();
        },
    });
}

// ---- ------ -- --- -------- ---- ------ ----
// This Method Is For Removing From Friend List
// ---- ------ -- --- -------- ---- ------ ----

function removeFromFriends(id) {
    $.ajax({
        url: `/remove-from-friends/${id}`,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {},
        error: function (error) {
            location.reload();
        },
    });
}

// ---- ------ -- -------- -- ------- ----
// This Method Is Designed To Unblock User
// ---- ------ -- -------- -- ------- ----

function unblockUser(id) {
    $.ajax({
        url: `/unblock-user/${id}`,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {},
        error: function (error) {
            location.reload();
        },
    });
}

// ---- ------ -- -------- -- ----- ----
// This Method Is Designed To Block User
// ---- ------ -- -------- -- ----- ----

function blockUser(id) {
    $.ajax({
        url: `/block-user/${id}`,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {},
        error: function (error) {
            location.reload();
        },
    });
}

// ---- ------ -- -------- -- ------ ----
// This Method Is Designed To Delete Chat
// ---- ------ -- -------- -- ------ ----

function deleteChat(id) {
    let spy = $('meta[name="spy"]').attr("content");
    $.ajax({
        url: `/delete-chat/${id}`,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            spy: spy,
        },
        success: function (response) {
            if (response.success) {
                location.href = "/";
            }
        },
        error: function (error) {
            location.reload();
        },
    });
}

// ---- ------ -- --- -------- -------------- ----- - -------
// This Method Is For Checking Authentication Every 3 Seconds
// ---- ------ -- --- -------- -------------- ----- - -------

function checkAuthentication() {
    $.ajax({
        url: "/check-authentication",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.reload) {
                location.reload();
            }
        },
        error: function (error) {
            if (error) {
                location.reload();
            }
        },
    });
}

checkAuthentication();
setInterval(checkAuthentication, 3000);
