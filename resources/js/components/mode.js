// ---- ------ ------ --- ------- ----
// This Method Checks The Website Mode
// ---- ------ ------ --- ------- ----

function checkMode ()
{
    const mode = {
        storage: localStorage.getItem('mode'),
        btn: $('.mode'),
        icon: $('.mode i')
    }

    if (mode.storage == 'light')
    {
        $('body').addClass('light');
        mode.icon.removeClass('fa-moon');
        mode.icon.addClass('fa-sun');
    }
}

checkMode();