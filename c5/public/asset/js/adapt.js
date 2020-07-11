(function ($) {
    //Удаление фирмы
    $('.deleteFirm').click(function(){
        // var deleteUrl = "https://твояфирма.рф"+$(this).attr('data-href');
        // var windowFrame = window.open(deleteUrl, '', 'width=1, height=1,resizable=yes,toolbar=no,location=no, left=20000, top=20000, visible=none');
        //
        // setTimeout(function(){
        //     windowFrame.close();
        //     document.location.reload(true);
        // },1000);
        //
        // return false;
    });

//функции для адаптива
    function header_adapt() {
        var doc_w = $(window).width();
        if (doc_w <= 497) {

            //$('.headline__wrap-title').append($('.adv-firm-upupup'));
            $('.adv-category-upupup').insertBefore('.firm_list');

            $('.main_content').after($('.side_right'));
            $('body').addClass('mobile');
            //$('header .for_user').appendTo('header .wrapper');
            //$('main .wrapper .side_right').appendTo('main .wrapper');
            $('header .info').after($('header .for_user'));
            $('.yandex').css('margin-top', 0);
            $('.for_user .show_search').text('Поиск');
            $('.for_user .hide_search').text('Поиск');
            // $('#footer_help_link').after($('.metrika'));
        } else {

            $('.main_content').before($('.side_right'));
            $('header .info').before($('header .for_user'));
            if ($('.sort').length > 0) {
                var height = $('.sort').offset().top - $('header').offset().top - $('header').outerHeight();
                $('.side_right').css('margin-top', height - 15);
            }
            if ($('.subrubrics').length > 0) {
                var height = $('.subrubrics').offset().top - $('header').offset().top - $('header').outerHeight();
                $('.side_right').css('margin-top', height - 15);
            }
            $('.for_user .show_search').text('Показать поиск');
            $('.for_user .hide_search').text('Скрыть поиск');
            // $('footer .info').prepend($('.metrika'));
        }
        
        if (doc_w > 800) {
            jQuery('.side_right .hole').height(
                    jQuery('.main_content').outerHeight()
                    - jQuery('.side_right .rubrics').outerHeight()
                    - jQuery('.side_right .google').outerHeight()
                    - jQuery('.side_right .yandex').outerHeight()
                    - jQuery('.side_right .adv-category-upupup').outerHeight()
                    - parseInt(jQuery('.side_right').css('margin-top'), 10)
                    );
        }
        

        if (doc_w > 497 && $('body').hasClass('mobile')) {
            $('body').removeClass('mobile');
            if ($('.sort').length > 0) {
                var height = $('.sort').offset().top - $('header').offset().top - $('header').outerHeight();
                $('.yandex').css('margin-top', height - 15);
            }
            if ($('.subrubrics').length > 0) {
                var height = $('.subrubrics').offset().top - $('header').offset().top - $('header').outerHeight();
                $('.yandex').css('margin-top', height - 15);
            }
            $('header .for_user').prependTo('header .wrapper');
            $('main .wrapper .side_right').prependTo('main .wrapper');
        }
    };


    $(document).ready(function () {
        header_adapt();
    });   

    //вызов функций для адаптива
    window.onload = header_adapt;
    $(window).resize(header_adapt);
    setTimeout(header_adapt, 3000);
    setTimeout(header_adapt, 6000);
    setTimeout(header_adapt, 12000);

})(jQuery);
