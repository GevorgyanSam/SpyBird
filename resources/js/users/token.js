// ------ ---- ------- ---- -----------
// Import Form Methods From Components.
// ------ ---- ------- ---- -----------
import { focus, eye } from "../components/form-functions";
// ------ ------- ------ ---- -----------
// Import Loading Method From Components.
// ------ ------- ------ ---- -----------
import loading from "../components/loading";

const form = {
    password: {
        input: $("#password"),
        label: $("label[for=password]"),
        icon: $("#eye"),
    },
    password_confirmation: {
        input: $("#password_confirmation"),
        label: $("label[for=password_confirmation]"),
        icon: $("#eye_confirmation"),
    },
};

focus(form.password.input, form.password.label);
eye(form.password.input, form.password.icon);
focus(form.password_confirmation.input, form.password_confirmation.label);
eye(form.password_confirmation.input, form.password_confirmation.icon);
form.password.input.focus();

// ---- ------ -- --- -------- ------
// This Method Is For Password Update
// ---- ------ -- --- -------- ------

function token() {
    const form = $("#form");
    const passwordLabel = form.find("label[for=password]");
    const passwordConfirmationLabel = form.find(
        "label[for=password_confirmation]"
    );

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
                } else {
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
        displayError(passwordLabel, errors.password);
        displayError(passwordConfirmationLabel, errors.password_confirmation);
    }

    function displayError(label, errorText) {
        if (errorText) {
            label.text(errorText[0]);
            label.addClass("error");
        } else {
            if (label.attr("for") == "password_confirmation") {
                label.text("password confirmation");
                label.removeClass("error");
            } else {
                label.text(label.attr("for"));
                label.removeClass("error");
            }
        }
    }
}

token();
