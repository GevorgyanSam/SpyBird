// ---- ------ ------ --- ------- ----
// This Method Checks The Website Mode
// ---- ------ ------ --- ------- ----

function checkMode ()
{
    let mode = localStorage.getItem('mode');
    if (mode == 'light')
    {
        document.body.classList.add('light')
    }
}

checkMode();