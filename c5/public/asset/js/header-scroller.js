jQuery(window).scroll(function () {
    if (jQuery(window).scrollTop() > jQuery('header').outerHeight()) {
        jQuery('header').addClass('header-fixed');
    } else {
        jQuery('header').removeClass('header-fixed');
    }
});