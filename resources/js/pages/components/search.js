// ------ ------- ------ ---- -----------
// Import Loading Method From Components.
// ------ ------- ------ ---- -----------
import loading from "../../components/loading";
// ------ ------ -------- --- --- ---- ------- ------ ---- -------
// Import Toggle Dropdown And Get Page Content Method From Script.
// ------ ------ -------- --- --- ---- ------- ------ ---- -------
import { toggleDropdown, getContent } from "../script";

// ---- ------ -- --- -------- --- ------ ---- --------
// This Method Is For Clearing The Search Page Contacts
// ---- ------ -- --- -------- --- ------ ---- --------

function clearSearchContacts() {
    const parent = $(".searchParent .personParent");
    parent.empty();
}

// ---- ------ -- --- ------- --------- --------
// This Method Is For Getting Suggested Contacts
// ---- ------ -- --- ------- --------- --------

export function getSuggestedContacts() {
    loading(true);
    $.ajax({
        url: "/get-suggested-contacts",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.data) {
                setSearchContacts(response.data);
            } else if (response.empty) {
                clearSearchContacts();
            }
            loading(false);
        },
        error: function (error) {
            location.reload();
            loading(false);
        },
    });
}

// ---- ------ -- --- ------- ------ --------
// This Method Is For Getting Nearby Contacts
// ---- ------ -- --- ------- ------ --------

export function getNearbyContacts() {
    loading(true);
    $.ajax({
        url: "/get-nearby-contacts",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.data) {
                setSearchContacts(response.data);
            } else if (response.empty) {
                clearSearchContacts();
            }
            loading(false);
        },
        error: function (error) {
            location.reload();
            loading(false);
        },
    });
}

// ---- ------ -- --- ------- ------ --------
// This Method Is For Setting Search Contacts
// ---- ------ -- --- ------- ------ --------

function setSearchContacts(data) {
    const parent = $(".searchParent .personParent");
    parent.empty();
    let content = "";
    data.forEach((user) => {
        let avatar = user.avatar
            ? `<img src="${user.avatar}"></img>`
            : user.name[0];
        let active = user.status ? "active" : null;
        let name = $("<div/>").text(user.name).html();
        let updated_at = user.updated_at;
        let status = user.hidden
            ? "hidden status"
            : active
            ? "online"
            : updated_at;
        content += `
        <div class="person">
            <div>
                <div class="avatar ${active}">
                    ${avatar}
                </div>
            </div>
            <div class="personInfo">
                <h4>${name}</h4>
                <div class="status">${status}</div>
            </div>
            <div class="personSettings">
                <i class="fa-solid fa-ellipsis-vertical"></i>
                <div class="dropdownMenu" data-user-id="${user.id}"></div>
            </div>
        </div>
        `;
    });
    parent.html(content);
    toggleDropdown();
}

// ---- ------ -- --- -------- --- ------ -------
// This Method Is For Toggling App Search Content
// ---- ------ -- --- -------- --- ------ -------

export function switchSearch() {
    const switches = $(".switchParent > div");

    switches.click(function () {
        if (!$(this).hasClass("active")) {
            switches.removeClass("active");
            $(this).addClass("active");
            let name = $(this).data("name");
            if (name === "familiar") {
                getSuggestedContacts();
            } else if (name === "nearby") {
                getNearbyContacts();
            }
        }
    });
}

// ---- ------ -- --- --------- --------
// This Method Is For Searching Contacts
// ---- ------ -- --- --------- --------

export function searchContacts() {
    const search = $("form#searchContacts");
    const inp = search.find("input[name=search]");
    const switchParent = $(".searchParent .switchParent");

    search.on("submit", (e) => {
        e.preventDefault();
    });

    inp.on("blur", () => {
        if (!inp.val() && switchParent.css("display") == "none") {
            switchParent.css("display", "flex");
            getContent("search");
        }
    });

    inp.on("input", (e) => {
        e.preventDefault();
        switchParent.css("display", "none");

        if (!e.target.value.length) {
            clearSearchContacts();
            return false;
        }

        $.ajax({
            url: search.attr("action"),
            method: search.attr("method"),
            data: search.serialize(),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.data) {
                    setSearchContacts(response.data);
                } else if (response.empty) {
                    clearSearchContacts();
                }
            },
            error: function (error) {
                location.reload();
            },
        });
    });
}
