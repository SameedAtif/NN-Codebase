$(document).ready(function () {
	$(".menu-btn i.fa-bars").click(function () {
		$("ul.nav-menu").toggleClass("active");
	});
	
	$( ".accordion" ).accordion({
		heightStyle: "content"
	});
	$( ".accordion-closed" ).accordion({
		heightStyle: "content", collapsible: true, active: false
	});

	// IMPORTANT HIGHLIGHTER
	$(".imp-highlighter").click(function () {
		$(this).toggleClass("active");
		$(".important").toggleClass("highlighted");
	});
	
	// FILTER_TEXT - QA
	var array_to_filter_QA = $(".question .statement");
	var filter_input = $(".filter-text");
	
	filter_input.on("input", function () {
		var filter_val = $(this).val().toLowerCase();
		var unfiltered_list = [];
		
		array_to_filter_QA.each(function (index, value) {
			if ( value.innerHTML.toLowerCase().indexOf(filter_val) == -1 ) {
				unfiltered_list.push(array_to_filter_QA[index]);
			}
		});
		
		// update questions with the only filtered list visible
		$(".filtered-out-2").removeClass("filtered-out-2");
		unfiltered_list.forEach(function (item, index) {
			item.parentNode.className += " filtered-out-2";
		});
	});
	
	// FILTER_TEXT - TABLE
	var array_to_filter_TABLE = $(".has-filter tr td:nth-child(2)");
	var filter_input = $(".filter-text");
	
	filter_input.on("input", function () {
		var filter_val = $(this).val().toLowerCase();
		var unfiltered_list = [];
		
		array_to_filter_TABLE.each(function (index, value) {
			if ( value.innerHTML.toLowerCase().indexOf(filter_val) == -1 ) {
				unfiltered_list.push(array_to_filter_TABLE[index]);
			}
		});
		
		// update table with the only filtered list visible
		$(".filtered-out-1").removeClass("filtered-out-1");
		unfiltered_list.forEach(function (item, index) {
			item.parentNode.className += " filtered-out-1";
		});
	});
	
	// FILTER_ALPHABET - TABLE
	var array_to_filter = $(".has-filter tr td:nth-child(2)");
	var filter_input = $(".filter-alphabet");
	
	filter_input.on("input change", function () {
		var filter_val = $(this).val();
		var unfiltered_list = [];
		
		if (filter_val != "All") {
			array_to_filter.each(function (index, value) {
				if ( value.innerHTML.indexOf(filter_val) != 0 ) {
					unfiltered_list.push(array_to_filter[index]);
				}
			});
		}
		
		// update table with the only filtered list visible
		$(".filtered-out-2").removeClass("filtered-out-2");
		unfiltered_list.forEach(function (item, index) {
			item.parentNode.className += " filtered-out-2";
		});
	});

	// Arrange Past papers categorically
	if ($("h2.type").html() == "Past Papers") {
		var table = {
			"Lahore Board": ".lahore-board",
			"Gujranwala Board": ".gujranwala-board",
			"Unknown Board": ".unknown-board"
		};
		$(".scanned-img").each(function (index) {
			var alt = $(this).attr("alt");
			$(table[alt]).append($(this));
		});
		// remove the ones with nothing in 'em
		$.each(table, function (index, item) {
			if($(item).children().length <= 0) {
				$(item)[0].previousSibling.outerHTML = "";
				$(item).remove();
			}
		});
	}
	
});