// ---- ------ -- --- --------- ----
// This Method Is For Scrolling Down
// ---- ------ -- --- --------- ----

function scrollAndFocus() {
    const chat = $(".main .chatArea");
    const input = $(".roomParent .footer .formParent input")
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
    }

    form.button.click((e) => {
        e.preventDefault();
        scrollToBottom();
    })
}

sendMessage();