$(document).ready(function () {
    $('.img-box').mouseover(function () {

        var img = $(this).find('.gr-col');
        var ss = '/files/images/picture_min/' + img.attr('data-min');

        $(this).css('background-color', 'black');
        img.attr('src', ss);
    });
    $('.img-box').mouseout(function () {

        var img = $(this).find('.gr-col');
        var ss = '/files/images/picture_gray/' + img.attr('data-gray');

        $(this).css('background-color', 'transparent');
        img.attr('src', ss);
    });
});
