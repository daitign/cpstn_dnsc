import $ from "jquery";

$(function () {
    $('.out').click(function (e) { 
        e.preventDefault();
        $('.in-btn').addClass('collapsed');
        $('.in-output').removeClass('show');
    });
});