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
                setFriends(response.data);
            } else if (response.empty) {
                clearFriends();
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
    const parent = $(".friendsParent .personParent");
    parent.empty();
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
    parent.html(content);
    toggleDropdown();
}

// ---- ------ -- --- -------- --- ------- ----
// This Method Is For Clearing The Friends Page
// ---- ------ -- --- -------- --- ------- ----

function clearFriends() {
    const parent = $(".friendsParent .personParent");
    parent.empty();
}
