// ------ ------ ---- -----------
// Import JQuery From Components.
// ------ ------ ---- -----------
import $ from './components/jquery';

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

// ---- ------ -- --- ------ ------ -- ----- -----
// This Method Is For Moving Labels In Input Focus
// ---- ------ -- --- ------ ------ -- ----- -----

function focus (input, label)
{
    input.focus(function () {

        if (input.val() == 0)
        {
            label.addClass('focus');
        }

    })

    input.blur(function () {

        if (input.val() == 0)
        {
            label.removeClass('focus');
        }

    })
}

// ---- ------ -- --- -------- --- ----- -------- ----
// This Method Is For Changing The Input Password Type
// ---- ------ -- --- -------- --- ----- -------- ----

function eye (input, icon)
{
    input.keyup(function () {

        if (input.val().length > 0)
        {
            icon.css('display', 'block')
        }
        else if (input.val() == 0)
        {
            icon.css('display', 'none');
        }

    })

    icon.click(function () {

        if (icon.hasClass("fa-eye-slash"))
        {
            icon.removeClass("fa-eye-slash");
            icon.addClass("fa-eye");
            icon.css({
                right: "1px",
            });
            input.attr('type', 'text');
            input.focus();
        }
        else if (icon.hasClass("fa-eye"))
        {
            icon.removeClass("fa-eye");
            icon.addClass("fa-eye-slash");
            icon.css({
                right: "0",
            });
            input.attr('type', 'password');
            input.focus();
        }

    })
}

focus(form.email.input, form.email.label);
focus(form.password.input, form.password.label);
eye(form.password.input, form.password.icon);