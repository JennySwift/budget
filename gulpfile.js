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
//    mix.less('app.less');
//});

//elixir(function(mix) {
//    mix.scripts([
//        'controllers/_BaseController.js',
//        'controllers/HomeController.js',
//        'controllers/AccountsController.js',
//        'controllers/BudgetsController.js',
//        'controllers/FilterController.js',
//        'controllers/TransactionsController.js',
//        'controllers/NewTransactionController.js',
//        'controllers/PreferencesController.js',
//        'factories/AutocompleteFactory.js',
//        'factories/BudgetsFactory.js',
//        'factories/SavingsFactory.js',
//        'factories/ColorsFactory.js',
//        'factories/TransactionsFactory.js',
//        'factories/PreferencesFactory.js',
//        'factories/TagsFactory.js',
//        'factories/AccountsFactory.js',
//        'factories/FilterFactory.js',
//        'factories/FeedbackFactory.js',
//        'directives/DropdownsDirective.js',
//        'directives/CheckboxesDirective.js',
//        'directives/TotalsDirective.js',
//        'directives/FilterDirective.js',
//        'directives/TagAutocompleteDirective.js',
//        'directives/FilterDropdownsDirective.js',
//        'directives/TransactionAutocompleteDirective.js',
//    ]);
//});

//elixir(function(mix) {
//    mix.scripts(['BaseController.js']);
//});

elixir(function(mix) {
    mix.scriptsIn('resources/assets/js/controllers', 'public/js/controllers.js')
        .scriptsIn('resources/assets/js/factories', 'public/js/factories.js')
        .scriptsIn('resources/assets/js/directives', 'public/js/directives.js');
});
