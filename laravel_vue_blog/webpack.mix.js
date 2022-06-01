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

mix.js('resources/js/app.js', 'public/js').vue()
mix.sass('resources/sass/app.scss', 'public/css');

mix.styles([
   // 'public/css/bootstrap.min.css',
    'public/css/style.css',
  //  'public/css/layout-dark.css',
   // 'public/css/layout-rtl.css',

], 'public/css/app.css');
/* mix.scripts([
       'public/js/pcoded.min.js',
      'public/js/vendor-all.min.js',
      'public/js/bootstrap.min.js',
  //     'public/assets/js/bootstrap.bundle.min.js',
  //     'public/assets/js/jquery.hoverIntent.min.js',
  //     'public/assets/js/jquery.waypoints.min.js',
  //     'public/assets/js/superfish.min.js',
  //     'public/assets/js/owl.carousel.min.js',
  //     'public/assets/js/bootstrap-input-spinner.js',
  //     'public/assets/js/jquery.elevateZoom.min.js',
  //     'public/assets/js/jquery.magnific-popup.min.js',
  //     // 'public/assets/js/jquery.plugin.min.js',
  //     'public/assets/js/main.js'
  ], 'public/js/temp.js').version();
 */
/*  mix.combine(['resources/js/external js/bootstrap.min.js',
  'resources/js/external js/pcoded.js',
  'resources/js/external js/vendor-all.min.js'
], 'public/js/backend.js');  */

mix.version();
