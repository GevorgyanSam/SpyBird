// ------ ---- ------- ---- -----------
// Import Form Methods From Components.
// ------ ---- ------- ---- -----------
import { focus } from '../components/form-functions';

const form = {
    email: {
        input: $("#email"),
        label: $("label[for=email]"),
    },
};

focus(form.email.input, form.email.label);