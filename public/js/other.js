
$(".tooltipster").tooltipster();

$(".tag_exists").hide();
$tags_array = [];


/*==============================quick select of transactions==============================*/

$("body").on('click', '.mass-delete-checkbox-container', function (event) {
	var $this = $(this).closest("tbody");
	var $checked = $(".checked");
	$(".last-checked").removeClass("last-checked");
	$(".first-checked").removeClass("first-checked");

	if (event.shiftKey) {
		var $last_checked = $($checked).last().closest("tbody");
		var $first_checked = $($checked).first().closest("tbody");
		
		$($last_checked).addClass("last-checked");
		$($first_checked).addClass("first-checked");
		$($this).addClass("checked");

		if ($($this).prevAll(".last-checked").length !== 0) {
			//$this is after .last-checked
			shiftSelect("forwards");
		}
		else if ($($this).nextAll(".last-checked").length !== 0) {
			//$this is before .last-checked
			shiftSelect("backwards");
		}
	}
	else if (event.altKey) {
		$($this).toggleClass('checked');
	}
	else {
		console.log("no shift");
		$(".checked").not($this).removeClass('checked');
		$($this).toggleClass('checked');
	}
});

function shiftSelect ($direction) {
	$("#my_results tbody").each(function () {
		var $prev_checked_length = $(this).prevAll(".checked").length;
		var $after_checked_length = $(this).nextAll(".checked").length;
		var $after_last_checked = $(this).prevAll(".last-checked").length;
		var $before_first_checked = $(this).nextAll(".first-checked").length;

		if ($direction === "forwards") {
			//if it's after $last_checked and before $this
			if ($prev_checked_length !== 0 && $after_checked_length !== 0 && $after_last_checked !== 0) {
				$(this).addClass('checked');
			}
		}
		else if ($direction === "backwards") {
			if ($prev_checked_length !== 0 && $after_checked_length !== 0 && $before_first_checked !== 0) {
				$(this).addClass('checked');
			}
		}
	});
}



/*==============================tag_div width==============================*/
function tagDivWidth () {
	var $width;
	console.log("running tagDivWidth");
	if ($(".income_tag_input").width() !== 0) {
		$width = $(".income_tag_input").width();
		$(".tag_div").css('width', $width);
	}
	else if ($(".expense_tag_input").width() !== 0) {
		$width = $(".expense_tag_input").width();
		$(".tag_div").css('width', $width);
	}
	else if ($(".transfer_tag_input").width() !== 0) {
		$width = $(".transfer_tag_input").width();
		$(".tag_div").css('width', $width);
	}
}

/*==============================dropdown positioning==============================*/


function dropdownPosition ($dropdown) {
	var $container_height;

	if ($($dropdown).attr('id') === "search-tags-dropdown") {
		$container_height = $("#search-tags-input").outerHeight();
	}
	else {
		$container_height = $($dropdown).parent().outerHeight();
	}
	$($dropdown).css('top', $container_height);
}







