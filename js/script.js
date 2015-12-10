$(function() {
	$(".not-templating .btn-next-frame-wrapper").on("click", function (e) {
		$('html, body').animate({scrollTop: $(".page-second").offset().top}, 800);		
	});

	$(".modal-wrapper").on("click", function (e) {
		e = e || window.event;
		e.stopPropagation ? e.stopPropagation() : (e.cancelBubble=true);
		var target = e.target || e.srcElement;
		
		if ($(target).hasClass("modal-wrapper"))
			$(this).removeClass("active");
	});

	$(".modal-wrapper .modal-window .title-window .close-window").on("click", function (e) {
		$(".modal-wrapper").removeClass("active");
	});
	$(".btn-designer-consult").on("click", function (e) {
		$(".modal-wrapper").addClass("active");
	});
});
