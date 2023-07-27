// ---- ------ ------ --- ------- ----
// This Method Checks The Website Mode
// ---- ------ ------ --- ------- ----

function checkMode ()
{
    let mode = localStorage.getItem('mode');
    if (mode == 'light')
    {
        $('body').addClass('light');
    }
}

checkMode();