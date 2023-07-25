$(document).ready (function() {
	$('.img-box').mouseover(function () {

		var img = $(this).find('.gr-col');
		var ss  = '/images/picture_min/' + img.attr('id') + '-min.jpg';

		$(this).css('background-color', 'black');
		img.attr('src', ss);
	});
	$('.img-box').mouseout(function () {

		var img = $(this).find('.gr-col');
		var ss  = '/images/picture_gray/' + img.attr('id') + '-gray.jpg';

		$(this).css('background-color', 'transparent');
		img.attr('src', ss);
	});
});
