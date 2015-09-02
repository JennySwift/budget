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

//elixir(function(mix) {
//    mix.scripts([
//        'resources/assets/js/plugins/angular.min.js',
//        'resources/assets/js/plugins/jquery.js',
//        'resources/assets/js/plugins/underscore-min.js',
//        'resources/assets/js/plugins/tooltipster.min.js',
//        'resources/assets/js/plugins/bootstrap.min.js',
//        'resources/assets/js/plugins/date-en-AU.js',
//        'resources/assets/js/plugins/time.js',
//        'resources/assets/js/plugins/moment.js',
//        'resources/assets/js/plugins/checklist-model.js',
//        'resources/assets/js/plugins/angular-animate.min.js'
//    ], 'public/js/plugins.js');
//});

elixir(function(mix) {
    mix.sass('style.scss');
    //mix.scripts(['plugins/*.js', 'controllers/*.js', 'factories/*.js', 'directives/*.js'], 'public/js/all.js');
    //mix.scripts('factories/*.js', 'public/js/factories.js');

    mix.stylesIn('resources/assets/css', 'public/css/plugins.css');
    mix.scriptsIn('resources/assets/js/controllers', 'public/js/controllers.js');
    mix.scriptsIn('resources/assets/js/factories', 'public/js/factories.js');
    mix.scriptsIn('resources/assets/js/directives', 'public/js/directives.js');
    mix.scriptsIn('resources/assets/js/plugins', 'public/js/plugins.js');
});

//elixir(function(mix) {
//    mix.scriptsIn('resources/assets/js/controllers', 'public/js/controllers.js')
//        .scriptsIn('resources/assets/js/factories', 'public/js/factories.js')
//        .scriptsIn('resources/assets/js/directives', 'public/js/directives.js')
//        .scriptsIn('resources/assets/js/plugins', 'public/js/plugins.js')
//});
