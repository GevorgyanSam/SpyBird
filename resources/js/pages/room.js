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

function sendMessage() {
    const form = {
        input: $(".roomParent .footer .formParent input"),
        button: $(".roomParent .footer .formParent button"),
    };

    form.button.click((e) => {
        e.preventDefault();
        scrollAndFocus();
    });
}

sendMessage();

// ---- ------ -- --- -------- - -------
// This Method Is For Deleting A Message
// ---- ------ -- --- -------- - -------

function removeMessage() {
    const message = $(".chatArea .message-right");
    message.each(function () {
        let self = $(this);
        self.children(".content").dblclick(function () {
            removeMessageAnimation(self);
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
    }, 200);
}

// ---- ------ -- --- ------ - -------
// This Method Is For Liking A Message
// ---- ------ -- --- ------ - -------

function likeMessage() {
    const message = $(".chatArea .message-left");
    message.each(function () {
        let self = $(this);
        self.children(".content").dblclick(function () {
            let liked = self.children(".content-date").children(".liked");
            if (!liked.hasClass("liked")) {
                likeMessageAnimation(self);
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
