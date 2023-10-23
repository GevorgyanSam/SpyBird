// ------ ---- ------- ---- -----------
// Import Form Methods From Components.
// ------ ---- ------- ---- -----------
import { focus } from "../components/form-functions";
// ------ ---- ------------ ---- -----------
// Import Push Notification From Components.
// ------ ---- ------------ ---- -----------
import notify from "../components/push-notifications";
// ------ ------- ------ ---- -----------
// Import Loading Method From Components.
// ------ ------- ------ ---- -----------
import loading from "../components/loading";

// ---- ------ -- --- -------- --- ----- ----
// This Method Is For Changing App Color Mode
// ---- ------ -- --- -------- --- ----- ----

function changeColorMode() {
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

changeColorMode();

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

    setActivePage(current);

    pages.actions.click(function () {
        setActivePage($(this).data("name"));
    });
}

changePages();

// ---- ------ -- --- -------- --- ------ -------
// This Method Is For Toggling App Search Content
// ---- ------ -- --- -------- --- ------ -------

function switchSearch() {
    const switches = $(".switchParent > div");

    switches.click(function () {
        switches.removeClass("active");
        $(this).addClass("active");
    });
}

switchSearch();

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

// ---- ------ -- --- ----- -- ---- ------
// This Method Is For Focus On Form Inputs
// ---- ------ -- --- ----- -- ---- ------

function focusOnInput() {
    const form = {
        name: {
            input: $("#name"),
            label: $("label[for=name]"),
        },
    };

    focus(form.name.input, form.name.label);
}

focusOnInput();

// ---- ------ -- --- -------- -------- ---------
// This Method Is For Toggling Settings Accordion
// ---- ------ -- --- -------- -------- ---------

function switchAccordion() {
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

switchAccordion();

// ---- ------ -- --- --------- -- ---- ------ -----
// This Method Is For Switching To Full Screen Mode.
// ---- ------ -- --- --------- -- ---- ------ -----

function switchFullScreen() {
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

switchFullScreen();

// ---- ------ -- --- --------- -- --- -----
// This Method Is For Switching To Spy Mode.
// ---- ------ -- --- --------- -- --- -----

function switchSpyMode() {
    const checkbox = $(".settingsParent .spy input[type=checkbox]");
    checkbox.change((e) => {
        if (e.target.checked) {
            notify("Switch To Spy Mode", "This Feature Is Under Construction");
            setTimeout(() => {
                e.target.checked = false;
            }, 1000);
        }
    });
}

switchSpyMode();

// ---- ------ -- --- --------- -- --------- -----
// This Method Is For Switching To Invisible Mode.
// ---- ------ -- --- --------- -- --------- -----

function switchInvisibleMode() {
    const checkbox = $(".settingsParent .invisible input[type=checkbox]");
    checkbox.change((e) => {
        if (e.target.checked) {
            notify("Switch To Invisible Mode", "This Feature Is Under Construction");
            setTimeout(() => {
                e.target.checked = false;
            }, 1000);
        }
    });
}

switchInvisibleMode();

// ---- ------ -- --- --------- -------- -------
// This Method Is For Switching Activity Status.
// ---- ------ -- --- --------- -------- -------

function switchActivityStatus() {
    const checkbox = $(".settingsParent .activity input[type=checkbox]");
    checkbox.change((e) => {
        if (e.target.checked) {
            notify("Toggle Activity Status", "This Feature Is Under Construction");
            setTimeout(() => {
                e.target.checked = false;
            }, 1000);
        }
    });
}

switchActivityStatus();

// ---- ------ -- --- --- ---- ------------
// This Method Is For Two Step Verification
// ---- ------ -- --- --- ---- ------------

function switchTwoStepVerification() {
    const checkbox = $(".settingsParent .verification input[type=checkbox]");
    checkbox.change((e) => {
        if (e.target.checked) {
            notify(
                "Two Step Verification",
                "This Feature Is Under Construction"
            );
            setTimeout(() => {
                e.target.checked = false;
            }, 1000);
        }
    });
}

switchTwoStepVerification();

// ---- ------ -- --- ------
// This Method Is For Logout
// ---- ------ -- --- ------

function logout() {
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
                if (response["success"]) {
                    sessionStorage.removeItem("current-page");
                    loading(false);
                    location.reload();
                }
            },
            error: function (error) {
                loading(false);
                console.log(error);
            },
        });
    });
}

logout();

// ---- ------ -- --- ------- ---- ------- ----
// This Method Is For Editing User Profile Data
// ---- ------ -- --- ------- ---- ------- ----

function editProfile() {
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
                if (response["default"]) {
                    loading(false);
                    handleValidationErrors(response);
                } else if (response["refresh"]) {
                    location.reload();
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

editProfile();

// ---- ------ -- --- -------- ----- -------
// This Method Is For Password Reset Request
// ---- ------ -- --- -------- ----- -------

function passwordReset() {
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
                if (response["success"]) {
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

passwordReset();
