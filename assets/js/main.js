(function ($, window) {
	// ----- longpv ------
	// ----- vucoder ------
	/* ===== Modal ===== */
	$(".open-modal").click(function () {
		var target = $(this).data("target");
		$(target).addClass("show");
		$("body").css("overflow", "hidden");
	});

	$(".modal").on("click", function (e) {
		if ($(e.target).is(".modal, .modal-close")) {
			$(this).removeClass("show");
			$("body").css("overflow", "auto");
		}
	});

	/* ===== Tabs ===== */
	$(".tabs .tab-links a").click(function (e) {
		e.preventDefault();
		var $tabs = $(this).closest(".tabs");
		var target = $(this).attr("href");

		$tabs.find(".tab-links a").removeClass("active");
		$(this).addClass("active");

		$tabs.find(".tab-content").removeClass("active");
		$tabs.find(target).addClass("active");
	});

	/* ===== Accordion ===== */
	$(".accordion .accordion-header").click(function () {
		var $accordion = $(this).closest(".accordion");
		var $body = $(this).next(".accordion-body");

		$accordion.find(".accordion-body").not($body).slideUp();
		$body.slideToggle();
	});
})(jQuery, window);
