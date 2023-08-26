// ------ ---- ------- ---- -----------
// Import Form Methods From Components.
// ------ ---- ------- ---- -----------
import { focus } from "../components/form-functions";

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
        actions: $(".navParent li:not(.mode)"),
        content: $(".asideParent > div"),
    };

    const setActivePage = (pageId) => {
        const btn = $(`#${pageId}`);
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
        setActivePage($(this).attr("id"));
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
