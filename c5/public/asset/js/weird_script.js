var TYPE_FIXED = 1;
var TYPE_BLOCKPAGE = 2;
var TYPE_ANNOYING = 3;
var TYPE_INNER = 4;

var shitty_timer;

var cookieManager = (function(){
    return {

        get: function(name) {
            var matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
            ));
            return matches ? decodeURIComponent(matches[1]) : false;
        },

        set: function(name, value, options) {
            options = options || {};

            var expires = options.expires;

            if (typeof expires == "number" && expires) {
                var d = new Date();
                d.setTime(d.getTime() + expires * 1000);
                expires = options.expires = d;
            }
            if (expires && expires.toUTCString) {
                options.expires = expires.toUTCString();
            }

            value = encodeURIComponent(value);

            var updatedCookie = name + "=" + value;

            for (var propName in options) {
                updatedCookie += "; " + propName;
                var propValue = options[propName];
                if (propValue !== true) {
                    updatedCookie += "=" + propValue;
                }
            }

            document.cookie = updatedCookie;
        },

        delete: function(name) {
            this.set(name, "", {
                expires: -1
            })
        }
    }
}());

var ADB_STATS_METRICS_ID = '28929580';
var ADB_STATS_TOTAL_ADBLOCKS = 'adblocks';
var ADB_STATS_GUIDE_CLICK = 'how-to-turn-off';
var ADB_STATS_TURNED_OFF = 'turned-off';
var ADB_STATS_ADBLOCK_COOKIE = 'blockpechenki';

function adblock_detection(){
    if(window.adsOn === undefined) {
        cookieManager.set(ADB_STATS_ADBLOCK_COOKIE, 1, {expires: 3600*24*30*5, path: '/'});
        yaCounter28929580.reachGoal(ADB_STATS_TOTAL_ADBLOCKS);
        return;
    }
    if(cookieManager.get(ADB_STATS_ADBLOCK_COOKIE)){
        yaCounter28929580.reachGoal(ADB_STATS_TURNED_OFF);
        cookieManager.delete(ADB_STATS_ADBLOCK_COOKIE);
    }

}

// функции темплейтов адблока

//блок страница
function block_page() {
    fixed();

    $('footer').remove();
    $('.hfooter').remove();
    $('.fon').remove();

    var main_content = $('#block-system-main .content');
    main_content.html('');

    var new_content = $('<div class="pls-dont-block-this-div pls-dont-block-this-div--big" style="">' +
        '<div class="adblock-wrapper">' +
        '<div class="adblock-cell">' +
        '<div class="adblock-image">' +
        '<img src="/asset/images/Adblockplus_big.png">' +
        '</div>' +
        '<div class="adblock_text"><span class="semibold">Здравствуйте. Похоже Вы используете AdBlock.</span><br>' +
        'Наш сайт развивается и существует за счет доходов от рекламы.<br>' +
        'Поддержите нас. Добавьте сайт в исключения. ' +
        '<a rel="nofollow" title="Как добавить в исключения"' +
        ' class="shitty_block_gotosite ab_link">Как отключить?</a>' +
        '<div class="link">' +
        '<button class="shitty_block_gotosite">Перейти на сайт</button>' +
        '</div>' +
        '</div>' +
        '</div></div></div>');
    //
    // var new_content = $('<div class="shitty_block"></div>').html(
    //     '<div class="shitty_block_head">' +
    //     '<div class="logo"><img src="/project/templates/default_assets/images/logo.png"/></div>' +
    //     '<div class="somehow_ads_is_going_to_render_here_idk">reklama</div>' +
    //     '</div>' +
    //     '<div class="shitty_block_middle"><div style="margin-bottom: 20px;">ОТКЛЮЧИ ADBLOCK</div>' +
    //     'и пользуйся без дополнительного просмотра рекламы</div>' +
    //     '<div class="shitty_block_footer"><button class="shitty_block_gotosite">ПЕРЕХОД НА САЙТ ЧЕРЕЗ 5</button></div>'
    // );



    new_content.appendTo(main_content);

    $('.ab_link').css('cursor', 'pointer');
    $('.ab_link').css('text-decoration', 'underline');

    $('.shitty_block_gotosite').click(function (e) {
        showHowToModal();
    });

    // shitty_timer = setInterval(crapload_blocker, 1000);

    return 'block';
}

var state_of_shitty_blocker = 5;

function crapload_blocker() {
    if(state_of_shitty_blocker > 0){
        state_of_shitty_blocker--;
        $('.shitty_block_gotosite').html('ПЕРЕХОД НА САЙТ ЧЕРЕЗ ' + state_of_shitty_blocker);
    } else {
        $('.shitty_block_gotosite').html('ПЕРЕХОД НА САЙТ');
        clearInterval(shitty_timer);
    }
}

//модальное
function annoying() {
    //отключаем, так как нет всплывашки адблока
    inner();
    return 0;

    var w = $('main > .wrapper').width();
    var h = 150;
    if(window.innerWidth > 500){
        w = 500;
    }
    $('<div>Здравствуйте. Похоже вы используете AdBlock. Наш сайт развивается и существует за счет доходов от рекламы.<br/>' +
        'Поддержите нас — добавьте сайт в исключения.<br/><br/>' +
        '<button class="popup-disable-block">КАК ОТКЛЮЧИТЬ?</button></div>').modal(
        {
            containerId : "aiwincss",
            containerCss:{
                width: w,
                height:h,
                overflow: "hidden",
                backgroundColor: "#F06C24",
                color:"#FFFFFF",
                textAlign: "center",
                fontFamily: 'Ubuntu'
            },
            overlayClose:true,
            overlayCss:{
                backgroundColor:"#ededed"
            }
        });

    $('.popup-disable-block').css({'background-color' : 'inherit',
        'text-decoration' : 'underline',
        color : '#FFFFFF'
    });

    $('.popup-disable-block').click(function (e) {
        $.modal.close();
        showHowToModal();
    });

    return 'anno';
}

//внутряк
function inner() {
    fixed();
    return;
    $('.side_right').css('margin-top', 0);

    var ad_mobile = $('.adblock-card-placeholder');
    var ad_pc = $('.headline__wrap-adblock');

    //мобилка
    ad_mobile.html('<div class="pls-dont-block-this-div pls-dont-block-div-card-adblock-mobile pls-dont-block-this-div--card mob-visible" style="">' +
        '<div class="adblock_text"><span class="semibold">Пожалуйста отключите Adblock</span><br>Мы тщательно следим за релевантностью и форматом показываемой рекламы</div>' +
        '</div>');

    //пекарня
    ad_pc.html('<div class="pls-dont-block-this-div pls-dont-block-this-div--card" style="">' +
        '<div class="adblock_text">' +
        '<span class="semibold">Пожалуйста отключите Adblock</span><br>' +
        'Мы тщательно следим за релевантностью и форматом показываемой рекламы' +
        '</div></div>');
    //
    //
    // ad_pc.css({'margin-top': '7px'});
    //
    // $('.popup-disable-block-mobile').css({'background-color' : 'inherit',
    //     'text-decoration' : 'underline',
    //     color : '#F06C24',
    //     'margin-top': '-13px',
    //     'margin-left': '38px',
    //     'display': 'block'
    // });
    //
    // $('.popup-disable-block-pc').css({'background-color' : 'inherit',
    //     'text-decoration' : 'underline',
    //     color : '#F06C24',
    //     'margin-top': '-4px',
    //     'margin-left': '3px',
    //     'padding-left': '37px',
    //     'line-height': '27px',
    //     'background': 'url(/project/templates/default_assets/images/no_adblock.png) no-repeat',
    //     'margin-bottom': '20px'
    // });
    //
    // $('.popup-disable-block').click(function (e) {
    //     showHowToModal();
    // });

    return 'inner';
}

//прилипашка
function fixed() {

    var div = $('<div class="pls-dont-block-this-div" style="">' +
        '<div class="adblock_text">' +
        '<span class="semibold"> Здравствуйте. Похоже вы используете AdBlock.</span><br>' +
        'Наш сайт развивается и существует за счет доходов от рекламы. Поддержите нас — добавьте сайт в исключения.' +
        ' <a href="#" rel="nofollow" title="Как добавить в исключения" ' +
    ' class="ab_link popup-disable-block">Как отключить?</a></div></div>');

    // var div = $('<div class="adblock_text header_middle"></div>')
    //     .html('<div class="wrapper"><img src="/project/templates/default_assets/images/Adblockplus_icon.png">Здравствуйте. Похоже вы используете AdBlock. Наш сайт развивается и существует за счет доходов от рекламы.<br>Поддержите нас — добавьте сайт в исключения.' +
    //         '<button class="popup-disable-block">КАК ОТКЛЮЧИТЬ?</button></div>');
    // $('body > .wrapper').css({'margin-top' : '78px'});
    div.appendTo($('body'));
    if(window.innerWidth > 500)
        $('body > .wrapper').css('margin-top', '70px');


    $('.ab_link').css('text-decoration', 'underline');

    // $('.popup-disable-block').css({'background-color' : 'inherit',
    //     'text-decoration' : 'underline',
    //     color : '#FFFFFF'
    // });
    $('.popup-disable-block').click(function (e) {
        showHowToModal();
    });
}



// добавлялка на главный экран

// ПК версия

//в контенте
function homescreen_pc_content() {
    //отключаем, в контенте такого нет
    return 0;
    setTimeout(antiVelosiped, 2000);
    var home_pc_firm = $('.home_pc_firm');
    var homescreen_pc_groups = $('.homescreen-pc-groups');
    if(home_pc_firm.length > 0){
        home_pc_firm.css('display', 'block');
        home_pc_firm.html('<div class="pc-firm-left">' +
            '<div class="pc-firm-lupa"><img src="/project/templates/default_assets/images/big_lupa.png"></div>' +
            '<div class="pc-firm-stars"><img src="/project/templates/default_assets/images/stars_shi.png"></div>' +
            '</div>' +
            '<div class="pc-firm-right">' +
            '<div class="pc-firm-logo"><img src="/project/templates/default_assets/images/logo.png"></div>' +
            '<div class="pc-firm-text">Справочник предприятий России</div>' +
            '<div class="pc-firm-buttons">' +
            '<div class="cursor-pointer pc-firm-add">ДОБАВИТЬ</div>' +
            '<div class="cursor-pointer pc-firm-already">Уже добавил</div>' +
            '</div>' +
            '</div>');

        var already = $('.pc-firm-already');
        already.hide();
        if(cookieManager.get(MAIN_SCREEN_ADDED_CHECK)){
            already.css('display', 'inline-block');
        }

        already.on('click', function () {
            home_pc_firm.remove();
            cookieManager.set(MAIN_SCREEN_APPROVED, 1, {expires: MAIN_SCREEN_APPROVED_TIMEOUT, path: '/'});
        });

        $('.pc-firm-add').on('click', function () {
            home_pc_firm.remove();
            showHowToDesktopModal();
        });

    }

    if(homescreen_pc_groups.length > 0){
        homescreen_pc_groups.css('display', 'block');
        homescreen_pc_groups.html('<div class="pc-groups-lupa"><img src="/project/templates/default_assets/images/big_lupa.png"></div>' +
            '<div class="pc-groups-right">' +
            '<div class="pc-groups-logo"><img src="/project/templates/default_assets/images/logo.png"></div>' +
            '<div class="pc-groups-text">Справочник предприятий России</div>' +
            '<div class="pc-groups-buttons">' +
            '<div class="pc-groups-add cursor-pointer">ДОБАВИТЬ</div>' +
            '<div class="pc-groups-approve cursor-pointer">Уже добавил</div>' +
            '</div>' +
            '</div>' +
            '<div class="pc-groups-stats"><img src="/project/templates/default_assets/images/stars_shi.png"></div>');

        var already = $('.pc-groups-approve');
        already.hide();
        if(cookieManager.get(MAIN_SCREEN_ADDED_CHECK)){
            already.css('display', 'inline-block');
        }

        already.on('click', function () {
            homescreen_pc_groups.remove();
            cookieManager.set(MAIN_SCREEN_APPROVED, 1, {expires: MAIN_SCREEN_APPROVED_TIMEOUT, path: '/'});
        });

        $('.pc-groups-add').on('click', function () {
            homescreen_pc_groups.remove();
            showHowToDesktopModal();
        });

    }
}

//прилипаха
// function homescreen_pc_bottom() {
//     var saying = 'Добавить на рабочий стол';
//
//     if(window.innerWidth < 768){
//         saying = 'Добавить на главный экран';
//     }
//
//
//     $('<div class="announce__wrap"><div class="announce">' +
//         '<div class="announce__logo">' +
//         '<div class="announce__logo-icon">' +
//         '<div class="announce__logo-icon-cell">' +
//         '<img src="/asset/images/logo_tf.png"/>' +
//         '</div></div>' +
//         '<div class="announce__logo-text">Справочник Твоя Фирма</div></div>' +
//         '<div class="announce__buttons">' +
//         '<div class="announce__buttons-item">' +
//         '<a class="button homescreen-pc-already button--gray" style="font-weight: bold;">Уже добавил</a>' +
//         '</div>' +
//         '<div class="announce__buttons-item">' +
//         '<a class="button homescreen_pc_button_add button--purple">'+saying+'</a>' +
//         '</div>' +
//         '</div>' +
//         '<span class="popup__close popup__close--announce"></span>' +
//         '</div></div>').appendTo($('body'));
//
//     var already = $('.homescreen-pc-already');
//     already.hide();
//     $('.homescreen-pc-bottom-button-container').css('padding-left', '200px');
//     if(cookieManager.get(MAIN_SCREEN_ADDED_CHECK)){
//         already.css('display', 'block');
//     }
//
//
//     $('.homescreen-pc-already').on('click', function () {
//         $('.announce__wrap').remove();
//         cookieManager.set(MAIN_SCREEN_APPROVED, 1, {expires: MAIN_SCREEN_APPROVED_TIMEOUT, path: '/'});
//     });
//
//     $('.popup__close--announce').on('click', function () {
//         cookieManager.set(MAIN_SCREEN_DECLINED, 1, {path: '/'});
//         $('.announce__wrap').remove();
//     });
//
//     $('.homescreen_pc_button_add').on('click', function () {
//         $('.announce__wrap').remove();
//         showHowToDesktopModal();
//     });
//
// }

//модальное
function homescreen_pc_modal() {
    // $('<div class="homescreen-please-block-modal-mobile">' +
    //     '<div class="homescreen-modal-main">' +
    //     '<div class="homescreen-modal-header">ДОБАВИТЬ НА ГЛАВНЫЙ ЭКРАН</div>' +
    //     '<div class="homescreen-modal-content">' +
    //     '<div class="homescreen-modal-lupa"><img src="/project/templates/default_assets/images/lupa.png"></div>' +
    //     '<div class="homescreen-modal-content-text">' +
    //     '<div class="homescreen-modal-spravv">Справочников</div>' +
    //     '<div class="lil-ass">Все компании Росии в одном месте</div>' +
    //     '</div>' +
    //     '</div>' +
    //     '</div>' +
    //     '<div class="homescreen-modal-buttonz">' +
    //     '<div class="homescreen-modal-button homescreen-modal-ROOO cursor-pointer">ОТМЕНА</div>' +
    //     '<div class="homescreen-modal-button homescreen-modal-approved cursor-pointer">Уже добавил</div>' +
    //     '<div class="homescreen-modal-button homescreen-modal-add cursor-pointer">ДОБАВИТЬ</div>' +
    //     '</div>' +
    //     '</div>').modal(
    //     {
    //         containerId : "aiwincss",
    //         containerCss:{
    //             width: 280,
    //             padding: 0,
    //             height: 170,
    //             overflow: "hidden"
    //         },
    //         overlayClose:true,
    //         overlayCss:{
    //             backgroundColor:"#000000"
    //         }
    //     });

    /// Коррекция от 12.11.2018
    // var saying = 'Добавить на рабочий стол';
    //
    // if(window.innerWidth < 768){
    //     saying = 'Добавить на главный экран';
    // }
    //
    // $('<div class="popup__background"></div>' +
    //     '<div class="popup">' +
    //     '<div class="popup__headline">'+saying+'</div>' +
    //     '<div class="popup__logo">' +
    //     '<div class="popup__logo-icon">' +
    //     '<div class="popup__logo-icon-cell">' +
    //     '<img src="/asset/images/logo_tf.png"/>' +
    //     '</div>' +
    //     '</div>' +
    //     '<div class="popup__logo-text">' +
    //     '<span class="mob-hide">Добавить на рабочий стол<br/></span>' +
    //     'Справочник Твоя Фирма</div></div><div class="popup__logo-buttons">' +
    //     '<div class="popup__logo-buttons-item">' +
    //     '<a class="button homescreen-modal-ROOO button--gray">Отмена</a></div>' +
    //     '<div class="popup__logo-buttons-item homescreen-modal-approved">' +
    //     '<a class="button button--gray">Уже добавил</a></div>' +
    //     '<div class="popup__logo-buttons-item">' +
    //     '<a class="button homescreen-modal-add button--purple">Добавить</a></div></div>' +
    //     '<span class="popup__close"></span>' +
    //     '</div>').appendTo($('body'));
    //
    // var already = $('.homescreen-modal-approved');
    // already.hide();
    //
    // if(cookieManager.get(MAIN_SCREEN_ADDED_CHECK)){
    //     already.show();
    //     $('.homescreen-modal-ROOO').hide();
    // }
    //
    // $('.homescreen-modal-approved').on('click', function () {
    //     $('.popup__background').remove();
    //     $('.popup').remove();
    //     cookieManager.set(MAIN_SCREEN_APPROVED, 1, {expires: MAIN_SCREEN_APPROVED_TIMEOUT, path: '/'});
    // });
    //
    // $('.homescreen-modal-ROOO').on('click', function () {
    //     cookieManager.set(MAIN_SCREEN_DECLINED, 1, {path: '/'});
    //     $('.popup__background').remove();
    //     $('.popup').remove();
    // });
    //
    // $('.homescreen-modal-add').on('click', function () {
    //     $('.popup__background').remove();
    //     $('.popup').remove();
    //     showHowToDesktopModal();
    // });

    $('.popup__close').on('click', function () {
        $('.popup__background').remove();
        $('.popup').remove();
    });

}

//мобильная версия

//прилипаха
function homescreen_bottom_mobile() {
    homescreen_pc_bottom();
    return 0;
    $('<div class="homescreen-please-block-bottom-mobile">' +
        '<div class="homescreen-bottom-head">' +
        '<div class="homescreen-bottom-lupa"><img src="/project/templates/default_assets/images/lupa.png"></div>' +
        '<div class="homescreen-bottom-block">' +
        '<div class="spravvv">Справочников</div>' +
        '<div>Все компании России в одном месте</div>' +
        '</div>' +
        '<div class="homescreen-bottom-close cursor-pointer">X</div>' +
        '</div>' +
        '<div class="homescreen-bottom-already cursor-pointer">Уже добавил</div>' +
        '<div class="homescreen-bottom-button cursor-pointer">ДОБАВИТЬ НА ГЛАВНЫЙ ЭКРАН</div>' +
        '</div>').appendTo($('body'));

    var already = $('.homescreen-bottom-already');
    already.hide();

    if(cookieManager.get(MAIN_SCREEN_ADDED_CHECK)){
        var butt = $('.homescreen-bottom-button');
        already.css('background-color', 'gray');
        already.css('float', 'left');
        already.css('width', '30%');
        already.css('color', '#ffffff');
        already.css('display', 'inline-block');

        already.css('font-family', 'sans-serif');
        already.css('text-align', 'center');
        already.css('padding', '5px 0');


        butt.css('display', 'block');
        butt.css('width', '62%');
        butt.css('float', 'right');
        butt.css('margin-right', '10px');
        butt.css('font-size', '0.8em');
    }

    $('.homescreen-bottom-already').on('click', function () {
        $('.homescreen-please-block-bottom-mobile').remove();
        cookieManager.set(MAIN_SCREEN_APPROVED, 1, {expires: MAIN_SCREEN_APPROVED_TIMEOUT, path: '/'});
    });

    $('.homescreen-bottom-close').on('click', function () {
        cookieManager.set(MAIN_SCREEN_DECLINED, 1, {path: '/'});
        $('.homescreen-please-block-bottom-mobile').remove();
    });

    $('.homescreen-bottom-button').on('click', function () {
        $('.homescreen-please-block-bottom-mobile').remove();
        showHowToDesktopModal();
    });
}

//вверху страницы
function homescreen_top_mobile() {
    return 0;
    $('<div class="homescreen-please-block-top-mobile">' +
        '<div class="homescreen-top-lupa"><img src="/project/templates/default_assets/images/big_lupa.png"></div>' +
        '<div class="homescreen-top-block">' +
        '<div class="homescreen-top-logo"><img src="/project/templates/default_assets/images/logo.png"></div>' +
        '<div class="homescreen-top-text">Справочник предприятий России</div>' +
        '<div class="homescreen-top-battanz">' +
        '<div class="homescreen-top-add-butt cursor-pointer">ДОБАВИТЬ</div>' +
        '<div class="homescreen-top-approve-butt cursor-pointer">Уже добавил</div>' +
        '</div>' +
        '</div>' +
        '<div class="homescreen-top-stars"><img src="/project/templates/default_assets/images/stars_shi.png"></div>' +
        '</div>').prependTo($('body'));

    var already = $('.homescreen-top-approve-butt');
    already.hide();

    if(cookieManager.get(MAIN_SCREEN_ADDED_CHECK)){
        already.show();
    }

    $('.homescreen-top-approve-butt').on('click', function () {
        $('.homescreen-please-block-top-mobile').remove();
        cookieManager.set(MAIN_SCREEN_APPROVED, 1, {expires: MAIN_SCREEN_APPROVED_TIMEOUT, path: '/'});
    });

    $('.homescreen-top-add-butt').on('click', function () {
        $('.homescreen-please-block-top-mobile').remove();
        showHowToDesktopModal();
    });

}

//модальное
function homescreen_modal_mobile() {
    homescreen_pc_modal();
    return 0;
    $('<div class="homescreen-please-block-modal-mobile">' +
        '<div class="homescreen-modal-main">' +
        '<div class="homescreen-modal-header">ДОБАВИТЬ НА ГЛАВНЫЙ ЭКРАН</div>' +
        '<div class="homescreen-modal-content">' +
        '<div class="homescreen-modal-lupa"><img src="/project/templates/default_assets/images/lupa.png"></div>' +
        '<div class="homescreen-modal-content-text">' +
        '<div class="homescreen-modal-spravv">Справочников</div>' +
        '<div class="lil-ass">Все компании Росии в одном месте</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="homescreen-modal-buttonz">' +
        '<div class="homescreen-modal-button homescreen-modal-ROOO cursor-pointer">ОТМЕНА</div>' +
        '<div class="homescreen-modal-button homescreen-modal-approved cursor-pointer">Уже добавил</div>' +
        '<div class="homescreen-modal-button homescreen-modal-add cursor-pointer">ДОБАВИТЬ</div>' +
        '</div>' +
        '</div>').modal(
        {
            containerId : "aiwincss",
            containerCss:{
                width: 280,
                padding: 0,
                height: 170,
                overflow: "hidden"
            },
            overlayClose:true,
            overlayCss:{
                backgroundColor:"#000000"
            }
        });

    var already = $('.homescreen-modal-approved');
    already.hide();

    if(cookieManager.get(MAIN_SCREEN_ADDED_CHECK)){
        already.show();
        $('.homescreen-modal-ROOO').hide();
    }

    $('.homescreen-modal-approved').on('click', function () {
        $.modal.close();
        cookieManager.set(MAIN_SCREEN_APPROVED, 1, {expires: MAIN_SCREEN_APPROVED_TIMEOUT, path: '/'});
    });

    $('.homescreen-modal-ROOO').on('click', function () {
        cookieManager.set(MAIN_SCREEN_DECLINED, 1, {path: '/'});
        $.modal.close();
    });

    $('.homescreen-modal-add').on('click', function () {
        $.modal.close();
        showHowToDesktopModal();
    });

}



(function($){
    $(document).ready(function(){
        if(cookieManager.get('admin_edit')){
            console.log('GURU MEDITATION #789SG83JZQ7');
            return false;
        }


        if(window.adsOn === undefined) {
            // если адблок - показываем напряжные штуки для адблока
            var current = fixed;

            var type = cookieManager.get('block_type');
            if(!type){
                current = getRandomAdblockType();
            } else {
                current = getAdblockByType(type);
            }

            current();
        }
        else{
            // если адблока нет - просим добавить на главный экран
           // mainScreenInit();
        }
        adblock_detection();

        // block_page();

    });
})(jQuery);

var MAIN_SCREEN_APPROVED = 'main_screen_approved';
var MAIN_SCREEN_ADDED = 'main_screen_added';
var MAIN_SCREEN_ADDED_CHECK = 'main_screen_added_check';
var MAIN_SCREEN_COUNTER = 'main_screen_counter';
var MAIN_SCREEN_SECOND_ENCOUNTER = 'main_screen_second';
var MAIN_SCREEN_SET_SESSION = 'main_screen_set_session';
var MAIN_SCREEN_DECLINED = 'main_screen_declined';

var MAIN_SCREEN_ADDED_TIMEOUT = 3600*24*14;

var MAIN_SCREEN_RETURN_TIMEOUT = 3600*24*30;
var MAIN_SCREEN_APPROVED_TIMEOUT = 3600*24*30*3;
var MAIN_SCREEN_SITE_TIMEOUT = 30000;

var screen_timeout_render;

function mainScreenInit(){
    if(cookieManager.get(MAIN_SCREEN_APPROVED) || cookieManager.get(MAIN_SCREEN_ADDED) || cookieManager.get(MAIN_SCREEN_DECLINED)){
        return false;
    }

    console.log('setting timeout!');
    screen_timeout_render = setTimeout(mainScreenRender, MAIN_SCREEN_SITE_TIMEOUT);

    console.log('timeout set!');
    if(cookieManager.get(MAIN_SCREEN_SECOND_ENCOUNTER) && !cookieManager.get(MAIN_SCREEN_SET_SESSION)){

        console.log('rendering!');
        mainScreenRender();
        return;
    }

    cookieManager.set(MAIN_SCREEN_SECOND_ENCOUNTER, 1, {expires: MAIN_SCREEN_RETURN_TIMEOUT, path: '/'});
    cookieManager.set(MAIN_SCREEN_SET_SESSION, 1, {path: '/'});

    var current_page_counter = cookieManager.get(MAIN_SCREEN_COUNTER);
    if(current_page_counter){
        current_page_counter++;
    }else{
        current_page_counter = 1;
    }
    cookieManager.set(MAIN_SCREEN_COUNTER, current_page_counter, {path: '/'});

    if(current_page_counter > 3){
        mainScreenRender();
        return;
    }
}

function mainScreenRender(){
    clearTimeout(screen_timeout_render);

    if(navigator.userAgent.toLowerCase().match(/(ipad)/)){
        renderRandomMobileAnnoyingShit();
        return;
    }

    if(window.innerWidth < 500){
        renderRandomMobileAnnoyingShit();
    } else {
        renderRandomPCAnnoyingShit();
    }

    console.log('wut');
}

function renderRandomPCAnnoyingShit() {
    var random = Math.floor((Math.random() * 2) + 1);
    if (navigator.userAgent.toLowerCase().match(/(chrome)/)){
        switch (parseInt(random)){
            case 1:
                console.log('pc_modal');
                homescreen_pc_modal();
                break;

            case 2:
                console.log('pc_bottom');
                homescreen_pc_bottom();
                break;

            default:
                console.log('DEFAULT');
                homescreen_pc_bottom();
        }
    }
}

function renderRandomMobileAnnoyingShit(){
    var random = Math.floor((Math.random() * 2) + 1);
    if (navigator.userAgent.toLowerCase().match(/(safari)/) || navigator.userAgent.toLowerCase().match(/(chrome)/)){
        switch (parseInt(random)){
            case 1:
                console.log('bottom');
                homescreen_bottom_mobile();
                break;

            case 2:
                console.log('modal');
                homescreen_modal_mobile();
                break;

            default:
                console.log('DEFAULT');
                homescreen_bottom_mobile();
        }
    }

}

function antiVelosiped(){
    $('.side_right').css('margin-top', 0);
}

function showHowToDesktopModal() {
    cookieManager.set(MAIN_SCREEN_ADDED, 1, {expires: MAIN_SCREEN_ADDED_TIMEOUT, path: '/'});
    cookieManager.set(MAIN_SCREEN_ADDED_CHECK, 1, {expires: MAIN_SCREEN_ADDED_TIMEOUT*10, path: '/'});
    var w = $('main > .wrapper').width();
    if(!w){
        w = $('body').width();
    }
    var path = '/ajax/add_homescreen';
    var h = 480;
    if(window.innerWidth < 500){
        h = 312;
        path = '/ajax/add_homescreen_mobile'
    }
    if (navigator.userAgent.toLowerCase().match(/(ipad)/)) {
        path = '/ajax/add_homescreen_ipad';
        h = 600;
    }
    if (navigator.userAgent.toLowerCase().match(/(iphone)/)) {
        path = '/ajax/add_homescreen_iphone';
    }
    if (navigator.userAgent.toLowerCase().match(/(android)/)) {
        path = '/ajax/add_homescreen_mobile';
        h = 249;
    }
    $('<div></div>').load(path).modal(
        {
            containerId : "aiwincss",
            containerCss:{
                width: w,
                height: h,
                overflow: "hidden",
                textAlign: "center"
            },
            overlayClose:true,
            overlayCss:{
                backgroundColor:"#000000"
            }
        });
    $(window).trigger('resize');
    return false;
}

function getAdblockByType(type){
    console.log(type);
    var temp;
    switch (parseInt(type)){
        case TYPE_ANNOYING:
            console.log('ANNOYING');
            temp = fixed;
            break;


        case TYPE_BLOCKPAGE:
            console.log('BLOCK');

            temp = fixed;
            break;

        case TYPE_FIXED:
            console.log('FIXED');

            temp = fixed;
            break;

        case TYPE_INNER:
            console.log('INNER');

            temp = inner;
            break;

        default:
            console.log('DEFAULT');

            temp = fixed;
    }

    return temp;
}

function getRandomAdblockType() {
    var chances = {
        'fixed': 0,
        'inner': 60
    };

    var current = fixed;

    var type = TYPE_FIXED;

    var random = Math.floor((Math.random() * 100) + 1);
    if (random > chances.inner) {
        current = inner;
        type = TYPE_INNER;
    } else {
        current = fixed;
    }

    var block_timer = 20;

    cookieManager.set('block_type', type, {expires: block_timer, path: '/'});

    return current;
}

function showHowToModal() {
    var w = $('main > .wrapper').width();
    if(!w){
        w = $('body').width();
    }
    var h = 412;
    var path = '/ajax/adblock';
    if(window.innerWidth < 500){
        path = '/ajax/adblock_mobile'
    }
    $('<div></div>').load(path).modal(
        {
            containerId : "aiwincss",
            containerCss:{
                width: w,
                height:h,
                overflow: "hidden"
            },
            overlayClose:true,
            overlayCss:{
                backgroundColor:"#000000"
            }
        });
    if(yaCounter28929580 !== undefined) yaCounter28929580.reachGoal(ADB_STATS_GUIDE_CLICK);

    return false;
}


