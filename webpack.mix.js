const mix = require('laravel-mix');
const $ = require('jquery');

// --- ------ ----
// Set Public Path
// --- ------ ----

mix.setPublicPath('public');

// ---- -----
// SCSS Files
// ---- -----

mix.sass('resources/scss/users/style.scss', 'css/users/style.css');
mix.sass('resources/scss/emails/email.scss', 'css/emails/email.css');
mix.sass('resources/scss/privacy/privacy.scss', 'css/privacy/privacy.css');
mix.sass('resources/scss/pages/style.scss', 'css/pages/style.css');
mix.sass('resources/scss/pages/room.scss', 'css/pages/room.css');

// -- -----
// JS Files
// -- -----

mix.js(['resources/js/components/mode.js', 'resources/js/users/login.js'], 'js/users/login.js');
mix.js(['resources/js/components/mode.js', 'resources/js/users/register.js'], 'js/users/register.js');
mix.js(['resources/js/components/mode.js', 'resources/js/users/password-reset.js'], 'js/users/password-reset.js');
mix.js(['resources/js/components/mode.js', 'resources/js/users/lockscreen.js'], 'js/users/lockscreen.js');
mix.js(['resources/js/components/mode.js', 'resources/js/users/token.js'], 'js/users/token.js');
mix.js(['resources/js/components/mode.js', 'resources/js/users/two-factor.js'], 'js/users/two-factor.js');
mix.js(['resources/js/components/mode.js', 'resources/js/users/lost-email.js'], 'js/users/lost-email.js');
mix.js(['resources/js/components/mode.js', 'resources/js/privacy/privacy.js'], 'js/privacy/privacy.js');
mix.js(['resources/js/components/mode.js', 'resources/js/pages/script.js'], 'js/pages/script.js');
mix.js(['resources/js/components/mode.js', 'resources/js/pages/script.js', 'resources/js/pages/room.js'], 'js/pages/room.js');

// ---- ------ ------
// Copy Assets Folder
// ---- ------ ------

mix.copyDirectory('resources/assets', 'public/assets');

// --- ------- ------ -------
// Mix Options (Babel Config)
// --- ------- ------ -------

mix.options({
    babelConfig: {
        presets: ['@babel/preset-env']
    }
})

// --- -------- --------
// Mix Autoload (JQuery)
// --- -------- --------

mix.autoload({
    jquery: ['$', 'window.jQuery'],
});