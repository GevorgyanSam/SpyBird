// ------ ------ ---- -----------
// Import JQuery From Components.
// ------ ------ ---- -----------
import $ from '../components/jquery';
// ------ ---- ------ --- -------- ------- -----
// Import Mode Method For Checking Website Mode.
// ------ ---- ------ --- -------- ------- -----
import '../components/mode';
// ------ ---- ------- ---- -----------
// Import Form Methods From Components.
// ------ ---- ------- ---- -----------
import { focus, eye } from '../components/form-functions';

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