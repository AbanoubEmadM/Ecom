$(function () {
    'use-strict';

    // Hide Placeholder
    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });
    // Add Asterisk
    $('input').each(function () {
        if ($(this).attr('required') == 'required') {
            $(this).after('<span class="asterisk">*</span>');
        }
    });

    // Convert Password Field To Text Field On Hover
    var passField = $('.password');
    $(".show-pass").hover(function () {
        passField.attr('type', 'text');
        $(this).css('cursor', 'pointer');
    }, function () {
        passField.attr('type', 'password');
    });

    // Confirm Delete Button
    $('.confirm').click(function () {
        return confirm('Are You Sure?');
    })
})