angular.module('budgetApp')
    .filter('formatDurationFilter', function () {
        return function ($minutes) {

            if (!$minutes) {
                return '';
            }

            var $moment = moment.duration($minutes, 'minutes');
            var $formattedDuration = $moment._data.hours + ':' + $moment._data.minutes;

            return $formattedDuration;
        }
    });

