
(function ( $ ) {

	$.fn.filterfy = function( options ) {
		var settings = $.extend({
			// These are the defaults.
			color: "#556b2f",
			backgroundColor: "white",
			array_to_filter: undefined,
			array_to_populate: undefined
		}, options );

		this.on('keyup', function ($keypress) {
			var $keycode = $keypress.which;
			var $typing = $(this).val();

			if ($keycode !== 38 && $keycode !== 40 && $keycode !== 13) {
				//not up arrow, down arrow or enter, so filter
				settings.array_to_populate = autocomplete.filterTags(settings.array_to_filter, $typing);
			}
			else if ($keycode === 38) {
				//up arrow
				autocomplete.upArrow($scope.autocomplete.tags);
			}
			else if ($keycode === 40) {
				//down arrow
				autocomplete.downArrow($scope.autocomplete.tags);
			}
			else if ($keycode === 13) {
				//enter
			}

			return $(this).css({
			color: settings.color,
			backgroundColor: settings.backgroundColor
			});
		});

		// // return this.each(function() {
		// // 	// Do something to each element here.
		// // });

	};

}( jQuery ));





















var autocomplete = {};

autocomplete.upArrow = function ($array) {
	var $selected = _.find($array, function ($item) {
		return $item.selected === true;
	});
	var $index = _.indexOf($array, $selected);
	var $prev_index = $index - 1;
	var $prev_item = $array[$prev_index];

	if ($prev_item) {
		delete $selected.selected;
		$prev_item.selected = true;
	}
};
autocomplete.downArrow = function ($array) {
	var $selected = _.find($array, function ($item) {
		return $item.selected === true;
	});

	if (!$selected) {
		var $first = _.first($array);
		$first.selected = true;
	}

	else {
		var $index = _.indexOf($array, $selected);
		var $next_index = $index + 1;
		var $next_item = $array[$next_index];

		if ($next_item) {
			delete $selected.selected;
			$next_item.selected = true;
		}
	}
};
autocomplete.filterTags = function ($tags, $typing) {
	var $filtered_tags = _.filter($tags, function ($tag) {
		return $tag.name.toLowerCase().indexOf($typing.toLowerCase()) !== -1;
	});

	return $filtered_tags;
};
autocomplete.filterTransactions = function ($transactions, $typing, $field) {
	$transactions = _.filter($transactions, function ($transaction) {
		if ($field === 'description') {
			return $transaction.description.toLowerCase().indexOf($typing.toLowerCase()) !== -1;
		}
		else if ($field === 'merchant') {
			return $transaction.merchant.toLowerCase().indexOf($typing.toLowerCase()) !== -1;
		}
	});
	autocomplete.removeSelected($transactions);
	//limiting the transactions in the autocomplete so it's faster
	$transactions = _.filter($transactions, function ($transaction) {
		return $transactions.indexOf($transaction) < 20;
	});

	if ($typing !== "") {
		autocomplete.selectFirstItem($transactions);
	}

	return $transactions;
};






