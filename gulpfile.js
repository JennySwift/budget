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
});

elixir(function(mix) {
    mix.scriptsIn('resources/assets/js/controllers', 'public/js/controllers.js')
        .scriptsIn('resources/assets/js/factories', 'public/js/factories.js')
        .scriptsIn('resources/assets/js/directives', 'public/js/directives.js');
});
