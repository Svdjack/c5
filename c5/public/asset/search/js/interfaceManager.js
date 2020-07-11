const KEY_ENTER = 13;
const KEY_UP = 38;
const KEY_DOWN = 40;
const KEY_ESC = 27;

var userInterface = (function () {
    return {
        searchHistorySelect: function (event) {
            var active_index = $('#search-autocomplete a').index($('.active'));
            var r_list = $('#search-autocomplete a');
            r_list.removeClass('active');
            if (event.keyCode == KEY_ESC) {
                $('.search__history ul').remove();
                $('#search-form input').blur();
            }
            if (event.keyCode == KEY_DOWN) {
                if (active_index >= r_list.length - 1)
                    active_index -= r_list.length;
                active_index++;
                r_list.eq(active_index).addClass('active');
            }
            if (event.keyCode == KEY_UP) {
                active_index--;
                if (active_index < 0)
                    active_index += r_list.length;
                r_list.eq(active_index).addClass('active')
            }

            if (event.keyCode == KEY_ENTER && active_index >= 0) {
                var url = r_list.eq(active_index).attr('href');
                document.location.href = url;
                $('#search-autocomplete').empty();
                event.preventDefault();
            }
        },
        liveSearchResult: function (result) {
            $('#search-autocomplete').html(result);
        },
        cardBlockRoll: function () {

            var cardWrap = $('.js-map__info-card'),
                cardBlockRollWorktime = cardWrap.find('.js-map__info-card-block-worktime'),
                cardBlockRollPhone = cardWrap.find('.js-map__info-card-block-phone'),
                cardCloseButton = cardWrap.find('.map__info-card-trigger--close'),
                cardRollButton = cardWrap.find('.map__info-card-trigger--roll, .on-map'),
                infoTriggers = $('.map__info-header-trigger');

            var mapInfoWrap = $('.js-map__info'),
                mapInfoHeader = mapInfoWrap.find('.map__info-header'),
                mapInfoContent = mapInfoWrap.find('.map__info-content'),
                mapCardIcon = mapInfoWrap.find('.map__info-card-icon'),
                mapInfoIcon = mapInfoWrap.find('.map__info-icon');


            cardBlockRollWorktime.click(function () {
                $('.contacts__info-wortime').slideToggle();
                //$(this).hide();
            });

            cardBlockRollPhone.click(function () {
                $('.map__info-card-block-phone').toggleClass('hide');
                $('.map__info-card-block-notice').toggleClass('hide');
                //$(this).hide();
            });

            cardCloseButton.click(function () {
                $('.frame__mover').remove();
                cardWrap.hide();
                infoTriggers.show();
                userInterface.setTitle(startTitle);
                paramsManager.clear('card');
                paramsManager.setUrl('card');
            });

            cardRollButton.click(function () {
                $('.frame__mover').hide();
                cardWrap.hide();
                mapInfoWrap.addClass('rolled');
                mapInfoHeader.hide();
                mapInfoContent.hide();
                mapCardIcon.show();
                mapInfoIcon.show();
            });
            mapCardIcon.click(function () {
                mapInfoWrap.removeClass('rolled');
                $('.frame__mover').show();
                mapInfoHeader.show();
                mapInfoContent.show();
                mapCardIcon.hide();
                mapInfoIcon.hide();
                cardWrap.show();
            });
            
            cardWrap.find('.map__info-card-title a, .map__info-card-review-link--more').click(function () {
                userInterface.saveActiveFirm(localStorage.getItem('activeFirm'));
            });
        },

        cardHide: function () {
            var card = $('.js-map__info-card, .frame__mover');
            card.hide();
        },

        slideCard: function () {
            var leftClass = 'left';
            var mover = $('.frame__mover');
            var card = $('.js-map__info-card');
            if (mover.hasClass(leftClass)) {
                mover.html('&#10096;');
            } else {
                mover.html('&#10097;');
            }
            card.toggleClass(leftClass);
            mover.toggleClass(leftClass);
        },

        setFilters: function () {
            var params = paramsManager.getAll();
            for (var type in params) {
                var filter = $('[data-type=' + type + ']');
                if (paramsManager.get(type) == NO_CHECKED) {
                    filter.removeClass('active');
                    filter.removeAttr('checked');
                }
                else {
                    filter.addClass('active');
                }
            }
            userInterface.distanceButtons();
            ajaxManager.setFilters();
        },
        setMyAddress: function (address) {
            if (address) {
                $('#address').val(address);
            }
        },
        setCounters: function () {
            //$('.company-count span').text(FirmList.length);
            //$(".company-count span").fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);

            $('.company-count span').each(function () {
                $(this).prop('Counter', 0).animate({
                    Counter: FirmList.length
                }, {
                    duration: 100,
                    easing: 'swing',
                    step: function (now) {
                        $(this).text(Math.ceil(now));
                    }
                });
            });

        },
        refreshSelectFilters: function () {
            if (window.streets && !paramsManager.get('street')) {
                var i;
                var list = '<option value="0">Улица</option>';
                for (i = 0; i < window.streets.length; ++i) {
                    var street = window.streets[i];
                    list += '<option value="' + street.url + '">' + street.name + '</option>';
                }
                $('select[data-type="street"]').html(list);
                jQuery('select[data-type="street"]').styler('destroy');
                jQuery('select[data-type="street"]').styler();
            }
            if (window.districts && !paramsManager.get('district')) {
                var i;
                var list = '<option value="0">Район</option>';
                for (i = 0; i < window.districts.length; ++i) {
                    var district = window.districts[i];
                    list += '<option value="' + district.url + '">' + district.name + '</option>';
                }
                $('select[data-type="district"]').html(list);
                jQuery('select[data-type="district"]').styler('destroy');
                jQuery('select[data-type="district"]').styler();
            }
            if (window.rubric_list && !paramsManager.get('rubric')) {
                var i;
                var list = '<option value="0">Категория</option>';
                for (i = 0; i < window.rubric_list.length; ++i) {
                    var rubric = window.rubric_list[i];
                    list += '<option value="' + rubric.id + '">' + rubric.name + '</option>';
                }
                $('select[data-type="rubric"]').html(list);
                jQuery('select[data-type="rubric"]').styler('destroy');
                jQuery('select[data-type="rubric"]').styler();
            }
            if (window.tag_list && !paramsManager.get('tag')) {
                var i;
                var list = '<option value="0">Ключевое слово</option>';
                for (i = 0; i < window.tag_list.length; ++i) {
                    var tag = window.tag_list[i];
                    list += '<option value="' + tag.id + '">' + tag.name + '</option>';
                }
                $('select[data-type="tag"]').html(list);
                jQuery('select[data-type="tag"]').styler('destroy');
                jQuery('select[data-type="tag"]').styler();
            }
        },
        setTitle: function (title) {
            document.title = title;
        },
        setH1: function (h1) {
            $('h1').text(h1);
        },
        setDescription: function (description) {
            document.querySelector('meta[name="description"]').setAttribute("content", description);
        },
        showMessage: function (message) {
            message = $('<div class="popup-message">' + message + '</div>');
            $('.l-page-container').append(message);
            message.slideDown();
            setTimeout(function () {
                message.remove();
            }, 2000)
        },

        distanceButtons: function () {
            $('.range').hide();
            if (paramsManager.get('near') == CHECKED) {
                $('.range').show();
            }
        },
        saveActiveFirm: function (firm) {
            localStorage.setItem('activeFirm', firm);
            localStorage.setItem('activeFirmLink', document.location.href);
            localStorage.setItem('activeFirmExpire', +(new Date()) + 180000);
        },
        onclickMapAny: function () {
            if (jQuery(window).width() < 800) {
                jQuery('.on-map').click();
            }
        }
    }
}());
