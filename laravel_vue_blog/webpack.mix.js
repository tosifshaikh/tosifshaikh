const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css').version();
mix.styles([
   // 'public/css/bootstrap.min.css',
    'public/css/style.css',
  //  'public/css/layout-dark.css',
   // 'public/css/layout-rtl.css',

], 'public/css/app.css');

/* mix.combine(['resources/js/external js/bootstrap.min.js',
  'resources/js/external js/pcoded.js',
  'resources/js/external js/vendor-all.min.js'
], 'public/js/backend.js'); */
