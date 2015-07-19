app.factory('TagsFactory', function ($http) {
    return {
        /**
         * select
         */

        getTags: function () {
            var $url = 'select/tags';
            var $description = 'tags';
            var $data = {
                description: $description
            };

            return $http.post($url, $data);
        },
        duplicateTagCheck: function () {
            var $url = 'select/duplicate-tag-check';
            var $description = 'duplicate tag check';
            var $new_tag_name = $("#new-tag-input").val();
            var $data = {
                description: $description,
                new_tag_name: $new_tag_name
            };

            return $http.post($url, $data);
        },
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
         * insert
         */

        insertTag: function () {
            //adds a new tag to tags table, not to a transaction
            var $url = 'insert/tag';
            var $description = 'tag';
            var $new_tag_name = $("#new-tag-input").val();
            var $data = {
                description: $description,
                new_tag_name: $new_tag_name
            };
            $("#tag-already-created").hide();

            return $http.post($url, $data);
        },

        /**
         * update
         */

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

        /**
         * delete
         */

        deleteTag: function ($tag_id) {
            var $url = 'delete/tag';
            var $description = 'tag';
            var $data = {
                description: $description,
                tag_id: $tag_id
            };

            return $http.post($url, $data);
        },
    };
});
