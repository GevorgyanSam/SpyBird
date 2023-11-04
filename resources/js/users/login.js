// ------ ---- ------- ---- -----------
// Import Form Methods From Components.
// ------ ---- ------- ---- -----------
import { focus, eye } from "../components/form-functions";
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
    password: {
        input: $("#password"),
        label: $("label[for=password]"),
        icon: $("#eye"),
    },
};

focus(form.email.input, form.email.label);
focus(form.password.input, form.password.label);
eye(form.password.input, form.password.icon);
form.email.input.focus();

// ---- ------ -- --- ---- -----
// This Method Is For User Login
// ---- ------ -- --- ---- -----

function login() {
    const form = $("#form");
    const emailLabel = form.find("label[for=email]");
    const passwordLabel = form.find("label[for=password]");

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
                    location.href = "/";
                } else if (response["two-factor"]) {
                    loading(false);
                    const fieldsToClear = [emailLabel, passwordLabel];
                    fieldsToClear.forEach((label) => {
                        const input = label.siblings("input");
                        input.val("");
                        label.removeClass("error");
                        label.text(label.attr("for"));
                        focus(input, label);
                    });
                    passwordLabel.nextAll("input + i").css("display", "none");
                    emailLabel.siblings("input").focus();
                    location.href = "/two-factor-authentication";
                } else {
                    location.reload();
                }
            },
            error: function (error) {
                loading(false);
                if (error.status === 422) {
                    handleValidationErrors(error.responseJSON.errors);
                } else if (error.status === 429) {
                    notify(
                        error.responseJSON.errors.title,
                        error.responseJSON.errors.body
                    );
                } else if (error.status === 419) {
                    location.reload();
                }
            },
        });
    });

    function handleValidationErrors(errors) {
        displayError(emailLabel, errors.email);
        displayError(passwordLabel, errors.password);
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

login();
