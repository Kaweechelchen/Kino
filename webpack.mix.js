let mix = require('laravel-mix');
let webpack = require('webpack');

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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .version()
   .disableNotifications();

mix.copy('resources/assets/img/', 'public/img/', false);

mix.webpackConfig({
  plugins: [
   // reduce bundle size by ignoring moment js local files
    new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/)
  ]
});
