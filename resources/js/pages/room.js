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
    area.empty();
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
                addScrollButton();
                loadImages();
                interactMessage();
                scrollDown();
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
        let data = JSON.parse(message.content);
        let asset = $('meta[name="asset-url"]').attr("content");
        let originalPath = asset + "/" + data.original;
        let lowQualityPath = asset + "/" + data.low;
        return `
            <div class="message ${position}" data-message-id="${message.id}">
                <div class="content-img" style="background-image: url(${lowQualityPath});">
                    <img src="${originalPath}" loading="lazy">
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
                    setNewMessages(response.messages);
                    loadImages();
                    interactMessage();
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

// ---- ------ -- --- ---------- ------- ------------
// This Method Is For Displaying Changed Conversation
// ---- ------ -- --- ---------- ------- ------------

function setNewMessages(messages) {
    let user_id = $('meta[name="user-id"]').attr("content");
    let client_id = $('meta[name="client-id"]').attr("content");
    let asset = $('meta[name="asset-url"]').attr("content");
    let chats = $(".chatArea .message");
    chats.each((index, element) => {
        let chat = $(element);
        let id = chat.data("message-id");
        let message = messages.find((item) => item.id === id);
        if (!message) {
            let lastSibling = chat.siblings().last();
            if (lastSibling.hasClass("message-date")) {
                removeMessageAnimation(lastSibling);
            }
            removeMessageAnimation(chat);
        }
    });
    messages.forEach((message) => {
        let chat = $(`.chatArea .message[data-message-id=${message.id}]`);
        if (!chat.length) {
            let area = $(".chatArea");
            let lastMessage = $(".chatArea .message").last();
            let newMessage = transformMessageDataToHtml(message);
            let lastDate = $(".chatArea .message-date").last().text();
            let newDate = transformMessageDate(message);
            if (lastDate != newDate) {
                if (lastMessage.length) {
                    lastMessage.after(transformMessageDateToHtml(newDate));
                } else {
                    area.prepend(transformMessageDateToHtml(newDate));
                }
                $(".chatArea .message-date").last().after(newMessage);
            } else {
                if (lastMessage.length) {
                    lastMessage.after(newMessage);
                } else {
                    area.prepend(transformMessageDateToHtml(newDate));
                }
            }
            checkScrollDown(message);
        }
        if (message.user_id == user_id) {
            if (!chat.hasClass("message-right")) {
                chat.removeAttr("class");
                chat.addClass("message message-right");
            }
        }
        if (message.user_id == client_id) {
            if (!chat.hasClass("message-left")) {
                chat.removeAttr("class");
                chat.addClass("message message-left");
            }
        }
        if (message.liked) {
            if (!chat.find(".liked").length) {
                likeMessageAnimation(chat);
            }
        } else {
            if (chat.find(".liked").length) {
                removeLikeMessageAnimation(chat);
            }
        }
        if (message.type == "text") {
            if (chat.find(".content").text() != message.content) {
                chat.find(".content").text(message.content);
            }
        } else {
            let path = asset + "/" + JSON.parse(message.content).original;
            if (chat.find(".content-img img").attr("src") != path) {
                chat.find(".content-img img").attr("src", path);
            }
        }
    });
}

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

function scrollDown() {
    const chat = $(".main .chatArea");
    chat.scrollTop(chat[0].scrollHeight);
}

scrollDown();

// ---- ------ -- -------- -- ----- ------- --- --------- ----
// This Method Is Designed To Check Message For Scrolling Down
// ---- ------ -- -------- -- ----- ------- --- --------- ----

function checkScrollDown(message) {
    let user_id = $('meta[name="user-id"]').attr("content");
    let area = $(".chatArea");
    let mainHeight = $(".main").height();
    let scrollHeight = area[0].scrollHeight;
    let scrollTop = area[0].scrollTop;
    let value = Math.ceil(window.innerHeight / 3);
    if (scrollHeight - scrollTop - mainHeight < value) {
        area.css("scroll-behavior", "smooth");
        scrollDown();
        return;
    }
    if (message.user_id == user_id) {
        area.css("scroll-behavior", "smooth");
        scrollDown();
        return;
    }
    showScrollButton();
}

// ---- ------ -- --- ---------- ---- -- ------
// This Method Is For Monitoring Chat On Scroll
// ---- ------ -- --- ---------- ---- -- ------

function checkScroll() {
    let area = $(".chatArea");
    let mainHeight = $(".main").height();
    let value = Math.ceil(window.innerHeight / 3);
    area.scroll(function () {
        let scrollHeight = area[0].scrollHeight;
        let scrollTop = area[0].scrollTop;
        if (scrollHeight - scrollTop - mainHeight < value) {
            hideScrollButton();
        }
    });
}

checkScroll();

// ---- ------ -- --- ------ ------ ---- ------ -- ----
// This Method Is For Adding Scroll Down Button In Chat
// ---- ------ -- --- ------ ------ ---- ------ -- ----

function addScrollButton() {
    let area = $(".chatArea");
    let element = $("<div></div>").addClass("scroll-down");
    let icon = "<i class='fa-regular fa-comments'></i>";
    element.prepend(icon);
    area.prepend(element);
    element.click(function () {
        hideScrollButton();
        scrollDown();
    });
}

// ---- ------ -- --- ------- ------ ---- ------
// This Method Is For Showing Scroll Down Button
// ---- ------ -- --- ------- ------ ---- ------

function showScrollButton() {
    let button = $(".chatArea .scroll-down");
    button.addClass("active");
}

// ---- ------ -- --- ------ ------ ---- ------
// This Method Is For Hiding Scroll Down Button
// ---- ------ -- --- ------ ------ ---- ------

function hideScrollButton() {
    let button = $(".chatArea .scroll-down");
    button.removeClass("active");
}

// ---- ------ -- --- -------- -----
// This Method Is For Focusing Input
// ---- ------ -- --- -------- -----

function addFocus() {
    const input = $(".roomParent .footer .formParent input");
    input.focus();
}

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
        addFocus();
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

// ---- ------ -- --- ------- ------
// This Method Is For Loading Images
// ---- ------ -- --- ------- ------

function loadImages() {
    const container = $(".content-img");
    const img = container.find("img");

    function loaded() {
        container.addClass("loaded");
    }

    if (img.prop("complete")) {
        loaded();
    } else {
        img.on("load", loaded);
    }
}

// ---- ------ -- --- -------- - -------
// This Method Is For Deleting A Message
// ---- ------ -- --- -------- - -------

function removeMessage(message) {
    let id = message.data("message-id");
    let room = $('meta[name="room-id"]').attr("content");
    $.ajax({
        url: `/delete-message/${id}`,
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            room: room,
        },
        success: function (response) {},
        error: function (error) {
            location.reload();
        },
    });
}

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

function likeMessage(message) {
    let liked = message.children(".content-date").children(".liked");
    let id = message.data("message-id");
    let room = $('meta[name="room-id"]').attr("content");
    if (!liked.hasClass("liked")) {
        $.ajax({
            url: `/like-message/${id}`,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                room: room,
            },
            success: function (response) {},
            error: function (error) {
                location.reload();
            },
        });
    }
}

// ---- ------ -- -- --------- -- ------ - -------
// This Method Is An Animation Of Liking A Message
// ---- ------ -- -- --------- -- ------ - -------

function likeMessageAnimation(item) {
    let liked = $("<div>");
    liked.addClass("liked");
    liked.html('<i class="fa-solid fa-heart"></i>');
    if (item.hasClass("message-left")) {
        item.children(".content-date").append(liked);
    } else if (item.hasClass("message-right")) {
        item.children(".content-date").prepend(liked);
    }
}

// ---- ------ -- --- -------- ---- ---- -------
// This Method Is For Removing Like From Message
// ---- ------ -- --- -------- ---- ---- -------

function removeLikeMessage(message) {
    let liked = message.children(".content-date").children(".liked");
    let id = message.data("message-id");
    let room = $('meta[name="room-id"]').attr("content");
    if (liked.hasClass("liked")) {
        $.ajax({
            url: `/remove-like-message/${id}`,
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                room: room,
            },
            success: function (response) {},
            error: function (error) {
                location.reload();
            },
        });
    }
}

// ---- ------ -- -- --------- -- -------- ---- ---- -------
// This Method Is An Animation Of Removing Like From Message
// ---- ------ -- -- --------- -- -------- ---- ---- -------

function removeLikeMessageAnimation(message) {
    let liked = message.find(".liked");
    liked.animate(
        {
            scale: 0,
        },
        100
    );
    setTimeout(() => {
        liked.remove();
    }, 100);
}

// ---- ------ -- -------- -- ------ ---- -- ---- ------
// This Method Is Designed To Change Size Of Chat Images
// ---- ------ -- -------- -- ------ ---- -- ---- ------

function resizeImage(message) {
    let container = $("#imageContainer");
    let element = container.find(".imageParent img");
    let path = message.find(".content-img img").attr("src");
    element.attr("src", path);
    container.addClass("active");

    container.click(function () {
        container.removeClass("active");
        element.attr("src", "");
    });
}

// ---- ------ -- -------- -- -------- -----
// This Method Is Designed To Download Image
// ---- ------ -- -------- -- -------- -----

function downloadImage(message) {
    let path = message.find(".content-img img").attr("src");
    let name = path.substring(path.lastIndexOf("/") + 1);
    let element = $("<a>").attr("href", path).attr("download", name);
    $("body").append(element);
    element[0].click();
    element.remove();
}

// ---- ------ -- -------- -- ---- ---- -- ---------
// This Method Is Designed To Copy Text To Clipboard
// ---- ------ -- -------- -- ---- ---- -- ---------

function copyToClipboard(message) {
    let text = message.find(".content").text();
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text);
    } else {
        $("<textarea>").val(text).appendTo("body").select();
        document.execCommand("copy");
        $("textarea").remove();
    }
}

// ---- ------ -- -------- -- -------- ---- --------
// This Method Is Designed To Interact With Messages
// ---- ------ -- -------- -- -------- ---- --------

function interactMessage() {
    let messages = $(".chatArea .message");

    messages.dblclick(function () {
        closeInteractContainer();
        let self = $(this);
        let response = getMessageInfo(self);
        interactContainer(response);
        interactItem(self);
    });

    $(document).click(function () {
        closeInteractContainer();
    });
}

interactMessage();

// ---- ------ -- --- ------- ---- ----- -------
// This Method Is For Getting Info About Message
// ---- ------ -- --- ------- ---- ----- -------

function getMessageInfo(message) {
    let response = {};
    if (message.hasClass("message-left")) {
        response.owner = false;
        if (
            message.children(".content-date").children("div").hasClass("liked")
        ) {
            response.liked = true;
        } else {
            response.liked = false;
        }
    } else if (message.hasClass("message-right")) {
        response.owner = true;
    }
    if (message.children("div").hasClass("content")) {
        response.text = true;
    } else if (message.children("div").hasClass("content-img")) {
        response.image = true;
    }
    return response;
}

// ---- ------ -- --- ------------ ------- ---- -- -------- ---------
// This Method Is For Transforming Message Data To Interact Container
// ---- ------ -- --- ------------ ------- ---- -- -------- ---------

function interactContainer(message) {
    let parent = $(".roomParent .main .interactParent");
    let container = parent.find(".container");
    container.empty();
    let content = ``;
    if (message.owner) {
        if (message.text) {
            content += `
                <div class="item" data-job="copyClipboard">
                    <i class="fa-solid fa-copy"></i>
                    copy to clipboard
                </div>
            `;
            content += `<div class="line"></div>`;
            content += `
                <div class="item danger" data-job="deleteMessage">
                    <i class="fa-solid fa-trash"></i>
                    delete message
                </div>
            `;
        } else if (message.image) {
            content += `
                <div class="item" data-job="resizeImage">
                    <i class="fa-solid fa-expand"></i>
                    open image
                </div>
            `;
            content += `<div class="line"></div>`;
            content += `
                <div class="item" data-job="downloadImage">
                    <i class="fa-solid fa-download"></i>
                    download image
                </div>
            `;
            content += `<div class="line"></div>`;
            content += `
                <div class="item danger"  data-job="deleteMessage">
                    <i class="fa-solid fa-trash"></i>
                    delete image
                </div>
            `;
        }
    } else {
        if (message.text) {
            content += `
                <div class="item" data-job="copyClipboard">
                    <i class="fa-solid fa-copy"></i>
                    copy to clipboard
                </div>
            `;
            content += `<div class="line"></div>`;
            if (message.liked) {
                content += `
                    <div class="item" data-job="removeLikeMessage">
                        <i class="fa-solid fa-heart-circle-xmark"></i>
                        remove like
                    </div>
                `;
            } else {
                content += `
                    <div class="item" data-job="likeMessage">
                        <i class="fa-solid fa-heart"></i>
                        like message
                    </div>
                `;
            }
        } else if (message.image) {
            content += `
                <div class="item" data-job="resizeImage">
                    <i class="fa-solid fa-expand"></i>
                    open image
                </div>
            `;
            content += `<div class="line"></div>`;
            content += `
                <div class="item" data-job="downloadImage">
                    <i class="fa-solid fa-download"></i>
                    download image
                </div>
            `;
            content += `<div class="line"></div>`;
            if (message.liked) {
                content += `
                    <div class="item" data-job="removeLikeMessage">
                        <i class="fa-solid fa-heart-circle-xmark"></i>
                        remove like
                    </div>
                `;
            } else {
                content += `
                    <div class="item" data-job="likeMessage">
                        <i class="fa-solid fa-heart"></i>
                        like image
                    </div>
                `;
            }
        }
    }
    container.html(content);
    parent.addClass("active");
}

// ---- ------ -- --- ------- -------- ---------
// This Method Is For Closing Interact Container
// ---- ------ -- --- ------- -------- ---------

function closeInteractContainer() {
    let parent = $(".roomParent .main .interactParent");
    parent.removeClass("active");
}

// ---- ------ -- -------- -- ------ --- ----- -- -------- ---------
// This Method Is Designed To Handle The Click In Interact Container
// ---- ------ -- -------- -- ------ --- ----- -- -------- ---------

function interactItem(message) {
    let item = $(".roomParent .main .interactParent .container .item");
    item.click(function () {
        let job = $(this).data("job");
        switch (job) {
            case "copyClipboard":
                copyToClipboard(message);
                break;
            case "deleteMessage":
                removeMessage(message);
                break;
            case "resizeImage":
                resizeImage(message);
                break;
            case "downloadImage":
                downloadImage(message);
                break;
            case "likeMessage":
                likeMessage(message);
                break;
            case "removeLikeMessage":
                removeLikeMessage(message);
                break;
        }
    });
}
