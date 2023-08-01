// ------ ---- ------- ---- -----------
// Import Form Methods From Components.
// ------ ---- ------- ---- -----------
import { focus } from '../components/form-functions';

const form = {
    code: {
        input: $("#code"),
        label: $("label[for=code]"),
    },
};

focus(form.code.input, form.code.label);