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
    let content = transformFriendsDataToHtml(data);
    parent.html(content);
}

// ---- ------ -- ------------ ---- -- ----
// This Method Is Transforming Data To Html
// ---- ------ -- ------------ ---- -- ----

function transformFriendsDataToHtml(data) {
    let content = "";
    data.forEach((user) => {
        let avatar = user.avatar
            ? `<img src="/storage/${user.avatar}"></img>`
            : user.name[0];
        let active = user.activity && user.status ? "active" : null;
        let unread = user.unread_message_count
            ? user.unread_message_count > 9
                ? `<div class="count">9+</div>`
                : `<div class="count">${user.unread_message_count}</div>`
            : "";
        let created_at = user.created_at.substr(-8, 5);
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
                        <div class="name">${user.name}</div>
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
