const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public/Modules/User/').mergeManifest();

mix.js(__dirname + '/Resources/assets/js/app.js', 'js/script.js')

if (mix.inProduction()) {
    mix.version();
}