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
    name: {
        input: $("#name"),
        label: $("label[for=name]"),
    },
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

focus(form.name.input, form.name.label);
focus(form.email.input, form.email.label);
focus(form.password.input, form.password.label);
eye(form.password.input, form.password.icon);
form.name.input.focus();

function register() {
    const form = $("#form");
    const nameLabel = form.find("label[for=name]");
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
                if (response['success']) {
                    loading(false);
                    const fieldsToClear = [nameLabel, emailLabel, passwordLabel];
                    fieldsToClear.forEach(label => {
                        const input = label.siblings("input");
                        input.val("");
                        label.removeClass("error");
                        label.text(label.attr("for"));
                        focus(input, label);
                    });
                    nameLabel.siblings("input").focus();
                    notify('Registration Success', 'Check Your Email To Confirm Your Email');
                }
            },
            error: function (error) {
                loading(false);
                if (error.status === 422) {
                    handleValidationErrors(error.responseJSON.errors);
                }
            }
        });
    });

    function handleValidationErrors(errors) {
        displayError(nameLabel, errors.name);
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

register();
