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
import * as Settings from "./components/settings";
// ------- -------- -------
// Execute Settings Methods
// ------- -------- -------
Object.values(Settings).forEach((method) => method());

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

function getContent(page) {
    if (page === "search") {
        let search = $(".searchParent .switchParent > div.active").data("name");
        let value = $("form#searchContacts input[name=search]").val();
        if (!value) {
            if (search === "familiar") {
                getSuggestedContacts();
            } else if (search === "nearby") {
                getNearbyContacts();
            }
        }
    } else if (page === "notifications") {
        getNotifications();
    }
}

// ---- ------ -- --- -------- --- ------ ---- --------
// This Method Is For Clearing The Search Page Contacts
// ---- ------ -- --- -------- --- ------ ---- --------

function clearSearchContacts() {
    const parent = $(".searchParent .personParent");
    parent.empty();
}

// ---- ------ -- --- ------- --------- --------
// This Method Is For Getting Suggested Contacts
// ---- ------ -- --- ------- --------- --------

function getSuggestedContacts() {
    loading(true);
    $.ajax({
        url: "/get-suggested-contacts",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.data) {
                setSearchContacts(response.data);
            } else if (response.empty) {
                clearSearchContacts();
            }
            loading(false);
        },
        error: function (error) {
            location.reload();
            loading(false);
        },
    });
}

// ---- ------ -- --- ------- ------ --------
// This Method Is For Getting Nearby Contacts
// ---- ------ -- --- ------- ------ --------

function getNearbyContacts() {
    loading(true);
    $.ajax({
        url: "/get-nearby-contacts",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.data) {
                setSearchContacts(response.data);
            } else if (response.empty) {
                clearSearchContacts();
            }
            loading(false);
        },
        error: function (error) {
            location.reload();
            loading(false);
        },
    });
}

// ---- ------ -- --- --------- --------
// This Method Is For Searching Contacts
// ---- ------ -- --- --------- --------

function searchContacts() {
    const search = $("form#searchContacts");
    const inp = search.find("input[name=search]");
    const switchParent = $(".searchParent .switchParent");

    search.on("submit", (e) => {
        e.preventDefault();
    });

    inp.on("blur", () => {
        if (!inp.val() && switchParent.css("display") == "none") {
            switchParent.css("display", "flex");
            getContent("search");
        }
    });

    inp.on("input", (e) => {
        e.preventDefault();
        switchParent.css("display", "none");

        if (!e.target.value.length) {
            clearSearchContacts();
            return false;
        }

        $.ajax({
            url: search.attr("action"),
            method: search.attr("method"),
            data: search.serialize(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.data) {
                    setSearchContacts(response.data);
                } else if (response.empty) {
                    clearSearchContacts();
                }
            },
            error: function (error) {
                location.reload();
            },
        });
    });
}

searchContacts();

// ---- ------ -- --- ------- ------ --------
// This Method Is For Setting Search Contacts
// ---- ------ -- --- ------- ------ --------

function setSearchContacts(data) {
    const parent = $(".searchParent .personParent");
    parent.empty();
    let content = "";
    data.forEach((user) => {
        let avatar = user.avatar
            ? `<img src="${user.avatar}"></img>`
            : user.name[0];
        let active = user.status ? "active" : null;
        let updated_at = user.updated_at;
        let status = user.hidden
            ? "hidden status"
            : active
            ? "online"
            : updated_at;
        content += `
        <div class="person">
            <div>
                <div class="avatar ${active}">
                    ${avatar}
                </div>
            </div>
            <div class="personInfo">
                <h4>${user.name}</h4>
                <div class="status">${status}</div>
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
        `;
    });
    parent.html(content);
    toggleDropdown();
}

// ---- ------ -- --- -------- --- ------ -------
// This Method Is For Toggling App Search Content
// ---- ------ -- --- -------- --- ------ -------

function switchSearch() {
    const switches = $(".switchParent > div");

    switches.click(function () {
        if (!$(this).hasClass("active")) {
            switches.removeClass("active");
            $(this).addClass("active");
            let name = $(this).data("name");
            if (name === "familiar") {
                getSuggestedContacts();
            } else if (name === "nearby") {
                getNearbyContacts();
            }
        }
    });
}

switchSearch();

// ---- ------ -- --- ----- ------------- ----
// This Method Is For Empty Notifications View
// ---- ------ -- --- ----- ------------- ----

function emptyNotifications() {
    const parent = $(".notificationsParent");
    const notifications = parent.find("div:first");
    const content = notifications.find("div:nth-child(2)");
    const empty = parent.find(".emptyParent");
    notifications.hide();
    content.empty();
    empty.addClass("active");
}

// ---- ------ -- --- ------- -------------
// This Method Is For Showing Notifications
// ---- ------ -- --- ------- -------------

function showNotifications() {
    const parent = $(".notificationsParent");
    const notifications = parent.find("div:first");
    const empty = parent.find(".emptyParent");
    empty.removeClass("active");
    notifications.show();
}

// ---- ------ -- --- ------- -------------
// This Method Is For Getting Notifications
// ---- ------ -- --- ------- -------------

function getNotifications() {
    loading(true);
    $.ajax({
        url: "/get-notifications",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.data) {
                showNotifications();
                hideNotificationCount();
                setNotifications(response.data);
                setSeenNotifications();
                toggleClearNotificationsButton();
                deleteNotification();
            } else if (response.empty) {
                emptyNotifications();
            }
            loading(false);
        },
        error: function (error) {
            location.reload();
            loading(false);
        },
    });
}

// ---- ------ -- --- ------- -------------
// This Method Is For Setting Notifications
// ---- ------ -- --- ------- -------------

function setNotifications(data) {
    let parent = $(".notificationsParent .reportParent");
    parent.empty();
    let html = transformNotificationDataToHtml(data);
    parent.html(html);
}

// ---- ------ -- --- ------------ ------------ ---- -- ----
// This Method Is For Transforming Notification Data To Html
// ---- ------ -- --- ------------ ------------ ---- -- ----

function transformNotificationDataToHtml(data) {
    let content = "";
    data.forEach((notification) => {
        let date = notification.created_at.substr(-8, 5);
        if (notification.user_id === notification.sender_id) {
            let data = {
                icon: "",
                name: "",
            };
            switch (notification.type) {
                case "avatar_change":
                    data.name = "avatar changed";
                    data.icon = '<i class="fa-solid fa-image"></i>';
                    break;
                case "name_change":
                    data.name = "name changed";
                    data.icon = '<i class="fa-solid fa-pen-to-square"></i>';
                    break;
                case "password_change":
                    data.name = "password changed";
                    data.icon = '<i class="fa-solid fa-unlock-keyhole"></i>';
                    break;
            }
            content += `
            <div class="report">
                <div class="notice">
                    <div>
                        <div class="avatar">
                            ${data.icon}
                        </div>
                    </div>
                    <div class="content">
                        <div class="name">${data.name}</div>
                        <div class="time">${date}</div>
                        <div class="message">${notification.content}.</div>
                        <div class="remove" data-notification-id="${notification.id}">
                            <i class="fa-solid fa-trash"></i>
                        </div>
                    </div>
                </div>
            </div>
            `;
        } else {
            let url = $('meta[name="asset-url"]').attr("content");
            let avatar = notification.sender.avatar
                ? `<img src="${url}/${notification.sender.avatar}"></img>`
                : notification.sender.name[0];
            content += `
            <div class="report">
                <div class="notice">
                    <div>
                        <div class="avatar">
                            ${avatar}
                        </div>
                    </div>
                    <div class="content">
                        <div class="name">${notification.sender.name}</div>
                        <div class="time">${date}</div>
                        <div class="message">${notification.content}.</div>
                    </div>
                </div>
                <div class="request">
                    <button class="reject">reject</button>
                    <button class="confirm">confirm</button>
                </div>
            </div>
            `;
        }
    });
    return content;
}

// ---- ------ -- --- -------- -------------- ------ ---- ------
// This Method Is For Toggling Notification's "Clear All" Button
// ---- ------ -- --- -------- -------------- ------ ---- ------

function toggleClearNotificationsButton() {
    let statement = $(".notificationsParent .report .notice .content .remove")[
        "length"
    ];
    let btn = $("form#clearNotifications");
    if (statement) {
        btn.show();
    } else {
        btn.hide();
    }
}

// ---- ------ -- --- -------- --- -------------
// This Method Is For Clearing All Notifications
// ---- ------ -- --- -------- --- -------------

function clearNotifications() {
    const clear = $("form#clearNotifications");
    clear.on("click", (e) => {
        e.preventDefault();
        loading(true);
        $.ajax({
            url: clear.attr("action"),
            method: clear.attr("method"),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    getContent("notifications");
                }
                loading(false);
            },
            error: function (error) {
                location.reload();
                loading(false);
            },
        });
    });
}

clearNotifications();

// ---- ------ -- --- -------- ------------
// This Method Is For Deleting Notification
// ---- ------ -- --- -------- ------------

function deleteNotification() {
    const btn = $(".notificationsParent .report .notice .content .remove");
    btn.on("click", function () {
        let notification = $(this);
        let id = notification.data("notification-id");
        loading(true);
        $.ajax({
            url: `/delete-notification/${id}`,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                loading(false);
                if (response.success) {
                    deleteNotificationAnimation(
                        notification.parents(".report")
                    );
                }
            },
            error: function (error) {
                location.reload();
                loading(false);
            },
        });
    });
}

// ---- ------ -- --- -------- --- --- ------------- ----- - -------
// This Method Is For Checking For New Notifications Every 3 Seconds
// ---- ------ -- --- -------- --- --- ------------- ----- - -------

function checkNewNotifications() {
    let page = sessionStorage.getItem("current-page");
    if (page !== "notifications") {
        $.ajax({
            url: "/check-new-notifications",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.count) {
                    showNotificationCount(response.count);
                } else {
                    hideNotificationCount();
                }
            },
            error: function (error) {
                location.reload();
            },
        });
    } else {
        $.ajax({
            url: "/get-new-notifications",
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.data) {
                    showNotifications();
                    setNewNotifications(response.data);
                    setSeenNotifications();
                    toggleClearNotificationsButton();
                    deleteNotification();
                }
            },
            error: function (error) {
                location.reload();
            },
        });
    }
}

checkNewNotifications();
setInterval(checkNewNotifications, 3000);

// ---- ------ -- --- ------- --- -------------
// This Method Is For Setting New Notifications
// ---- ------ -- --- ------- --- -------------

function setNewNotifications(data) {
    let parent = $(".notificationsParent .reportParent");
    let html = transformNotificationDataToHtml(data);
    parent.prepend(html);
}

// ---- ------ -- --- ------- ------ -- --- -------------
// This Method Is For Setting "Seen" To New Notifications
// ---- ------ -- --- ------- ------ -- --- -------------

function setSeenNotifications() {
    $.ajax({
        url: "/set-seen-notifications",
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

// ---- ------ -- --- ------- --- ------------- -----
// This Method Is For Showing New Notifications Count
// ---- ------ -- --- ------- --- ------------- -----

function showNotificationCount(int) {
    let count = int > 9 ? "9+" : int;
    let element = $("nav li[data-name=notifications] i");
    let content = `<div class="count">${count}</div>`;
    if (element.html() != content) {
        element.html(content);
    }
}

// ---- ------ -- --- ------ ------------- -----
// This Method Is For Hiding Notifications Count
// ---- ------ -- --- ------ ------------- -----

function hideNotificationCount() {
    let element = $("nav li[data-name=notifications] i");
    element.empty();
}

// ---- ------ -- -- --------- --- -------- - ------------
// This Method Is An Animation For Deleting A Notification
// ---- ------ -- -- --------- --- -------- - ------------

function deleteNotificationAnimation(notification) {
    notification.animate(
        {
            height: 0,
            opacity: 0,
            scale: 0,
        },
        200
    );
    setTimeout(() => {
        notification.remove();
        let report = $(".notificationsParent .report")["length"];
        if (!report) {
            emptyNotifications();
        }
        toggleClearNotificationsButton();
    }, 300);
}

// ---- ------ -- --- -------- --- ------ --------
// This Method Is For Toggling App Search Dropdown
// ---- ------ -- --- -------- --- ------ --------

function toggleDropdown() {
    const dropdown = {
        menu: $(".dropdownMenu"),
        btn: $(".personSettings, .dropdownParent"),
        item: $(".dropdownItem"),
    };

    function closeDropdownMenu() {
        dropdown.menu.removeClass("active");
    }

    dropdown.btn.click(function () {
        closeDropdownMenu();
        $(this).children(".dropdownMenu").addClass("active");
    });

    dropdown.item.click(function () {
        closeDropdownMenu();
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
