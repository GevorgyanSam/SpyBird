// ------ ---- ------------ ---- -----------
// Import Push Notification From Components.
// ------ ---- ------------ ---- -----------
import notify from "../components/push-notifications";
// ------ ------- ------ ---- -----------
// Import Loading Method From Components.
// ------ ------- ------ ---- -----------
import loading from "../components/loading";
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

// ------- -------- -------
// Execute Settings Methods
// ------- -------- -------

Object.values(SettingsComponent).forEach((method) => method());

// ------- ------ -------
// Execute Search Methods
// ------- ------ -------

SearchComponent.switchSearch();
SearchComponent.searchContacts();

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
        let value = $("form#searchContacts input[name=search]").val();
        if (!value) {
            if (search === "familiar") {
                SearchComponent.getSuggestedContacts();
            } else if (search === "nearby") {
                SearchComponent.getNearbyContacts();
            }
        }
    } else if (page === "notifications") {
        NotificationsComponent.getNotifications();
    } else if (page === "friends") {
        FriendsComponent.getFriends();
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
        getRelationship(id);
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
            case "sendFriendRequest":
                sendFriendRequest(id);
                break;
            case "removeFromFriends":
                removeFromFriends(id);
                break;
        }
    });
}

// ---- ------ -- --- ------- ------------ ------- -----
// This Method Is For Getting Relationship Between Users
// ---- ------ -- --- ------- ------------ ------- -----

function getRelationship(id) {
    $.ajax({
        url: `/get-relationship/${id}`,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            setDropdownMenu(response, id);
        },
        error: function (error) {
            location.reload();
        },
    });
}

// ---- ------ -- --- ------- ---- -- --- -------- ----
// This Method Is For Setting Data In The Dropdown Menu
// ---- ------ -- --- ------- ---- -- --- -------- ----

function setDropdownMenu(data, id) {
    let dropdownMenu = $(`.dropdownMenu[data-user-id=${id}]`);
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
    let content = `
        <div class="dropdownItem">send message</div>
        ${friend}
        <div class="line"></div>
        <div class="dropdownItem danger">block user</div>
    `;
    dropdownMenu.html(content);
    dropdownItem();
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
