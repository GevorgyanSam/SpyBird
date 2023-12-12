// ------ ------- ------ ---- -----------
// Import Loading Method From Components.
// ------ ------- ------ ---- -----------
import loading from "../../components/loading";
// ------ ------ -------- --- --- ---- ------- ------ ---- -------
// Import Toggle Dropdown And Get Page Content Method From Script.
// ------ ------ -------- --- --- ---- ------- ------ ---- -------
import { toggleDropdown, getContent } from "../script";

// ---- ------ -- --- ------- -------
// This Method Is For Getting Friends
// ---- ------ -- --- ------- -------

export function getFriends() {
    loading(true);
    $.ajax({
        url: "/get-friends",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.data) {
                showFriends();
                setFriends(response.data);
            } else if (response.empty) {
                hideFriends();
            }
            loading(false);
        },
        error: function (error) {
            location.reload();
            loading(false);
        },
    });
}

// ---- ------ -- --- ------- -------
// This Method Is For Setting Friends
// ---- ------ -- --- ------- -------

function setFriends(data) {
    sessionStorage.setItem("friends", JSON.stringify(data));
    const parent = $(".friendsParent .personParent");
    parent.empty();
    let content = transformFriendsDataToHtml(data);
    parent.html(content);
    toggleDropdown();
}

// ---- ------ -- ------------ ---- -- ----
// This Method Is Transforming Data To Html
// ---- ------ -- ------------ ---- -- ----

function transformFriendsDataToHtml(data) {
    let content = "";
    data.forEach((user) => {
        let avatar = user.avatar
            ? `<img src="${user.avatar}"></img>`
            : user.name[0];
        let active = user.status ? "active" : null;
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
                <h4>${user.name}</h4>
                <div class="status">${status}</div>
            </div>
            <div class="personSettings">
                <i class="fa-solid fa-ellipsis-vertical"></i>
                <div class="dropdownMenu" data-user-id="${user.id}"></div>
            </div>
        </div>
        `;
    });
    return content;
}

// ---- ------ -- --- -------- --- ------- ----
// This Method Is For Clearing The Friends List
// ---- ------ -- --- -------- --- ------- ----

function clearFriends() {
    const parent = $(".friendsParent .personParent");
    parent.empty();
}

// ---- ------ -- --- ------ --- ------- ----
// This Method Is For Hiding The Friends Page
// ---- ------ -- --- ------ --- ------- ----

function hideFriends() {
    const parent = $(".friendsParent");
    const empty = parent.find("> div.emptyParent");
    const content = parent.find("> div:not(.emptyParent)");
    content.hide();
    empty.addClass("active");
}

// ---- ------ -- --- ------- --- ------- ----
// This Method Is For Showing The Friends Page
// ---- ------ -- --- ------- --- ------- ----

function showFriends() {
    const parent = $(".friendsParent");
    const empty = parent.find("> div.emptyParent");
    const content = parent.find("> div:not(.emptyParent)");
    empty.removeClass("active");
    content.show();
}

// ---- ------ -- --- --------- --------
// This Method Is For Searching Contacts
// ---- ------ -- --- --------- --------

export function searchFriends() {
    const search = $("form#searchFriends");
    const inp = search.find("input[name=search]");

    search.on("submit", (e) => {
        e.preventDefault();
    });

    inp.on("blur", () => {
        if (!inp.val()) {
            getContent("friends");
        }
    });

    inp.on("input", (e) => {
        e.preventDefault();

        if (!e.target.value.length) {
            clearFriends();
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
                    setFriends(response.data);
                } else if (response.empty) {
                    clearFriends();
                }
            },
            error: function (error) {
                location.reload();
            },
        });
    });
}

// ---- ------ -- --- ------- --- -------
// This Method Is For Getting New Friends
// ---- ------ -- --- ------- --- -------

export function getNewFriends() {
    let page = sessionStorage.getItem("current-page");
    let input = $("form#searchFriends input[name=search]");
    if (page !== "friends" || input.val() || input.is(":focus")) {
        return false;
    }
    $.ajax({
        url: "/get-friends",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.data) {
                showFriends();
                setNewFriends(response.data);
            } else if (response.empty) {
                hideFriends();
            }
        },
        error: function (error) {
            location.reload();
        },
    });
}

// ---- ------ -- --- ------- --- -------
// This Method Is For Setting New Friends
// ---- ------ -- --- ------- --- -------

function setNewFriends(data) {
    let oldData = sessionStorage.getItem("friends");
    let newData = JSON.stringify(data);
    if (oldData !== newData) {
        sessionStorage.setItem("friends", newData);
        let parent = $(".friendsParent .personParent");
        let content = transformFriendsDataToHtml(data);
        parent.html(content);
        toggleDropdown();
    }
}

// ---- ------ -- -- --------- --- -------- ---- -------
// This Method Is An Animation For Removing From Friends
// ---- ------ -- -- --------- --- -------- ---- -------

export function removeFromFriendsAnimation(friend) {
    let page = sessionStorage.getItem("current-page");
    if (page == "friends") {
        friend.animate(
            {
                height: 0,
                opacity: 0,
                scale: 0,
            },
            200
        );
        setTimeout(() => {
            friend.remove();
            getNewFriends();
        }, 300);
    }
}
