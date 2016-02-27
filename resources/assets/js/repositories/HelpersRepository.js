var HelpersRepository = {

    /**
     *
     * @param response
     */
    handleResponseError: function (response) {
        $.event.trigger('response-error', [response]);
        $.event.trigger('hide-loading');
    }
};