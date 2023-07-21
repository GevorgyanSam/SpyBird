const mix = require('laravel-mix');

// --- ------ ----
// Set Public Path
// --- ------ ----

mix.setPublicPath('public');

// ---- -----
// SCSS Files
// ---- -----

mix.sass('resources/scss/users/style.scss', 'css/users/style.css');

// -- -----
// JS Files
// -- -----

mix.js('resources/js/users/login.js', 'js/users/login.js');
mix.js('resources/js/users/register.js', 'js/users/register.js');
mix.js('resources/js/users/password-reset.js', 'js/users/password-reset.js');
mix.js('resources/js/users/lockscreen.js', 'js/users/lockscreen.js');
mix.js('resources/js/users/token.js', 'js/users/token.js');

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