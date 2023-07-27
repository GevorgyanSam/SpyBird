// ------ ---- ------- ---- -----------
// Import Form Methods From Components.
// ------ ---- ------- ---- -----------
import { focus, eye } from '../components/form-functions';

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