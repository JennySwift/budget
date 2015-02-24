


/*==============================other==============================*/

$(".tooltipster").tooltipster();

$(".tag_exists").hide();
$tags_array = [];


/*==============================logout==============================*/

$("#logout-btn").on('click', function () {
	var $log_out = true;

	$.ajax({
		url: 'ajax/logout.php',
		data: {
			log_out: $log_out
		},
	})
	.done(function(response) {
		window.location = "php/login.php";
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
});

/*==============================tour==============================*/

function startTour () {
	var tour = new Tour({
		debug: true,
		// storage: false,
		name: "tour-one",
		steps: [
			{
				// path: "/",
				element: "#tags_button",
				// title: "Title of my step",
				content: "Start out by clicking on this button, then entering some tags and accounts.",
				placement: "bottom"
			},
			{
				// path: "home.php",
				element: "#budget-button",
				content: "Then you can click on this button (clicking on it only works from the home screen) and start entering some budgets.",
				placement: "left",
			},
			// {
			// 	element: "#fixed-budget-tag-select",
			// 	content: "",
			// 	placement: "left",
			// 	onShow: function (tour) {
			// 		$("#budget-button").click();
			// 	}
			// },
			{
				// path: "/",
				element: "#new-transaction-wrapper",
				// title: "Title of my step",
				content: "This is where you enter a new transaction. Fill in the values then press command + enter (shift + enter for Windows users) simulataneously to enter it. Use tags to track the budgets you made.",
				placement: "bottom"
			},
			{
				// path: "/",
				element: "#new-transaction-tr .checkbox_container",
				// title: "Title of my step",
				content: "Click this button to reconcile a new transaction. Click again to undo.",
				placement: "left"
			},
			{
				// path: "/",
				element: "#search_button",
				// title: "Title of my step",
				content: "Click here to show the filter. The filter is where you can choose to only show certain transactions. Click again to hide the filter.",
				placement: "left"
			},
			{
				// path: "/",
				element: "#navigation-buttons",
				// title: "Title of my step",
				content: "Use these buttons to view next/previous transactions.",
				placement: "bottom"
			},
			{
				// path: "/",
				element: "#reset-search",
				// title: "Title of my step",
				content: "This button gives you a quick way of removing all the filters you have set.",
				placement: "bottom"
			},
			{
				// path: "/",
				element: ".total_div",
				// title: "Title of my step",
				content: "Here are your totals. Hover your cursor over them to see what the abbreviations stand for. Why don't you try now entering some transactions, and once you've done that, you can have a look at Tour 2 from the help menu.",
				placement: "right"
			},
			{
				// path: "/",
				element: "#menu-dropdown",
				// title: "Title of my step",
				content: "CMN is based on the cumulative starting date you set using this dropdown.",
				placement: "bottom"
			}
		]
	});

	// tour.init();
	// tour.start();

	$("#tour-button").on('click', function () {
		tour.init();
		tour.restart();
	});



	if (!tour.ended) {
		tour.start();
	}
}

startTour();

// if (tour.ended()) {
//   // decide what to do
//   tour.restart();
// }

function tour2 () {
	var tour = new Tour({
		debug: true,
		// storage: false,
		name: "tour-one",
		steps: [
			{
				element: ".results-transaction-tbody:first",
				// title: "Title of my step",
				content: "Edit a transaction by changing the value of an input and hitting enter. Values for account, reconciled, and tags are not transactions and so their is no need to hit enter for them to save the changes made.",
				placement: "bottom"
			},
			{
				element: ".mass-delete-checkbox-container:first",
				// title: "Title of my step",
				content: "To select multiple transactions, click these. Use the shift key for fast selection, and the option key to select a transaction while not unselecting the other selections you have made.",
				placement: "bottom"
			},
			{
				element: "#menu-dropdown",
				// title: "Title of my step",
				content: "Here is where you can edit or delete multiple transactions at once, after selecting them as described in the previous step.",
				placement: "bottom"
			}
		]
	});

	// tour.init();
	// tour.start();

	$("#tour-2-button").on('click', function () {
		tour.init();
		tour.restart();
	});

	if (!tour.ended) {
		tour.start();
	}
}

tour2();


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

/*==============================other==============================*/

$("#select_transaction_type").focus();

$("#search-type-select, #search-reconciled-select").val("all");







