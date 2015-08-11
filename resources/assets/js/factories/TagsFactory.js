app.factory('TagsFactory', function ($http) {
    return {
        getTags: function () {
            var $url = 'select/tags';
            var $description = 'tags';
            var $data = {
                description: $description
            };

            return $http.post($url, $data);
        },
        //duplicateTagCheck: function () {
        //    var $url = 'select/duplicate-tag-check';
        //    var $description = 'duplicate tag check';
        //    var $new_tag_name = $("#new-tag-input").val();
        //    var $data = {
        //        description: $description,
        //        new_tag_name: $new_tag_name
        //    };
        //
        //    return $http.post($url, $data);
        //},
        countTransactionsWithTag: function ($tag_id) {
            var $url = 'select/countTransactionsWithTag';
            var $description = 'count transactions with tag';
            var $data = {
                description: $description,
                tag_id: $tag_id
            };

            return $http.post($url, $data);
        },

        /**
         * Adds a new tag to tags table, not to a transaction
         * @returns {*}
         */
        insertTag: function () {
            var $url = '/tags';
            var $data = {
                new_tag_name: $("#new-tag-input").val()
            };
            $("#tag-already-created").hide();

            return $http.post($url, $data);
        },

        updateTagName: function ($tag_id, $tag_name) {
            var $url = 'update/tagName';
            var $description = 'tag name';
            var $data = {
                description: $description,
                tag_id: $tag_id,
                tag_name: $tag_name
            };

            return $http.post($url, $data);

        },

        deleteTag: function ($tag_id) {
            var $url = 'delete/tag';
            var $description = 'tag';
            var $data = {
                description: $description,
                tag_id: $tag_id
            };

            return $http.post($url, $data);
        }
    };
});
