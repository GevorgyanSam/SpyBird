// ------ ---- ------- ---- -----------
// Import Form Methods From Components.
// ------ ---- ------- ---- -----------
import { focus } from "../components/form-functions";
// ------ ------- ------ ---- -----------
// Import Loading Method From Components.
// ------ ------- ------ ---- -----------
import loading from "../components/loading";

const form = {
    code: {
        input: $("#code"),
        label: $("label[for=code]"),
    },
};

focus(form.code.input, form.code.label);
form.code.input.focus();

// ---- ------ -- --- ---
// This Method Is For 2FA
// ---- ------ -- --- ---

function twoFactorAuthentication() {
    const form = $("#form");
    const codeLabel = form.find("label[for=code]");

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
                if (error.status === 422) {
                    loading(false);
                    handleValidationErrors(error.responseJSON.errors);
                } else {
                    location.reload();
                }
            },
        });
    });

    function handleValidationErrors(errors) {
        displayError(codeLabel, errors.code);
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

twoFactorAuthentication();
