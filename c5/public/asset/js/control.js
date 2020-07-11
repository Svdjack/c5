String.prototype.russify = function () {
    var lat = ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', '[', ']', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', ';', "'", 'z', 'x', 'c', 'v', 'b', 'n', 'm', ',', '.',
        'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', '{', '}', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', ':', '"', 'Z', 'X', 'C', 'V', 'B', 'N', 'M', '<', '>'];
    var rus = ['й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ъ', 'ф', 'ы', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', 'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю',
        'Й', 'Ц', 'У', 'К', 'Е', 'Н', 'Г', 'Ш', 'Щ', 'З', 'Х', 'Ъ', 'Ф', 'Ы', 'В', 'А', 'П', 'Р', 'О', 'Л', 'Д', 'Ж', 'Э', 'Я', 'Ч', 'С', 'М', 'И', 'Т', 'Ь', 'Б', 'Ю'];

    var replaceString = this;

    for (var i = 0; i < lat.length; i++) {
        replaceString = replaceString.replace(lat[i], rus[i]);
    }

    return replaceString;
};

function fire_target(name) {
    yaCounter28929580.reachGoal(name);
}

// Focus element
var focusElement = {
    // Init mask
    init: function () {
        if ($('#mask').length < 1) {
            $('body').append('<div class="mask" id="mask"></div>')
        }

        $('#mask').on('click', function () {

            $('#mask').fadeOut('fast');

            $('.element-focus').removeClass('element-focus');
        });
    },

    // Show mask
    show: function (e) {
        $('#mask').fadeIn('fast');

        $(e).addClass('element-focus');
    },

    // Hide mask
    hide: function () {
        $('#mask').fadeOut('fast');

        $('.element-focus').removeClass('element-focus');
    }
};

focusElement.init();

$('.js-input-search-focus').on('focus', function () {
    var _this = this;
    
    jQuery(this).parents('header').addClass('header__search-infocus');

    focusElement.show($(this).parent());

    focusElement.show($('.popular'));

    $(document).on('keyup', function (e) {
        if (e.keyCode == 27) {
            focusElement.hide();

            _this.value = '';
            _this.blur();
        }
    });
});

$('.js-input-search-focus').on('blur', function () {
    jQuery(this).parents('header').removeClass('header__search-infocus');
});

var scrolltotop = {
    setting: {startline: 100, scrollto: 0, scrollduration: 1e3, fadeduration: [500, 100]},
    controlHTML: '<img src="/asset/images/arrow_up.png" />',
    controlattrs: {
        offsetx: 5,
        offsety: 5
    },
    anchorkeyword: "#top",
    state: {isvisible: !1, shouldvisible: !1},
    scrollup: function () {
        this.cssfixedsupport || this.$control.css({opacity: 0});
        var t = isNaN(this.setting.scrollto) ? this.setting.scrollto : parseInt(this.setting.scrollto);
        t = "string" == typeof t && 1 == jQuery("#" + t).length ? jQuery("#" + t).offset().top : 0, this.$body.animate({scrollTop: t}, this.setting.scrollduration)
    },
    keepfixed: function () {
        var t = jQuery(window), o = t.scrollLeft() + t.width() - this.$control.width() - this.controlattrs.offsetx,
            s = t.scrollTop() + t.height() - this.$control.height() - this.controlattrs.offsety;
        this.$control.css({left: o + "px", top: s + "px"})
    },
    togglecontrol: function () {
        var t = jQuery(window).scrollTop();
        this.cssfixedsupport || this.keepfixed(), this.state.shouldvisible = t >= this.setting.startline ? !0 : !1, this.state.shouldvisible && !this.state.isvisible ? (this.$control.stop().animate({opacity: 1}, this.setting.fadeduration[0]), this.state.isvisible = !0) : 0 == this.state.shouldvisible && this.state.isvisible && (this.$control.stop().animate({opacity: 0}, this.setting.fadeduration[1]), this.state.isvisible = !1)
    },
    init: function () {
        jQuery(document).ready(function (t) {
            var o = scrolltotop, s = document.all;
            o.cssfixedsupport = !s || s && "CSS1Compat" == document.compatMode && window.XMLHttpRequest, o.$body = t(window.opera ? "CSS1Compat" == document.compatMode ? "html" : "body" : "html,body"), o.$control = t('<div id="topcontrol">' + o.controlHTML + "</div>").css({
                position: o.cssfixedsupport ? "fixed" : "absolute",
                bottom: o.controlattrs.offsety,
                right: o.controlattrs.offsetx,
                opacity: 0,
                cursor: "pointer"
            }).attr({title: "Наверх"}).click(function () {
                return o.scrollup(), !1
            }).appendTo("body"), document.all && !window.XMLHttpRequest && "" != o.$control.text() && o.$control.css({width: o.$control.width()}), o.togglecontrol(), t('a[href="' + o.anchorkeyword + '"]').click(function () {
                return o.scrollup(), !1
            }), t(window).bind("scroll resize", function (t) {
                o.togglecontrol()
            })
        })
    }
};
scrolltotop.init();



function form_populate(namespace, data) {
    jQuery.each(data, function (key, value) {
        if (value) {
            var $input = jQuery('[name="' + namespace + '[' + key + ']"]');
            if ($input.length > 0) {
                $input.val(value);
            }
        }
    });
}

function set_worktime(worktime) {
    $.each(worktime, function (day, value) {
        switch (value.type) {
            case 'rest':
                $('#' + day + '_rest').click();
                break;
            case 'normal_with_rest':
                $('.' + day + ' .add-obed').click();
                break;
            case 'nonstop':
                $('#' + day + '_nonstop').click();
                break;
        }

        var workday = $('.' + day);
        workday.find('.start').val(value.start.replace(/^0+/g, ''));
        workday.find('.end').val(value.end.replace(/^0+/g, ''));
        workday.find('.obed-start').val(value.obed.start.replace(/^0+/g, ''));
        workday.find('.obed-end').val(value.obed.end.replace(/^0+/g, ''));
    });
}

function set_okved(okved) {
    var $okved_field = $('#firm-okved');
    $.each(okved, function (key, value) {
        var new_field = $okved_field.clone();
        new_field.val(value);
        $okved_field.after(new_field);
    });

    var $okveds = $('.okved-field');
    console.log($okveds);
    var okved_count = $okveds.length;
    if (okved_count > 1) {
        $okveds.each(function () {
            if (!$(this).val() && okved_count > 1) {
                $(this).remove();
                okved_count--;
            }
        });
    }

}

function set_children(children) {
    var $child_field = $('#firm-children');
    $.each(children, function (key, value) {
        var new_field = $child_field.clone();
        new_field.val(value.Value);
        $child_field.after(new_field);
    });

    var $children = $('.user-company-children');
    var children_count = $children.length;
    if (children_count > 1) {
        $children.each(function () {
            if (!$(this).val() && children_count > 1) {
                $(this).remove();
                children_count--;
            }
        });
    }
}

function bind_contacts(contacts) {
    var $phone_field = $('#firm-phone');
    var $email_field = $('#firm-email');
    var $fax_field = $('#firm-fax');
    var $site_field = $('#firm-sites');
    $.each(contacts, function (key, value) {
        switch (value.Type) {
            case 'website':
                var new_site = $site_field.clone();
                new_site.val(value.Value);
                $site_field.after(new_site);
                break;

            case 'email':
                var new_email = $email_field.clone();
                new_email.val(value.Value);
                $email_field.after(new_email);
                break;

            case 'fax':
                var new_fax = $fax_field.clone();
                new_fax.val(value.Value);
                $fax_field.after(new_fax);
                break;

            case 'phone':
                var new_phone = $phone_field.clone();
                new_phone.val(value.Value);
                $phone_field.after(new_phone);
                break;
        }
    });

    $('.contact-field').each(function () {
        var $this = $(this);
        if ($this.siblings('.contact-field').length > 0)
            if (!$(this).val()) {
                $(this).remove();
            }
    });

}

function set_region(region) {
    setTimeout(function () {
        console.log(region);
        $('#firm-region').val(region.region_id);
        $('#firm-region').change();
        $(document).on('city-list-generated', function () {
            $('.firm-city-select').val(region.city_id);
            $(document).off('city-list-generated');
        });
    }, 3201);
}

function add_category(id, name) {
    if (id == 0) {
        alert('Выберите категорию!');
        return;
    }
    var cats = $('#categories');

    if (cats.find('input[value=' + id + ']').length > 0) {
        alert('Уже выбрана!');
        return;
    }

    if (cats.find('input').length >= 5) {
        alert('Не больше 5 категорий!');
        return;
    }

    var cat = $('<div/>', {
        'class': 'form-list-item',
        text: name
    });
    var input = $('<input/>',
        {
            type: 'hidden',
            value: id,
            name: 'firm[categories][]'
        });
    var remove_button = $('<span/>', {
        text: 'X',
        'class': 'form-list-remove'
    });
    cat.append(input).append(remove_button);
    cats.append(cat);
}

function add_keyword(name) {
    if (name == '') {
        alert('Введите ключевое слово');
        return;
    }
    var keywords = $('#keywords');

    if (keywords.find('input').length >= 15) {
        alert('Не больше 15 ключевых слов!');
        return;
    }

    if (keywords.find('input[value="' + name + '"]').length > 0) {
        alert('Данное ключевое слово уже выбрано');
        return;
    }

    var key = $('<div/>', {
        'class': 'form-list-item',
        text: name
    });

    var input = $('<input/>', {
        type: 'hidden',
        value: name,
        name: 'firm[keywords][]'
    });

    var remove_button = $('<span/>', {
        text: 'X',
        'class': 'form-list-remove'
    });

    key.append(input).append(remove_button);
    keywords.append(key);
}

(function ($) {


    $(document).ready(function () {

        $('.colorbox-node').colorbox({
            href: $(this).attr('href'),
            width: 600,
            height: 500,
            loop: false,
            iframe: true,
            title: false,
            className: "report-box",
            close: 'Закрыть',
            maxWidth: '99%'
        });

        $(document).on("submit", '#error_form', function (event) {
            event.preventDefault();
            $.ajax($(this).attr('action'), {
                type: 'post',
                data: $(this).serialize(),
                success: submit_report
            });
        });

        function submit_report(data) {
            if (data.error) {
                $('#error_form').find('.form-submit').before('<div style="color:red;font-weight: bold">' + data.error + '<div>');
            }
            if (data.success) {
                $.colorbox.resize({width: "600px", height: "150px"});
                console.log(data);
                $('.error-form').html('<div style="color:green;font-weight: bold">' + data.success + '<div>');
            }
        }

        $(document).on('keydown', '.select-city-input input, #front-page #google-search', function (event) {
            if (event.keyCode == 38 || event.keyCode == 40) {
                var $active = $(this).parents('.select-city-input').find('#drop-down-cities-block > .active');
                if ($active.length == 0) {                    
                    $(this).parents('.select-city-input').find('#drop-down-cities-block > span:first-child').addClass('active');
                } else {
                    if (event.keyCode == 38) {
                        if ($active.prev().length == 0) {
                            return
                        }
                        else {
                            $active.prev().addClass('active');
                            $active.removeClass('active');
                        }
                    }
                    if (event.keyCode == 40) {
                        if ($active.next().length == 0) {
                            return
                        }
                        else {
                            $active.next().addClass('active');
                            $active.removeClass('active');
                        }
                    }
                }
                event.stopPropagation();
                return false;
            }
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });


        $(document).on('click', '#drop-down-cities-block > span', function () {
            window.location.href = '/' + $(this).attr('data-url');
            $keywords_input.val('');
            $('#drop-down-cities-block').html('');
        });

        //$('#drop-down-keywords-block').hide();
        //
        //$keywords_input.on('blur', function(){
        //    $('#drop-down-keywords-block').html('');
        //});

        $(document).on('mouseover', '#drop-down-cities-block > span', function () {
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
        });

        if (('#front-page').length > 0) {
            $.get('/ajax/regionsList', function (data) {
                $('body').append('<div style="display: none">' + data + '</div>');
            });
        }

        $(document).on('keyup', '#front-page #google-search, .select-city-input input', function (event) {
            if (event.keyCode == 38 || event.keyCode == 40) {
                return false;
            }

            var $this = $(this);
            if (event.keyCode == 13) {
                if ($('#drop-down-cities-block > span.active').length != 0) {
                    window.location.href = '/' + $('#drop-down-cities-block > span.active').attr('data-url');
                }
                else {
                    alert('Введите существующий город!')
                }
                $this.val('');
            }

            if ($this.val() == '') {
                $('#drop-down-cities-block').html('');
                return false;
            }

            var search = $this.val().toLowerCase();
            var items = [];

            $.each(window.regions_list, function (key, value) {
                if (items.length < 5)
                    if (value.name.toLowerCase().indexOf(search) !== -1)
                        items.push("<span data-url='" + value.url + "' value='" + value.name + "'>" + value.name + "</span>");
            });
            $('#drop-down-cities-block').html(items.join(""));
            $(this).parent().find('#drop-down-cities-block > span:first-child').addClass('active');
        });

        $('.show_search').click(function () {
            $(this).hide();
            $('.search').show();
            $('.hide_search').css('display', 'inline-block');
        });

        $('.hide_search').click(function () {
            $(this).hide();
            $('.search').hide();
            $('.show_search').css('display', 'inline-block');
        });

        $('.side_right nav .title').click(function () {
            if ($(document).width() < 497) {
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active');
                    $(this).siblings('ul').hide();
                }
                else {
                    $(this).addClass('active');
                    $(this).siblings('ul').show();
                }
            }
        });

        //Сполер у категорий
        var searchObject = {
            container: '.js-result-search-list',
            textContainer: '.spoler-open',
            beginningWithNumber: 5,
            notActiveClass: 'hide-rubric'
        };

        resultSearch.init(searchObject);

        // $('.spoler-open').on('click', function () {
        //     $(this).parent().find('.hide-rubric').addClass('show-rubric');
        //     $(this).parent().find('.hide-rubric').removeClass('hide-rubric');
        //     $(this).removeClass('spoler-open').text('Скрыть').addClass('spoler-close');
        // });
        //
        // $('.spoler-close').on('click', function () {
        //     $(this).parent().find('.show-rubric').addClass('hide-rubric');
        //     $(this).parent().find('.show-rubric').removeClass('show-rubric');
        //     $(this).removeClass('spoler-close').text('Расскрыть').addClass('spoler-open');
        // });


        // фильтры в категориях

        $('#street_select').on('change', function () {
            var val = $(this).val();
            fire_target('filter');
            if (val == 0) {
                window.location = window.location.pathname;
                return;
            }
            window.location = window.location.pathname + '?улица=' + val;
        });

        $('#district_select').on('change', function () {
            var val = $(this).val();
            fire_target('filter');
            if (val == 0) {
                window.location = window.location.pathname;
                return;
            }
            window.location = window.location.pathname + '?район=' + val;
        });


        //День недели

        // if ($('.work-time-block').find('.work-time').length > 0) {
        //     $.ajax({
        //         type: "POST",
        //         url: "/запрос/день-недели",
        //     })
        //         .done(function (data) {
        //             $('.' + data).addClass('today');
        //         });
        // }


        //Кнопка загрузки фоток у компании
        $('.photos-load-form .bred').on('click', function () {
            $('.dropzone').show();
            $(this).hide();
        });

        //Colorbox для Выбора города
        try {

            $('.city-select-link').colorbox({
                href: '/ajax/regionsList',
                width: 1000,
                height: 800,
                maxWidth: '99%',
                opacity: .7,
                fixed: true,
                loop: false,
                title: false,
                className: "tvoyafirma-cbox",
                closeButton: true,
                onOpen: function () {
                    $('#front-page #drop-down-cities-block').remove();
                    jQuery('#city-selector').focus();
                },
                onComplete: function () {
                    jQuery('#city-selector').focus();
                }
            });

            //colorbox для фото у компании
            $('.gallery').colorbox({rel: 'gal'});
        }
        catch (err) {
            console.log('colorbox error')
        }
        $('.main_content .selected').on('click', function () {
            return false;
        });

        //форма компании


        if ($('.node-company-form').length > 0) {
            //выбиралка категорий
            $(document).on('change', '.firm-category-selector', function () {
                var _this = $(this);
                $.getJSON('/ajax/user_company_category/' + _this.val(), function (data) {
                    _this.nextAll('select').remove();
                    if (data.length > 0) {
                        var new_select = $('<select />',
                            {
                                "class": 'form-select firm-category-selector'
                            });
                        $.each(data, function (key, value) {
                            var option = $('<option />', {
                                value: value.id,
                                text: value.name
                            });
                            new_select.append(option);
                        });
                        _this.after(new_select);
                        new_select.trigger('change');
                    }
                });
            });

            $('.add-category').click(function () {
                var select = $(this).closest('.categories-block').find('select:last');
                var name = select.find('option:selected').text();
                add_category(select.val(), name);
            });


            // кейворды

            var $keywords_input = $('#firm-keywords');

            $keywords_input.on('keydown', function (event) {
                if (event.keyCode == 38 || event.keyCode == 40) {
                    var $active = $(this).parent().find('#drop-down-keywords-block > .active');
                    if ($active.length == 0) {
                        $(this).parent().find('#drop-down-keywords-block > span:first-child').addClass('active');
                    }
                    else {
                        if (event.keyCode == 38) {
                            if ($active.prev().length == 0) {
                                return
                            }
                            else {
                                $active.prev().addClass('active');
                                $active.removeClass('active');
                            }
                        }
                        if (event.keyCode == 40) {
                            if ($active.next().length == 0) {
                                return
                            }
                            else {
                                $active.next().addClass('active');
                                $active.removeClass('active');
                            }
                        }
                    }
                    event.stopPropagation();
                    return false;
                }
                if (event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });

            $(document).on('click', '#drop-down-keywords-block > span', function () {
                add_keyword($(this).html());
                $keywords_input.val('');
                $('#drop-down-keywords-block').html('');
            });

            //$('#drop-down-keywords-block').hide();
            //
            //$keywords_input.on('blur', function(){
            //    $('#drop-down-keywords-block').html('');
            //});

            $(document).on('mouseover', '#drop-down-keywords-block > span', function () {
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
            });


            $('.add-keyword').on('click',function () {
                add_keyword($keywords_input.val());
            });

            $keywords_input.on('keyup', function (event) {
                if (event.keyCode == 38 || event.keyCode == 40) {
                    return false;
                }

                var $this = $(this);
                if (event.keyCode == 13) {
                    if ($('#drop-down-keywords-block > span.active').length != 0) {
                        add_keyword($('#drop-down-keywords-block > span.active').html());
                    }
                    else {
                        add_keyword($this.val());
                    }
                    $this.val('');
                }

                if ($this.val() == '') {
                    $('#drop-down-keywords-block').html('');
                    return false;
                }

                var search = $this.val();
                $.getJSON("/ajax/user_company_keywords/" + search, function (data) {
                    var items = [];
                    $.each(data, function (key, value) {
                        items.push("<span value='" + value.name + "'>" + value.name + "</span>");
                    });
                    $('#drop-down-keywords-block').html(items.join(""));
                });
            });

            $(document).on('click', '.form-list-remove', function () {
                $(this).closest('.form-list-item').remove();
            });

            //выбиралка города
            $('#firm-region').change(function () {
                var _this = $(this);
                $('.firm-city-select').remove();
                var city_select = $('<select/>', {
                    'class': 'form-select firm-city-select',
                    name: 'firm[cityid]'
                });

                $.getJSON('/ajax/user_company_region/' + _this.val(), function (data) {
                    $.each(data, function (key, value) {
                        var option = $('<option />', {
                            value: value.id,
                            text: value.name
                        });
                        city_select.append(option);
                    });
                    _this.after(city_select);
                    $(document).trigger('city-list-generated');
                });

            });

            $('.clone-prev').click(function () {
                var tc = $(this).prev();
                var parent = tc.parent('.form-item');
                if (parent.find('input').length > 10) {
                    return alert('Не больше 10 значений');
                }
                tc.after(tc.clone().val(''));
            });

            $('.make-nonstop').change(function () {
                var $this = $(this);
                var type = $this.closest('.work-row').find('.work-type');
                type.val('normal');
                $this.siblings(":not(.obed)").show();
                $this.parent().siblings(":not(.obed)").show();
                if ($this.is(':checked')) {
                    $this.siblings().hide();
                    $this.parent().siblings().hide();
                    $this.siblings('label[for="' + $this.prop('id') + '"]').show();
                    type.val('nonstop');
                }
            });

            $('.make-rest').change(function () {
                var $this = $(this);
                var type = $this.closest('.work-row').find('.work-type');
                type.val('normal');
                $this.parent().siblings(":not(.obed)").show();
                $this.siblings(":not(.obed)").show();
                if ($this.is(':checked')) {
                    $this.siblings().hide();
                    $this.parent().siblings().hide();
                    $this.siblings('label[for="' + $this.prop('id') + '"]').show();
                    type.val('rest');
                }
            });

            $('.add-obed').click(function () {
                var $this = $(this);
                $this.siblings('.obed').show();
                $this.hide();
                var type = $this.closest('.work-row').find('.work-type');
                type.val('normal_with_rest');
            });

            $('.turn-off-obed').click(function () {
                var $this = $(this).parent();
                $this.hide();
                $this.siblings('.add-obed').show();
                var type = $this.closest('.work-row').find('.work-type');
                type.val('normal');
            });
        }

        $(document).on('click', function (event) {
            var _this = $(event.target);
            if (!_this.is('.towns-in-region.active') && $('.towns-in-region').hasClass('active')) {
                console.log(!_this.is('.towns-in-region.active'));
                console.log($('.towns-in-region').hasClass('active'));
                $('.towns-in-region').hide();
                $('.towns-in-region').removeClass('active');
            }
        });

        //Показать города в шапке
        $('.select_city span').click(function (event) {
            event.stopPropagation();
            if ($('.towns-in-region').hasClass('active')) {
                $('.towns-in-region').removeClass('active');
                $('.towns-in-region').hide();
            }
            else {
                $('.towns-in-region').addClass('active');
                $('.towns-in-region').show();
            }
        });


        $(".select_city .active").on('mouseleave', function () {
            $('.towns-in-region').hide();
            $('.towns-in-region').removeClass('active');
        });


        if (window.location.hash == '#send-review') {
            setTimeout(function () {
                $('.reviews_block .add_review .button').click();
            }, 400);

        }

        //показать форму "оставить отзыв"
        $('.reviews_block .add_review .button').click(function () {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $('.reviews_block form').hide();
                $(this).text('Добавить отзыв');
            }
            else {
                $(this).addClass('active');
                $('.reviews_block form').show();
                $(this).text('Свернуть форму');
            }
        });

        $('.rating .button-reviews').click(function () {
            $('.reviews_block form').show();
            $('.reviews_block .add_review .button').addClass('active');
            $('.reviews_block .add_review .button').text('Свернуть форму');

        });

        //Открываем форму при ошибке
        if ($('#reviews-company').find('.error').length > 0) {
            $('#reviews-company form').show();
        }

        //радиокнопки
        $('.reviews_block .form-radios [value="0"]').parent().eq(0).addClass('selected');

        $('.reviews_block .form-radios .form-item label').click(function () {
            $(this).parents('.form-radios').find('.selected').removeClass('selected');
            $(this).parent().eq(0).addClass('selected');
            $(this).parent().eq(0).find('input').attr('checked', 'checked');
            $(this).parent().eq(0).find('input').prop('checked', 'checked');
            return false;
        });

        //говно
        $('.comment-form').on('submit', function () {
            // console.log('arararara');
            $('.comment-form .form-submit').prop('disabled', true);
        });

        //функции для popUp
        $('.mistakes').click(function () {
            $('#overlay').show();
            $('.popUp').show();
        });

        //Разворачиваем карту
        $('.lacation .zoom').on('click', function () {
            window.myMap.container.enterFullscreen()
        });

        //Прикол с звездами в форме отзывов
        $('.comment-form .star-1').click(function () {
            $('[for="edit-field-emotion-und-2"]').click();
        });

        $('.comment-form .star-3').click(function () {
            $('[for="edit-field-emotion-und-0"]').click();
        });

        $('.comment-form .star-5').click(function () {
            $('[for="edit-field-emotion-und-1"]').click();
        });

        function popUp_close() {
            $('#overlay').hide();
            $('.popUp').hide();
        }

        $('.popUp .close').click(popUp_close);
        $('#overlay').click(popUp_close);

        //tabber
        $('.tabber_body').each(function () {
            $(this).find('.tab_content:first').addClass('current');
        });
        $('.tabber_head .tab').click(function () {
            if ($(this).hasClass('selected')) {
            }
            else {
                var index = $(this).index();
                $(this).parent('.tabber_head').find('.selected').removeClass('selected');
                $(this).addClass('selected');
                $(this).parents('.tabber').find('.tabber_body').find('.current').hide().removeClass('current');
                $(this).parents('.tabber').find('.tabber_body').find('.tab_content').eq(index).addClass('current').show();
                return false;
            }
        });

        //скроллинг страницы на клик по меню в конечке
        $('.page_nav a').click(function () {
            //if($(this).parents('.tab').eq(0).hasClass('other')){}
            //else{
            var anchor = $(this).attr('href').replace(/#/, "");
            var new_dist = $('a[name=' + anchor + ']').offset().top;
            $('html ,body').animate({scrollTop: new_dist}, 800);
            return false;
            // }
        });

        $('.search .form-submit').click(function () {
            if ($('[name="city-search"]').length > 0 && !City) {
                var city = $('[name="city-search"]').val();
                var search = $('.search .form-text').val();
                if (!searchText(search, 'site:')) {
                    $('.search .form-text').val("site:" + city + " " + search);
                }
            }
        });
    });

    //

    var resultSearch = {
        init: function (object) {
            var _this = this,
                $resultSearchList = $(object.container),
                textContainer = object.textContainer,
                beginningWithNumber = object.beginningWithNumber,
                notActiveClass = object.notActiveClass;

            for (var i = 0; i < $resultSearchList.length; i++) {
                var resultSearchMore = $($resultSearchList[i]).children(textContainer);

                _this.innerText($(resultSearchMore), 'Еще ' + '(' +
                    _this.countUpElements($resultSearchList[i], beginningWithNumber) +
                    ')', 'removeAttr');

                _this.hideListElements($resultSearchList[i], beginningWithNumber, notActiveClass);
            }

            $(textContainer).click(function () {
                if ($(this).children().attr('data') === 'innerText') {
                    var count = _this.countUpElements($(this).parent(), beginningWithNumber);

                    _this.hideListElements($(this).parent(), beginningWithNumber, notActiveClass);
                    _this.innerText(this, 'Еще' + '(' + count + ')', 'removeAttr');

                } else {
                    _this.showListElements($(this).parent(), notActiveClass);
                    _this.innerText(this, 'скрыть');
                }
            });
        },

        innerText: function (element, text, actionAttr) {
            var $child = $(element)
                .children()
                .text(text);

            if (actionAttr === 'removeAttr') {
                $($child)
                    .removeAttr('data', 'innerText');

            } else {
                $($child)
                    .attr('data', 'innerText');
            }
        },

        hideListElements: function (parentElement, beginningWithNumber, className) {
            var $parent = $(parentElement);

            for (var i = 0; i < $parent.length; i++) {
                var $child = $($parent[i])
                    .children();

                for (var j = beginningWithNumber; j < $child.length - 1; j++) {
                    $($child[j])
                        .addClass(className);
                }
                ;
            }
            ;
        },

        showListElements: function (parentElement, className) {
            var $parent = $(parentElement);

            for (var i = 0; i < $parent.length; i++) {
                var $child = $($parent[i])
                    .children();

                for (var j = 0; j < $child.length; j++) {
                    if ($($child[j])
                            .hasClass(className)) {

                        $($child[j])
                            .removeClass(className);

                    }
                }
            }
        },

        countUpElements: function (element, beginningWithNumber) {
            var $child = $(element)
                    .children(),
                numberElements = 0;

            for (var i = beginningWithNumber; i < $child.length - 1; i++) {
                numberElements++;
            }

            return numberElements
        }
    };

    function searchText(string, needle) {
        return !!(string.search(needle) + 1);
    }

    function init_review() {

        var starfield = $('#starfield');

        for (i = 1; i <= 5; ++i) {
            var star = $('<span></span>').addClass('star');
            star.mouseover(function (index) {
                return function (event) {
                    showStars(index);
                }
            }(i))
            star.click(function (index) {
                return function (event) {
                    setStars(index);
                }
            }(i))
            Stars.push(star)
            starfield.append(star);
        }

        starfield.mouseout(function () {
            showStars(score)
        })

        $('#review_form').submit(function (event) {
            event.preventDefault()
            if (score == 0) {
                $('#starhint_box').removeClass().addClass('score')
                return false;
            }
            if ($('#field_name').val() == '') {
                $('#starhint_box').removeClass().addClass('name')
                return false;
            }
            $.post(
                $(this).attr('action'),
                $(this).serialize(),
                function (data) {
                    addComment(data)
                }
            );
            $(this).remove()
        })
    }

    $(document).ready(init_review);

    var score = 0;
    var Stars = [];

    function addComment(html_data) {
        var block_id = '#tab_good';
        if (score == 3)
            block_id = '#tab_neutral';
        if (score < 3)
            block_id = '#tab_bad';
        $(block_id).append(html_data)

        $('#you_first').hide();
    }

    function showStars(index) {
        var className = 'red';
        if (index == 3)
            className = 'neutral';
        if (index > 3)
            className = 'green';
        if (index == 0)
            className = '';
        for (i = 0; i < Stars.length; ++i) {
            star = Stars[i];
            star.removeClass()
            if (index > i)
                star.addClass(className)
        }
        $('#starhint_box').removeClass().addClass(className)
    }

    function setStars(index) {
        score = index;
        $('#score').val(score);
        $('.form-item-field-emotion-und').removeClass('selected');
        switch (score) {
            case 1:
            case 2:
                $('#edit-field-emotion-und-2').prop('checked', true);
                $('#edit-field-emotion-und-2').closest('.form-item-field-emotion-und').addClass('selected');
                break;

            case 3:
                $('#edit-field-emotion-und-0').prop('checked', true);
                $('#edit-field-emotion-und-0').closest('.form-item-field-emotion-und').addClass('selected');
                break;

            case 4:
            case 5:
                $('#edit-field-emotion-und-1').prop('checked', true);
                $('#edit-field-emotion-und-1').closest('.form-item-field-emotion-und').addClass('selected');
                break;
        }
    }

    if (City) { //Live search
        var gisRegion;
        var form = '.header__form';
        $(form).submit(function (event) {
            event.preventDefault();
            return false;
        });

        $(form).find('input').keyup(function (event) {
            if ($('#front-page-form').length > 0) {
                return false;
            }
            var KEY_ENTER = 13;
            var KEY_UP = 38;
            var KEY_DOWN = 40;
            var KEY_ESC = 27;
            var value = $(this).val();
            $(form).find('input').val(value);
            var key = event.keyCode;

            if (key == KEY_ENTER) {
                var url = $('#search-autocomplete .active').attr('href');

                if (!url && City.url && City.url !== '/') {
                    url = '/' + City.url + '/найти/' + value;
                }

                if (url) {
                    document.location.href = url;
                }

                event.preventDefault();
            }

            if (value.length > 2 && key != KEY_DOWN && key != KEY_UP && key != KEY_ESC && key != KEY_ENTER) {
                liveSearch(value);
            } else {
                userInterface.searchHistorySelect(event);
                event.preventDefault();
            }
        })

        $(form).find('input').focusin(function () {
            var value = $(this).val();
            liveSearch(value);
        })


        function liveSearch(value) {
            if (!gisRegion) {
                getGisRegion();
            } else {
                gisSuggest(value);
            }
        }

        function getGisRegion() {
            $.ajax({
                url: 'https://catalog.api.2gis.ru/2.0/region/search?q=' + City.lon + ',' + City.lat + '&key=ruoedw9225',
                type: 'GET',
                success: function (result) {
                    
                    if(result && result.result && result.result.items) {                    
                        gisRegion = result.result.items[0];
                    }
                }
            });
        }

        function gisSuggest(query) {
            $.ajax({
                url: 'https://catalog.api.2gis.ru/2.0/suggest/list?key=rutnpt3272&region_id=' + gisRegion.id + '&locale=ru_RU&q=' + query,
                type: 'GET',
                success: function (result) {
                    var list = '';
                    $.each(result.result.items, function (index, value) {
                        var text = value.hint.text;
                        
                        if (!City.url) {
                            return;
                        }
                        
                        list += '<li><a href="/' + City.url + '/найти/' + text + '">' + text + '</a></li>';
                    });
                    list = '<ul>' + list + '</ul>'
                    userInterface.liveSearchResult(list);
                    $.post("/search/live", {result: result, city: City}, function () {});
                }
            });
        }

    }


    

})(jQuery);
