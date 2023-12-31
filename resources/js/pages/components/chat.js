// ------ ------- ------ ---- -----------
// Import Loading Method From Components.
// ------ ------- ------ ---- -----------
import loading from "../../components/loading";
// ------ --- ---- ------- ------ ---- -------
// Import Get Page Content Method From Script.
// ------ --- ---- ------- ------ ---- -------
import { getContent } from "../script";

// ---- ------ -- --- ------- -----
// This Method Is For Getting Chats
// ---- ------ -- --- ------- -----

export function getChats() {
    loading(true);
    $.ajax({
        url: "/get-chats",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.data) {
                showChats();
                setChats(response.data);
            } else if (response.empty) {
                hideChats();
            }
            loading(false);
        },
        error: function (error) {
            location.reload();
            loading(false);
        },
    });
}

// ---- ------ -- --- ------- -----
// This Method Is For Setting Chats
// ---- ------ -- --- ------- -----

function setChats(data) {
    sessionStorage.setItem("chats", JSON.stringify(data));
    const parent = $(".chatParent .contentParent");
    parent.empty();
    let content = transformChatDataToHtml(data);
    parent.html(content);
}

// ---- ------ -- ------------ ---- -- ----
// This Method Is Transforming Data To Html
// ---- ------ -- ------------ ---- -- ----

function transformChatDataToHtml(data) {
    let content = "";
    data.forEach((user) => {
        let name = "";
        let avatar = "";
        let active = "";
        let created_at = user.created_at.substr(-8, 5);
        let unread = user.unread_message_count
            ? user.unread_message_count > 9
                ? `<div class="count">9+</div>`
                : `<div class="count">${user.unread_message_count}</div>`
            : "";
        if (user.spy) {
            name = "anonymous";
            let int = Math.floor(Math.random() * 2) + 1;
            let url = `/assets/anonymous-${int}.png`;
            avatar = `<img src="${url}"></img>`;
            active = "";
        } else {
            name = user.name;
            avatar = user.avatar
                ? `<img src="/storage/${user.avatar}"></img>`
                : user.name[0];
            active = user.activity && user.status ? "active" : "";
        }
        content += `
            <a href="/room/${user.room_id}">
                <div class="chatItem">
                    <div class="chat">
                        <div>
                            <div class="avatar ${active}">
                                ${avatar}
                            </div>
                        </div>
                        <div class="chatInfo">
                            <div class="name">${name}</div>
                            <div class="time">${created_at}</div>
                            <div class="message">${user.message}</div>
                            <div class="unread">${unread}</div>
                        </div>
                    </div>
                </div>
            </a>
        `;
    });
    return content;
}

// ---- ------ -- --- ------ --- ---- ----
// This Method Is For Hiding The Chat Page
// ---- ------ -- --- ------ --- ---- ----

function hideChats() {
    const parent = $(".chatParent");
    const empty = parent.find("> div.emptyParent");
    const content = parent.find("> div:not(.emptyParent)");
    content.hide();
    empty.addClass("active");
}

// ---- ------ -- --- ------- --- ---- ----
// This Method Is For Showing The Chat Page
// ---- ------ -- --- ------- --- ---- ----

function showChats() {
    const parent = $(".chatParent");
    const empty = parent.find("> div.emptyParent");
    const content = parent.find("> div:not(.emptyParent)");
    empty.removeClass("active");
    content.show();
}

// ---- ------ -- --- -------- --- ---- ----
// This Method Is For Clearing The Chat Page
// ---- ------ -- --- -------- --- ---- ----

function clearChats() {
    const parent = $(".chatParent .contentParent");
    parent.empty();
}

// ---- ------ -- --- --------- --------
// This Method Is For Searching Contacts
// ---- ------ -- --- --------- --------

export function searchChats() {
    const search = $("form#searchChats");
    const inp = search.find("input[name=search]");

    search.on("submit", (e) => {
        e.preventDefault();
    });

    inp.on("blur", () => {
        if (!inp.val()) {
            getContent("chat");
        }
    });

    inp.on("input", (e) => {
        e.preventDefault();

        if (!e.target.value.length) {
            clearChats();
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
                    setChats(response.data);
                } else if (response.empty) {
                    clearChats();
                }
            },
            error: function (error) {
                location.reload();
            },
        });
    });
}

// ---- ------ -- --- ------- --- -----
// This Method Is For Getting New Chats
// ---- ------ -- --- ------- --- -----

export function getNewChats() {
    let page = sessionStorage.getItem("current-page");
    let input = $("form#searchChats input[name=search]");
    if (input.val() || input.is(":focus")) {
        return false;
    }
    $.ajax({
        url: "/get-chats",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.data) {
                let count = filterCount(response.data);
                count ? showChatCount(count) : hideChatCount();
                if (page === "chat") {
                    showChats();
                    setNewChats(response.data);
                }
            } else if (response.empty && page === "chat") {
                hideChatCount();
                hideChats();
            } else {
                hideChatCount();
            }
        },
        error: function (error) {
            location.reload();
        },
    });
}

// ---- ------ -- --- ------- -----
// This Method Is For Setting Chats
// ---- ------ -- --- ------- -----

function setNewChats(data) {
    let oldData = sessionStorage.getItem("chats");
    let newData = JSON.stringify(data);
    if (oldData !== newData) {
        sessionStorage.setItem("chats", newData);
        let parent = $(".chatParent .contentParent");
        let content = transformChatDataToHtml(data);
        parent.html(content);
    }
}

// ---- ------ -- -------- -- ------ ----- ---- --------
// This Method Is Designed To Filter Count From Messages
// ---- ------ -- -------- -- ------ ----- ---- --------

function filterCount(data) {
    let count = data.filter((item) => item.unread_message_count > 0).length;
    return count;
}

// ---- ------ -- --- ------- --- ------------- -----
// This Method Is For Showing New Notifications Count
// ---- ------ -- --- ------- --- ------------- -----

function showChatCount(int) {
    let count = int > 9 ? "9+" : int;
    let element = $("nav li[data-name=chat] i");
    let content = `<div class="count">${count}</div>`;
    if (element.html() != content) {
        element.html(content);
    }
}

// ---- ------ -- --- ------ ------------- -----
// This Method Is For Hiding Notifications Count
// ---- ------ -- --- ------ ------------- -----

function hideChatCount() {
    let element = $("nav li[data-name=chat] i");
    element.empty();
}
