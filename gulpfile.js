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

    //mix.scripts([
    //    'plugins/*.js',
    //    'app.js',
    //    'controllers/*.js',
    //    'filters/*.js',
    //    'factories/*.js',
    //    'directives/*.js'
    //], 'public/js/all.js');

    mix.scripts([
        'plugins/*.js',
        'shared/*.js',
        'accounts/*.js',
        'budgets/*.js',
        'help/*.js',
        'home/*.js',
        'preferences/*.js'
    ], 'public/js/all.js');

    mix.stylesIn('resources/assets/css', 'public/css/plugins.css');

    //mix.scripts('resources/assets/js/app.js', 'public/js/app.js');
    //mix.scriptsIn('resources/assets/js/filters', 'public/js/filters.js');
    //mix.scriptsIn('resources/assets/js/controllers', 'public/js/controllers.js');
    //mix.scriptsIn('resources/assets/js/factories', 'public/js/factories.js');
    //mix.scriptsIn('resources/assets/js/directives', 'public/js/directives.js');
    //mix.scriptsIn('resources/assets/js/plugins', 'public/js/plugins.js');
});