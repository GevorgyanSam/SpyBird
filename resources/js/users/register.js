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