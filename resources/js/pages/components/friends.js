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
            console.log(response);
            if (response.data) {
                // setSearchContacts(response.data);
            } else if (response.empty) {
                // clearSearchContacts();
            }
            loading(false);
        },
        error: function (error) {
            console.log(error);
            // location.reload();
            loading(false);
        },
    });
}