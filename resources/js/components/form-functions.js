// ---- ------ -- --- ------ ------ -- ----- -----
// This Method Is For Moving Labels In Input Focus
// ---- ------ -- --- ------ ------ -- ----- -----

export function focus(input, label) {
    if (input.val() != 0) {
        label.addClass("focus");
    } else {
        label.removeClass("focus");
    }

    input.focus(function () {
        if (input.val() == 0) {
            label.addClass("focus");
        }
    });

    input.blur(function () {
        if (input.val() == 0) {
            label.removeClass("focus");
        }
    });
}

// ---- ------ -- --- -------- --- ----- -------- ----
// This Method Is For Changing The Input Password Type
// ---- ------ -- --- -------- --- ----- -------- ----

export function eye(input, icon) {
    input.keyup(function () {
        if (input.val().length > 0) {
            icon.css("display", "block");
        } else if (input.val() == 0) {
            icon.css("display", "none");
        }
    });

    icon.click(function () {
        if (icon.hasClass("fa-eye-slash")) {
            icon.removeClass("fa-eye-slash");
            icon.addClass("fa-eye");
            icon.css({
                right: "1px",
            });
            input.attr("type", "text");
            input.focus();
        } else if (icon.hasClass("fa-eye")) {
            icon.removeClass("fa-eye");
            icon.addClass("fa-eye-slash");
            icon.css({
                right: "0",
            });
            input.attr("type", "password");
            input.focus();
        }
    });
}
