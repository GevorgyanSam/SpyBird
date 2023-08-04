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

// ---- ------ -- --- -------- --- --- ----- -------
// This Method Is For Changing The App Aside Content
// ---- ------ -- --- -------- --- --- ----- -------

function changePages ()
{

    const pages = {
        actions: $('.navParent li:not(.mode)'),
    }

    pages.actions.click(function () {
        pages.actions.removeClass('active');
        $(this).addClass('active');
    })

}

changePages();