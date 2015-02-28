app.factory('deleteItem', function ($http) {
	return {
		tag: function ($tag_id) {
			var $url = 'delete/tag';
			var $description = 'tag';
			var $data = {
				description: $description,
				tag_id: $tag_id
			};
			
			return $http.post($url, $data);
		},
		account: function ($account_id) {
			var $url = 'delete/account';
			var $description = 'account';
			var $data = {
				description: $description,
				account_id: $account_id
			};
			
			return $http.post($url, $data);
		},
		// removeTag: function ($this, $tag_array, $location_for_tags, $function) {
		// 	var $tag_name = $($this).text();
		// 	var $tag_id = $($this).attr('data-id');

		// 	var $index = $tag_array.map(function(el) {
		// 		return el.tag_id;
		// 	}).indexOf($tag_id);

		// 	$tag_array.splice($index, 1);

		// 	tagsHTML($tag_array, $location_for_tags);

		// 	if ($function !== undefined) {
		// 		$function();
		// 	}
		// },
		item: function () {
			if(confirm("Are you sure you want to delete this " + $item + "?")) {
				var $url = 'delete/item';

				var $data = {
					table: $table,
					id: $id
				};

				return $http.post($url, $data);
			}
		},
		budget: function ($tag_id, $tag_name) {
			var $url = 'delete/budget';
			var $description = 'budget';
			var $data = {
				description: $description,
				tag_id: $tag_id,
				tag_name: $tag_name
			};
			
			return $http.post($url, $data);
		},
		transaction: function ($transaction_id) {
			var $url = 'delete/transaction';
				var $description = 'transaction';
				var $data = {
					description: $description,
					transaction_id: $transaction_id
				};
				
				return $http.post($url, $data);
		},
		massDelete: function () {
			$(".checked").each(function () {
				deleteTransaction($(this));
			});
		}
	};
});
