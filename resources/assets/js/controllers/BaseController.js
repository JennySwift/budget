var app = angular.module('budgetApp', ['checklist-model', 'ngAnimate'], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

/*==============================dates==============================*/

$("#convert_date_button_2").on('click', function () {
    $(this).toggleClass("long_date");
    $("#my_results .date").each(function () {
        var $date = $(this).val();
        var $parse = Date.parse($date);
        var $toString;
        if ($("#convert_date_button_2").hasClass("long_date")) {
            $toString = $parse.toString('dd MMM yyyy');
        }
        else {
            $toString = $parse.toString('dd/MM/yyyy');
        }

        $(this).val($toString);
    });
});

/*==============================new month==============================*/

function newMonth () {
    $("#fixed-budget-info-table .spent").each(function () {
        $(this).text(0);
    });
}