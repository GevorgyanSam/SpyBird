// ---- ------ -- --- -------- --- ----- ----
// This Method Is For Changing App Color Mode
// ---- ------ -- --- -------- --- ----- ----

function changeColorMode() {
    const mode = {
        btn: $(".mode"),
        icon: $(".mode i"),
    };

    mode.btn.click(function () {
        let storage = localStorage.getItem("mode");
        if (storage == "light") {
            $("body").removeClass("light");
            localStorage.removeItem("mode");
            mode.icon.removeClass("fa-sun");
            mode.icon.addClass("fa-moon");
        } else {
            $("body").addClass("light");
            localStorage.setItem("mode", "light");
            mode.icon.removeClass("fa-moon");
            mode.icon.addClass("fa-sun");
        }
    });
}

changeColorMode();

// ---- ------ -- --- -------- --- --- ----- -------
// This Method Is For Changing The App Aside Content
// ---- ------ -- --- -------- --- --- ----- -------

function changePages() {
    const pages = {
        actions: $(".navParent li:not(.mode)"),
        content: $(".asideParent > div"),
    };

    let current = sessionStorage.getItem("current-page");
    if (current != undefined) {
        let btn = $(`.navParent li#${current}`);
        pages.actions.removeClass("active");
        btn.addClass("active");
        let content = $(`.${btn.attr("data-content")}`);
        pages.content.removeClass("active");
        content.addClass("active");
    }

    pages.actions.click(function () {
        let current = $(this).attr("id");
        sessionStorage.setItem("current-page", current);
        pages.actions.removeClass("active");
        $(this).addClass("active");
        let content = $(`.${$(this).attr("data-content")}`);
        pages.content.removeClass("active");
        content.addClass("active");
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
        menu: $(".person .dropdownMenu"),
        btn: $(".person .personSettings"),
        item: $(".person .dropdownItem"),
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
            !target.hasClass("line") &&
            !target.hasClass("dropdownMenu")
        ) {
            closeDropdownMenu();
        }
    });
}

toggleDropdown();