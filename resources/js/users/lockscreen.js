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
};

focus(form.password.input, form.password.label);
eye(form.password.input, form.password.icon);
form.password.input.focus();

// ---- ------ -- --- -------- -------------- ----- - -------
// This Method Is For Checking Authentication Every 3 Seconds
// ---- ------ -- --- -------- -------------- ----- - -------

function checkAuthentication() {
    $.ajax({
        url: "/check-authentication",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.reload) {
                location.reload();
            }
        },
        error: function (error) {
            if (error) {
                location.reload();
            }
        },
    });
}

checkAuthentication();
setInterval(checkAuthentication, 3000);

// ---- ------ -- --- ----------
// This Method Is For Lockscreen
// ---- ------ -- --- ----------

function lockscreen() {
    const form = $("#form");
    const passwordLabel = form.find("label[for=password]");

    form.on("submit", (e) => {
        e.preventDefault();
        loading(true);
        $.ajax({
            url: form.attr("action"),
            method: form.attr("method"),
            data: form.serialize(),
            success: function (response) {
                if (response.success) {
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
                } else if (error.status === 429) {
                    //
                } else {
                    location.reload();
                }
            },
        });
    });

    function handleValidationErrors(errors) {
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

lockscreen();
