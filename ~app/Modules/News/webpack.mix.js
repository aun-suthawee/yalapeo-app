const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public/Modules/News').mergeManifest();

mix.sass( __dirname + '/Resources/assets/sass/app.scss', 'css/app.css');

if (mix.inProduction()) {
    mix.version();
}