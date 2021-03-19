$(function () {
	// Scroll to top button appear
	$(document).on('scroll', function () {
		var scrollDistance = $(this).scrollTop();
		if (scrollDistance > 100) {
			$('.scroll-to-top').fadeIn();
		} else {
			$('.scroll-to-top').fadeOut();
		}
	});

	// Smooth scrolling using jQuery easing
	$(document).on('click', 'a.scroll-to-top', function (e) {
		var $target = $(this.hash);
		$target = $target.length ? $target : $('html');
		var targetOffset = $target.offset().top;
		$('html,body').stop().animate({scrollTop: targetOffset}, {duration: 1000, easing: 'easeInOutQuad'});
		e.preventDefault();
	});
});