app.factory('TagsFactory', function ($http) {
    return {
        /**
         *
         * @returns {*}
         */
        getTags: function () {
            var $url = 'api/tags';

            return $http.get($url);
        },

        /**
         * Adds a new tag to tags table, not to a transaction
         * @returns {*}
         */
        insertTag: function () {
            var $url = '/api/tags';
            var $data = {
                name: $("#new-tag-input").val()
            };
            $("#tag-already-created").hide();

            return $http.post($url, $data);
        },

        /**
         *
         * @param $tag_id
         * @param $tag_name
         * @returns {*}
         */
        updateTagName: function ($tag_id, $tag_name) {
            var $url = 'api/tags/'+$tag_id+'/update';
            var $data = {
                name: $tag_name
            };

            return $http.put($url, $data);
        },

        /**
         *
         * @param $tag_id
         * @returns {*}
         */
        deleteTag: function ($tag_id) {
            var $url = '/api/tags/'+$tag_id;

            return $http.delete($url);
        }
    };
});
