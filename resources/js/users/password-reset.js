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

const form = {
    email: {
        input: $("#email"),
        label: $("label[for=email]"),
    },
};

focus(form.email.input, form.email.label);
form.email.input.focus();

// ---- ------ -- --- --------- --------
// This Method Is For Resetting Password
// ---- ------ -- --- --------- --------

function reset() {
    const form = $("#form");
    const emailLabel = form.find("label[for=email]");

    form.on("submit", (e) => {
        e.preventDefault();
        loading(true);
        $.ajax({
            url: form.attr("action"),
            method: form.attr("method"),
            data: form.serialize(),
            success: function (response) {
                if (response["success"]) {
                    loading(false);
                    const input = emailLabel.siblings("input");
                    input.val("");
                    emailLabel.removeClass("error");
                    emailLabel.text(emailLabel.attr("for"));
                    notify(
                        "check your email",
                        "a password reset link has been sent to your email"
                    );
                } else {
                    location.reload();
                }
            },
            error: function (error) {
                loading(false);
                if (error.status === 422) {
                    displayError(emailLabel, error.responseJSON.errors.email);
                }
            },
        });
    });

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

reset();
