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

// -- -----
// JS Files
// -- -----

mix.js('resources/js/login.js', 'js/login.js');
mix.js('resources/js/register.js', 'js/register.js');

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