// ------ ------- ------ ---- -----------
// Import Loading Method From Components.
// ------ ------- ------ ---- -----------
import loading from "../../components/loading";
// ------ --- ---- ------- ------ ---- -------
// Import Get Page Content Method From Script.
// ------ --- ---- ------- ------ ---- -------
import { getContent } from "../script";

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

export function getNotifications() {
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
                deleteNotificationAnimation(notification.parents(".report"));
            },
            error: function (error) {
                location.reload();
                loading(false);
            },
        });
    });
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

// ---- ------ -- --- ------ ------------- -----
// This Method Is For Hiding Notifications Count
// ---- ------ -- --- ------ ------------- -----

function hideNotificationCount() {
    let element = $("nav li[data-name=notifications] i");
    element.empty();
}

// ---- ------ -- --- -------- --- -------------
// This Method Is For Clearing All Notifications
// ---- ------ -- --- -------- --- -------------

export function clearNotifications() {
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

// ---- ------ -- --- -------- --- --- ------------- ----- - -------
// This Method Is For Checking For New Notifications Every 3 Seconds
// ---- ------ -- --- -------- --- --- ------------- ----- - -------

export function checkNewNotifications() {
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

// ---- ------ -- --- ------- --- -------------
// This Method Is For Setting New Notifications
// ---- ------ -- --- ------- --- -------------

function setNewNotifications(data) {
    let parent = $(".notificationsParent .reportParent");
    let html = transformNotificationDataToHtml(data);
    parent.prepend(html);
}
