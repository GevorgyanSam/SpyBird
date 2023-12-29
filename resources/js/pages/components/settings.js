// ------ ---- ------- ---- -----------
// Import Form Methods From Components.
// ------ ---- ------- ---- -----------
import { focus } from "../../components/form-functions";
// ------ ---- ------------ ---- -----------
// Import Push Notification From Components.
// ------ ---- ------------ ---- -----------
import notify from "../../components/push-notifications";
// ------ ------- ------ ---- -----------
// Import Loading Method From Components.
// ------ ------- ------ ---- -----------
import loading from "../../components/loading";

// ---- ------ -- --- -------- --- ----- ----
// This Method Is For Changing App Color Mode
// ---- ------ -- --- -------- --- ----- ----

export function changeColorMode() {
    const mode = {
        btn: $(".mode"),
        btn_settings: $(".static.theme"),
        icon: $(".mode i"),
        icon_settings: $(".static.theme i"),
    };

    const change = () => {
        let storage = localStorage.getItem("mode");
        if (storage == "light") {
            $("body").removeClass("light");
            localStorage.removeItem("mode");
            mode.icon.removeClass("fa-sun");
            mode.icon.addClass("fa-moon");
            mode.icon_settings.removeClass("fa-sun");
            mode.icon_settings.addClass("fa-moon");
        } else {
            $("body").addClass("light");
            localStorage.setItem("mode", "light");
            mode.icon.removeClass("fa-moon");
            mode.icon.addClass("fa-sun");
            mode.icon_settings.removeClass("fa-moon");
            mode.icon_settings.addClass("fa-sun");
        }
    };

    mode.btn.click(function () {
        change();
    });

    mode.btn_settings.click(function () {
        change();
    });
}

// ---- ------ -- --- ----- -- ---- ------
// This Method Is For Focus On Form Inputs
// ---- ------ -- --- ----- -- ---- ------

export function focusOnInput() {
    const form = {
        name: {
            input: $("#name"),
            label: $("label[for=name]"),
        },
    };

    focus(form.name.input, form.name.label);
}

// ---- ------ -- --- -------- -------- ---------
// This Method Is For Toggling Settings Accordion
// ---- ------ -- --- -------- -------- ---------

export function switchAccordion() {
    const accordions = $(".settingsParent .accordion");

    accordions.each(function () {
        const accordion = {
            icon: $(this).find(".visible i"),
            visible: $(this).find(".visible"),
            hidden: $(this).find(".hidden"),
        };

        accordion.visible.click(() => {
            accordion.icon.toggleClass("rotate");
            accordion.hidden.toggleClass("active");
        });
    });
}

// ---- ------ -- --- --------- -- ---- ------ -----
// This Method Is For Switching To Full Screen Mode.
// ---- ------ -- --- --------- -- ---- ------ -----

export function switchFullScreen() {
    const fullscreen = {
        button: $(".settingsParent .fullScreen"),
        h4: $(".settingsParent .fullScreen h4"),
        icon: $(".settingsParent .fullScreen i"),
    };

    function enterFullscreen() {
        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
            document.documentElement.webkitRequestFullscreen();
        } else if (document.documentElement.msRequestFullscreen) {
            document.documentElement.msRequestFullscreen();
        }
    }

    function exitFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }

    fullscreen.button.click(function () {
        if (
            document.fullscreenElement ||
            document.mozFullScreenElement ||
            document.webkitFullscreenElement ||
            document.msFullscreenElement
        ) {
            exitFullscreen();
            fullscreen.h4.text("Switch to Full Screen Mode");
            fullscreen.icon.removeClass("fa-compress").addClass("fa-expand");
        } else {
            enterFullscreen();
            fullscreen.h4.text("Exit Full Screen Mode");
            fullscreen.icon.removeClass("fa-expand").addClass("fa-compress");
        }
    });
}

// ---- ------ -- --- --------- -- --- -----
// This Method Is For Switching To Spy Mode.
// ---- ------ -- --- --------- -- --- -----

export function switchSpyMode() {
    const checkbox = $(".settingsParent .spy input[type=checkbox]");
    checkbox.change((e) => {
        loading(true);
        if (e.target.checked) {
            e.target.checked = false;
            $.ajax({
                url: "/request-enable-spy-mode",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        loading(false);
                        e.target.checked = true;
                    } else if (response.reload) {
                        location.reload();
                    }
                },
                error: function (error) {
                    loading(false);
                    location.reload();
                },
            });
        } else {
            e.target.checked = true;
            $.ajax({
                url: "/request-disable-spy-mode",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        loading(false);
                        e.target.checked = false;
                    } else if (response.reload) {
                        location.reload();
                    }
                },
                error: function (error) {
                    loading(false);
                    location.reload();
                },
            });
        }
    });
}

// ---- ------ -- --- --------- -- --------- -----
// This Method Is For Switching To Invisible Mode.
// ---- ------ -- --- --------- -- --------- -----

export function switchInvisibleMode() {
    const checkbox = $(".settingsParent .invisible input[type=checkbox]");
    checkbox.change((e) => {
        loading(true);
        if (e.target.checked) {
            e.target.checked = false;
            $.ajax({
                url: "/request-enable-invisible-mode",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        loading(false);
                        e.target.checked = true;
                    } else if (response.reload) {
                        location.reload();
                    }
                },
                error: function (error) {
                    loading(false);
                    location.reload();
                },
            });
        } else {
            e.target.checked = true;
            $.ajax({
                url: "/request-disable-invisible-mode",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        loading(false);
                        e.target.checked = false;
                    } else if (response.reload) {
                        location.reload();
                    }
                },
                error: function (error) {
                    loading(false);
                    location.reload();
                },
            });
        }
    });
}

// ---- ------ -- --- --------- -------- -------
// This Method Is For Switching Activity Status.
// ---- ------ -- --- --------- -------- -------

export function switchActivityStatus() {
    const checkbox = $(".settingsParent .activity input[type=checkbox]");
    checkbox.change((e) => {
        loading(true);
        if (e.target.checked) {
            e.target.checked = false;
            $.ajax({
                url: "/request-show-activity",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        loading(false);
                        e.target.checked = true;
                    } else if (response.reload) {
                        location.reload();
                    }
                },
                error: function (error) {
                    loading(false);
                    location.reload();
                },
            });
        } else {
            e.target.checked = true;
            $.ajax({
                url: "/request-hide-activity",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        loading(false);
                        e.target.checked = false;
                    } else if (response.reload) {
                        location.reload();
                    }
                },
                error: function (error) {
                    loading(false);
                    location.reload();
                },
            });
        }
    });
}

// ---- ------ -- --- --- ---- ------------
// This Method Is For Two Step Verification
// ---- ------ -- --- --- ---- ------------

export function switchTwoStepVerification() {
    const checkbox = $(".settingsParent .verification input[type=checkbox]");
    checkbox.change((e) => {
        loading(true);
        if (e.target.checked) {
            e.target.checked = false;
            $.ajax({
                url: "/request-enable-two-factor-authentication",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        loading(false);
                        notify(
                            "check your email",
                            "enable 2FA link has been sent to your email"
                        );
                    } else if (response.reload) {
                        location.reload();
                    }
                },
                error: function (error) {
                    loading(false);
                    if (error.status === 429) {
                        notify("too many requests", "try again after a while");
                    }
                },
            });
        } else {
            e.target.checked = true;
            $.ajax({
                url: "/request-disable-two-factor-authentication",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        loading(false);
                        notify(
                            "check your email",
                            "disable 2FA link has been sent to your email"
                        );
                    } else if (response.reload) {
                        location.reload();
                    }
                },
                error: function (error) {
                    loading(false);
                    if (error.status === 429) {
                        notify("too many requests", "try again after a while");
                    }
                },
            });
        }
    });
}

// ---- ------ -- --- ------
// This Method Is For Logout
// ---- ------ -- --- ------

export function logout() {
    const logout = $("form.logout-form");
    logout.on("click", (e) => {
        e.preventDefault();
        loading(true);
        $.ajax({
            url: logout.attr("action"),
            method: logout.attr("method"),
            data: logout.serialize(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    sessionStorage.removeItem("current-page");
                    loading(false);
                    location.reload();
                }
            },
            error: function (error) {
                loading(false);
            },
        });
    });
}

// ---- ------ -- --- ------- ---- ------- ----
// This Method Is For Editing User Profile Data
// ---- ------ -- --- ------- ---- ------- ----

export function editProfile() {
    const settings = {
        avatar: $(".settingsParent .profile .avatar"),
        name: $(".settingsParent .profile .profileInfo h4"),
    };
    const form = $("form#updateProfile");
    const file = form.find("input[type=file]");
    const avatarLabel = form.find("label[for=avatar]");
    const nameLabel = form.find("label[for=name]");

    file.on("change", () => {
        avatarLabel.removeClass("error");
        if (file[0].files.length) {
            avatarLabel.text(file[0].files[0].name);
        } else {
            avatarLabel.text(avatarLabel.attr("for"));
        }
    });

    form.on("submit", (e) => {
        e.preventDefault();
        loading(true);
        let formData = new FormData(form[0]);
        $.ajax({
            url: form.attr("action"),
            method: form.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.default) {
                    loading(false);
                    handleValidationErrors(response);
                } else if (response.success) {
                    form.trigger("reset");
                    form.find("input[type=text]").blur();
                    handleValidationErrors(response);
                    if (settings.name.text() != response.data.name) {
                        settings.name.text(response.data.name);
                    }
                    if (settings.avatar.html() != response.data.avatar) {
                        settings.avatar.html(response.data.avatar);
                    }
                    loading(false);
                }
            },
            error: function (error) {
                loading(false);
                if (error.status === 422) {
                    handleValidationErrors(error.responseJSON.errors);
                }
            },
        });
    });

    function handleValidationErrors(errors) {
        displayError(avatarLabel, errors.avatar);
        displayError(nameLabel, errors.name);
    }

    function displayError(label, errorText) {
        if (errorText) {
            label.text(errorText[0]);
            label.addClass("error");
        } else {
            label.text(label.attr("for"));
            label.removeClass("error");
        }
    }
}

// ---- ------ -- --- -------- ----- -------
// This Method Is For Password Reset Request
// ---- ------ -- --- -------- ----- -------

export function passwordReset() {
    const reset = $("form#passwordReset");
    reset.on("click", (e) => {
        e.preventDefault();
        loading(true);
        $.ajax({
            url: reset.attr("action"),
            method: reset.attr("method"),
            data: reset.serialize(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    loading(false);
                    notify(
                        "check your email",
                        "a password reset link has been sent to your email"
                    );
                }
            },
            error: function (error) {
                loading(false);
            },
        });
    });
}

// ---- ------ -- --- -------- ------ ---- --------
// This Method Is For Deleting Device From Settings
// ---- ------ -- --- -------- ------ ---- --------

export function deleteDevice() {
    const devices = $(".settingsParent .static.devices").has("i.fa-trash");
    devices.each(function () {
        $(this).on("click", function () {
            let device = $(this);
            let url = device.find("input[name=device-link]").val();
            loading(true);
            $.ajax({
                url: url,
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        loading(false);
                        deleteDeviceAnimation(device);
                    }
                },
                error: function (error) {
                    loading(false);
                },
            });
        });
    });
}

// ---- ------ -- -- --------- --- -------- - -------
// This Method Is An Animation For Deleting A Device
// ---- ------ -- -- --------- --- -------- - -------

function deleteDeviceAnimation(device) {
    device.animate(
        {
            height: 0,
            opacity: 0,
            scale: 0,
        },
        200
    );
    setTimeout(() => {
        device.remove();
    }, 300);
}

// ---- ------ -- --- ------- -----------
// This Method Is For Account Termination
// ---- ------ -- --- ------- -----------

export function deleteAccount() {
    const deleteAccount = $("form.delete-account-form");
    deleteAccount.on("click", (e) => {
        e.preventDefault();
        loading(true);
        $.ajax({
            url: deleteAccount.attr("action"),
            method: deleteAccount.attr("method"),
            data: deleteAccount.serialize(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    loading(false);
                    notify(
                        "check your email",
                        "an account termination link has been sent to your email"
                    );
                }
            },
            error: function (error) {
                loading(false);
                if (error.status === 429) {
                    notify("too many requests", "try again after a while");
                }
            },
        });
    });
}

// ---- ------ -- --- ----------
// This Method Is For Lockscreen
// ---- ------ -- --- ----------

export function lockscreen() {
    const lockscreen = $("form.lockscreen-form");
    lockscreen.on("click", (e) => {
        e.preventDefault();
        loading(true);
        $.ajax({
            url: lockscreen.attr("action"),
            method: lockscreen.attr("method"),
            data: lockscreen.serialize(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.lockscreen) {
                    loading(false);
                    location.reload();
                }
            },
            error: function (error) {
                loading(false);
                location.reload();
            },
        });
    });
}
