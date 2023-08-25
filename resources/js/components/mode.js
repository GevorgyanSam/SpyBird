// ---- ------ ------ --- ------- ----
// This Method Checks The Website Mode
// ---- ------ ------ --- ------- ----

function checkMode() {
    const mode = {
        storage: localStorage.getItem("mode"),
        icon: $(".mode i"),
        icon_settings: $(".accordion.theme i"),
    };

    if (mode.storage == "light") {
        $("body").addClass("light");
        mode.icon.removeClass("fa-moon");
        mode.icon.addClass("fa-sun");
        mode.icon_settings.removeClass("fa-moon");
        mode.icon_settings.addClass("fa-sun");
    }
}

checkMode();
