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
    //mix.scripts(['plugins/*.js', 'controllers/*.js', 'factories/*.js', 'directives/*.js'], 'public/js/all.js');
    //mix.scripts('factories/*.js', 'public/js/factories.js');

    mix.scripts('resources/assets/js/app.js', 'public/js/app.js');
    mix.stylesIn('resources/assets/css', 'public/css/plugins.css');
    mix.scriptsIn('resources/assets/js/controllers', 'public/js/controllers.js');
    mix.scriptsIn('resources/assets/js/factories', 'public/js/factories.js');
    mix.scriptsIn('resources/assets/js/directives', 'public/js/directives.js');
    mix.scriptsIn('resources/assets/js/plugins', 'public/js/plugins.js');
});