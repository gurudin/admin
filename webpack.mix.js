let mix = require('laravel-mix');

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

mix.options({
    uglify: {
      uglifyOptions: {
        compress: {
          drop_console: true,
        }
      }
    }
  })
  .setPublicPath('public')
  .js('resources/assets/js/admin.js', 'public/js')
  .sass('resources/assets/sass/admin.scss', 'public/css')
  .copy('resources/assets/fonts', 'public/fonts')
  .sourceMaps()
  .version();
