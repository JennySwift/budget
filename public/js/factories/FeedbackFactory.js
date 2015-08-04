app.factory('FeedbackFactory', function ($http) {
    var $object = {};

    $object.provideFeedback = function ($message) {
        //My watch in my controller would only work once unless I made an object here.
        //(Just $object.message would not work.)
        $object.data = {
            message: $message,
            update: true
        };
        return $object.data;
    };

    return $object;
});
