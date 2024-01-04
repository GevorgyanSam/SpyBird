// ------ ------- ------ ---- -----------
// Import Loading Method From Components.
// ------ ------- ------ ---- -----------
import loading from "../components/loading";

// ---- ------ -- --- ------ --------
// This Method Is For Hiding Messages
// ---- ------ -- --- ------ --------

function hideMessages() {
    let empty = $(".mainParent .roomParent .main .emptyParent");
    let area = $(".mainParent .roomParent .main .chatArea");
    area.hide();
    empty.addClass("active");
}

// ---- ------ -- --- ------- --------
// This Method Is For Showing Messages
// ---- ------ -- --- ------- --------

function showMessages() {
    let empty = $(".mainParent .roomParent .main .emptyParent");
    let area = $(".mainParent .roomParent .main .chatArea");
    empty.removeClass("active");
    area.show();
}

// ---- ------ -- --- ------- --------
// This Method Is For Getting Messages
// ---- ------ -- --- ------- --------

function getMessages() {
    let room = $('meta[name="room-id"]').attr("content");
    $.ajax({
        url: `/get-messages/${room}`,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.empty) {
                hideMessages();
            } else if (response.messages) {
                sessionStorage.setItem(
                    "messages",
                    JSON.stringify(response.messages)
                );
                showMessages();
                setMessages(response.messages);
                removeMessage();
                likeMessage();
                resizeImage();
                scrollAndFocus();
            }
        },
        error: function (error) {
            location.reload();
        },
    });
}

getMessages();

// ---- ------ -- -------- -- ------- --------
// This Method Is Designed To Display Messages
// ---- ------ -- -------- -- ------- --------

function setMessages(messages) {
    let area = $(".mainParent .roomParent .main .chatArea");
    let content = "";
    let date = transformMessageDate(messages[0]);
    content += transformMessageDateToHtml(date);
    messages.forEach((message) => {
        let newDate = transformMessageDate(message);
        if (date != newDate) {
            date = newDate;
            content += transformMessageDateToHtml(date);
        }
        content += transformMessageDataToHtml(message);
    });
    area.html(content);
}

// ---- ------ -- --- ------------ ------- ---- -- ----
// This Method Is For Transforming Message Data To Html
// ---- ------ -- --- ------------ ------- ---- -- ----

function transformMessageDataToHtml(message) {
    let user_id = $('meta[name="user-id"]').attr("content");
    let client_id = $('meta[name="client-id"]').attr("content");
    let text = $("<div/>").text(message.content).html();
    let date = message.created_at.substr(-8, 5);
    let liked = message.liked
        ? `<div class="liked"><i class="fa-solid fa-heart"></i></div>`
        : "";
    let position = "";
    let content = "";
    if (message.user_id == user_id) {
        position = "message-right";
        content = liked + date;
    } else if (message.user_id == client_id) {
        position = "message-left";
        content = date + liked;
        if (!message.seen) {
            setSeenMessage(message.id);
        }
    }
    if (message.type === "image") {
        let asset = $('meta[name="asset-url"]').attr("content");
        let path = asset + "/" + message.content;
        return `
            <div class="message ${position}" data-message-id="${message.id}">
                <div class="content-img">
                    <img src="${path}">
                </div>
                <div class="content-date">${content}</div>
            </div>
        `;
    }
    return `
        <div class="message ${position}" data-message-id="${message.id}">
            <div class="content">${text}</div>
            <div class="content-date">${content}</div>
        </div>
    `;
}

// ---- ------ -- --- ------------ ------- ----
// This Method Is For Transforming Message Date
// ---- ------ -- --- ------------ ------- ----

function transformMessageDate(message) {
    let date = new Date(message.created_at);
    let day = date.getDate();
    let month = date.toLocaleString("default", { month: "short" });
    let content = month + " " + day;
    return content;
}

// ---- ------ -- --- ------------ ------- ---- -- ----
// This Method Is For Transforming Message Date To Html
// ---- ------ -- --- ------------ ------- ---- -- ----

function transformMessageDateToHtml(content) {
    return `<div class="message-date">${content}</div>`;
}

// ---- ------ -- --- ------- ------- -- ----
// This Method Is For Marking Message As Seen
// ---- ------ -- --- ------- ------- -- ----

function setSeenMessage(id) {
    $.ajax({
        url: `/set-seen-message/${id}`,
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

// ---- ------ -- --- ------- --- --------
// This Method Is For Getting New Messages
// ---- ------ -- --- ------- --- --------

function getNewMessages() {
    let room = $('meta[name="room-id"]').attr("content");
    $.ajax({
        url: `/get-new-messages/${room}`,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.client) {
                handleClientData(response.client);
            }
            if (response.empty) {
                sessionStorage.removeItem("messages");
                hideMessages();
            }
            if (response.messages) {
                let oldMessages = sessionStorage.getItem("messages");
                let newMessages = JSON.stringify(response.messages);
                if (oldMessages != newMessages) {
                    sessionStorage.setItem("messages", newMessages);
                    showMessages();
                    setMessages(response.messages);
                    removeMessage();
                    likeMessage();
                    resizeImage();
                    scrollAndFocus();
                }
            }
        },
        error: function (error) {
            if (error.responseJSON.redirect) {
                location.href = "/";
            }
        },
    });
}

getNewMessages();
setInterval(getNewMessages, 1000);

// ---- ------ -- --- -------- ------ ----
// This Method Is For Handling Client Data
// ---- ------ -- --- -------- ------ ----

function handleClientData(client) {
    let oldData = sessionStorage.getItem(`client-${client.id}`);
    let newData = JSON.stringify(client);
    if (oldData !== newData) {
        sessionStorage.setItem(`client-${client.id}`, newData);
        let profile = $(".mainParent .roomParent .header .profile");
        let name = $("<div/>").text(client.name).html();
        let avatar = client.avatar
            ? `<img src="${client.avatar}">`
            : client.name[0];
        let content = `
            <div>
                <div class="avatar ${client.active}">
                    ${avatar}
                </div>
            </div>
            <div>
                <div class="info">
                    <h2>${name}</h2>
                    <h3>${client.status}</h3>
                </div>
            </div>
        `;
        profile.html(content);
    }
}

// ---- ------ -- --- --------- ----
// This Method Is For Scrolling Down
// ---- ------ -- --- --------- ----

function scrollAndFocus() {
    const chat = $(".main .chatArea");
    const input = $(".roomParent .footer .formParent input");
    chat.scrollTop(chat[0].scrollHeight);
    input.focus();
}

scrollAndFocus();

// ---- ------ -- --- ------- - -------
// This Method Is For Sending A Message
// ---- ------ -- --- ------- - -------

function sendLetter() {
    const form = $("form#sendLetter");
    const input = $("form#sendLetter input[name=letter]");

    form.submit((e) => {
        e.preventDefault();
        if (!input.val().trim()) {
            return false;
        }
        $.ajax({
            url: form.attr("action"),
            method: form.attr("method"),
            data: form.serialize(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {},
            error: function (error) {
                location.reload();
            },
        });
        input.val("");
        scrollAndFocus();
    });
}

sendLetter();

// ---- ------ -- --- ------- -- ------
// This Method Is For Sending An Image
// ---- ------ -- --- ------- -- ------

function sendImage() {
    const input = $("form#sendLetter input[name=file]");

    input.on("change", function () {
        let file = input[0].files[0];
        if (file) {
            loading(true);
            let id = $('meta[name="room-id"]').attr("content");
            let data = new FormData();
            data.append("file", file);
            $.ajax({
                url: `/send-image/${id}`,
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: data,
                processData: false,
                contentType: false,
                success: function (response) {
                    input.val("");
                    loading(false);
                },
                error: function (error) {
                    location.reload();
                },
            });
        }
    });
}

sendImage();

// ---- ------ -- --- -------- - -------
// This Method Is For Deleting A Message
// ---- ------ -- --- -------- - -------

function removeMessage() {
    const message = $(".chatArea .message-right");
    message.each(function () {
        let self = $(this);
        self.children(".content, .content-img").dblclick(function () {
            let id = self.data("message-id");
            let room = $('meta[name="room-id"]').attr("content");
            $.ajax({
                url: `/delete-message/${id}`,
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: {
                    room: room,
                },
                success: function (response) {
                    removeMessageAnimation(self);
                },
                error: function (error) {
                    location.reload();
                },
            });
        });
    });
}

removeMessage();

// ---- ------ -- -- --------- --- -------- - -------
// This Method Is An Animation For Deleting A Message
// ---- ------ -- -- --------- --- -------- - -------

function removeMessageAnimation(item) {
    item.css({
        transform: "scale(0)",
    });
    setTimeout(() => {
        item.animate(
            {
                height: 0,
            },
            100
        );
        setTimeout(() => {
            item.remove();
        }, 100);
    }, 200);
}

// ---- ------ -- --- ------ - -------
// This Method Is For Liking A Message
// ---- ------ -- --- ------ - -------

function likeMessage() {
    const message = $(".chatArea .message-left");
    message.each(function () {
        let self = $(this);
        self.children(".content, .content-img").dblclick(function () {
            let liked = self.children(".content-date").children(".liked");
            if (!liked.hasClass("liked")) {
                let id = self.data("message-id");
                let room = $('meta[name="room-id"]').attr("content");
                $.ajax({
                    url: `/like-message/${id}`,
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    data: {
                        room: room,
                    },
                    success: function (response) {
                        likeMessageAnimation(self);
                    },
                    error: function (error) {
                        location.reload();
                    },
                });
            }
        });
    });
}

likeMessage();

// ---- ------ -- -- --------- -- ------ - -------
// This Method Is An Animation Of Liking A Message
// ---- ------ -- -- --------- -- ------ - -------

function likeMessageAnimation(item) {
    let liked = $("<div>");
    liked.addClass("liked");
    liked.html('<i class="fa-solid fa-heart"></i>');
    item.children(".content-date").append(liked);
}

// ---- ------ -- -------- -- ------ ---- -- ---- ------
// This Method Is Designed To Change Size Of Chat Images
// ---- ------ -- -------- -- ------ ---- -- ---- ------

function resizeImage() {
    let image = $(".chatArea .content-img img");
    image.click(function () {
        let path = $(this).attr("src");
    });
}

resizeImage();
