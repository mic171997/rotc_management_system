/** @format */

const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js').vue();

mix.sass('resources/sass/app.scss', 'public/css');

if (mix.inProduction()) {
    mix.version();
}
