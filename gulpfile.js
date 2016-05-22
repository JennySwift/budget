process.env.DISABLE_NOTIFIER = true;
var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('style.scss');

    mix.scripts([
        'plugins/*.js',
        'config.js',
        'shared/**/*.js',
        'repositories/**/*.js',
        'components/**/*.js',
        'directives/**/*.js',
        'app.js',
    ], 'public/js/all.js');

    mix.version(["css/style.css", "js/all.js"]);
    mix.stylesIn('resources/assets/css', 'public/css/plugins.css');
});

