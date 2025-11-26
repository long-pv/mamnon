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

	document.addEventListener("wpcf7mailsent", function (event) {
		const form = event.target; // form đã submit thành công

		form.querySelectorAll(".form_file").forEach(function (input) {
			const noteElement = input.closest(".form_file_label").querySelector(".form_note");
			if (noteElement) {
				noteElement.innerHTML = noteElement.getAttribute("data-default-text") || "Upload attachments";
			}
			input.value = ""; // reset file input
		});
	});

	function setBusy(form, busy) {
		var $btn = $(form).find(".wpcf7-submit");
		if (!$btn.length) return;
		if (busy) {
			$btn.addClass("is-busy").prop("disabled", true);
		} else {
			$btn.removeClass("is-busy").prop("disabled", false);
		}
	}

	/* CF7 sẽ bắn event này ngay TRƯỚC khi submit AJAX */
	document.addEventListener(
		"wpcf7beforesubmit",
		function (e) {
			setBusy(e.target, true);
		},
		false
	);

	/* Sau khi có kết quả (kể cả validate lỗi) → luôn bật lại */
	["wpcf7invalid", "wpcf7spam", "wpcf7mailfailed", "wpcf7mailsent", "wpcf7submit"].forEach(function (ev) {
		document.addEventListener(
			ev,
			function (e) {
				setBusy(e.target, false);
			},
			false
		);
	});
})(jQuery, window);
