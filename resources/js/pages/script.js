// ---- ------ -- --- -------- --- ----- ----
// This Method Is For Changing App Color Mode
// ---- ------ -- --- -------- --- ----- ----

function changeColorMode ()
{

    const mode = {
        btn: $('.mode'),
        icon: $('.mode i')
    }

    mode.btn.click(function () {

        let storage = localStorage.getItem('mode');
        if (storage == 'light')
        {
            $('body').removeClass('light');
            localStorage.removeItem('mode');
            mode.icon.removeClass('fa-sun');
            mode.icon.addClass('fa-moon');
        }
        else
        {
            $('body').addClass('light');
            localStorage.setItem('mode', 'light');
            mode.icon.removeClass('fa-moon');
            mode.icon.addClass('fa-sun');
        }

    })

}

changeColorMode();