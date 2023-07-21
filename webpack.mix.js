const mix = require('laravel-mix');

// --- ------ ----
// Set Public Path
// --- ------ ----

mix.setPublicPath('public');

// ---- -----
// SCSS Files
// ---- -----

mix.sass('resources/scss/login.scss', 'css/login.css');
mix.sass('resources/scss/register.scss', 'css/register.css');
mix.sass('resources/scss/password-reset.scss', 'css/password-reset.css');
mix.sass('resources/scss/lockscreen.scss', 'css/lockscreen.css');

// -- -----
// JS Files
// -- -----

mix.js('resources/js/login.js', 'js/login.js');
mix.js('resources/js/register.js', 'js/register.js');
mix.js('resources/js/password-reset.js', 'js/password-reset.js');
mix.js('resources/js/lockscreen.js', 'js/lockscreen.js');

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