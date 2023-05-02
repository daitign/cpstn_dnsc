import $ from "jquery";

$(function () {
    $('input[name="username"]').on('input', function () {
        $('.error_username').remove();
    });
});