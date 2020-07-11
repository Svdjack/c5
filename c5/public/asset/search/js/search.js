/* global mapManager, paramsManager, userInterface, DG */

var isMob = window.innerWidth <= 600;

var firmInList = '.map__info-list-item';
var firmList = '.map__info-content';
var fastFilter = '.filter-item';
var attributes = '.filter-attribute';
var distanceButton = '.distance-button';
var pagerItem = '.paging__item';
var clearFilters = '.map__info-filter-clear';
var filterSelect = '.filter-select';
var myPosition = '.map__search-location';
var inputClear = '.input-clear';
var positions = '.map__search-location-input input';
var createWay = '.get-way';
var workDay = '.filter-by-days .map__info-filter-field-item';
var timeFilter = '.filter-time';
var isOpen = '[data-type="worktime"]';
var is24x7 = '[data-type="is24x7"]';
var searchForm = '#search-form';
var searchFormClear = '.close-button';
var printButton = '.i-print';
var shareButton = '.i-share';
var filterButton = '.js-map__info-header-button-filter';
var closeButton = '.map__info-header-trigger--close';
var firmAddress = '#firm-address';
var firmFilials = '[data-type="filial"]';
var back = '.search__back';
var slideCard = '.frame__mover';
var zoomPlus = '.map__zoom-item--plus';
var zoomMinus = '.map__zoom-item--minus';
var emtyResultLocation = '.empty-result-location';
var liveSearchLinks = '.search__history a';
var firmComment = '#comment';
var firmComments = '.kotel-review';
var yandexMapClose = '.map__zoom-item.map__zoom-item--close';

$(document).ready(function () {
    paramsManager.setFromUrl();
    userInterface.cardBlockRoll();
          
    setTimeout(() => {        
        let inp = jQuery('input#search');

        if (inp.val()) {
            return;
        }

        jQuery('input#search').focus();
    }, 400);

    var value = paramsManager.getTextQuery();
    if (value.search('рядом') > 0) {
        setTimeout(function () {
            $('[data-type="near"]').click();
        }, 1000);
    }

    if (document.referrer == '') {
        $(back).hide();
    }

    DG.then(function () {
        mapManager.createMap();
        suggestions();
    })


    $(document).on('click', yandexMapClose, function () {
        mapManager.redrawPoints();
    });

    $(document).on('click', firmComments, function () {
        $('.map__info-card-block-content--comments').fadeToggle();
    });

    $(document).on('tap', '.map__search-wrap.rolled', function () {
        $(this).removeClass('rolled');
    });
    $(document).on('click', '.map__search-wrap.rolled', function () {
        $(this).removeClass('rolled');
    });

    $(firmList).bind('scroll', function () {
        if ($('.map__info-list').offset().top < 0 && !$('.map__search-wrap').hasClass('rolled')) {
            $('.map__search-wrap').addClass('rolled')
        } else if ($('.map__info-list').offset().top > 0 && $('.map__search-wrap').hasClass('rolled')) {
            $('.map__search-wrap').removeClass('rolled');
        }
    });

    $(document).on('click', liveSearchLinks, function () {
        document.location.href = $(this).attr('href');
    });

    $(document).on('click', searchFormClear, function () {
        $('#search').val('');
        $(this).hide();
    });

    $(document).on('click', emtyResultLocation, function () {
        $(myPosition).click();
    });

    $(document).on('click', zoomPlus, function () {
        mapManager.zoomPlus();
    });
    $(document).on('click', zoomMinus, function () {
        mapManager.zoomMinus();
    });

    $('ymaps, .map__info').click(function () {
        $('.map__region-list--full').hide();
    });

    $(document).on('click', back, function () {
        history.back();
    });

    $(document).on('click', slideCard, function () {
        userInterface.slideCard();
    });

    $(document).on('submit', searchForm, function (event) {
        var value = $(searchForm).find('input').val();
        if (value)
            window.location.href = '/' + City.url + '/поиск/' + value;
        event.preventDefault();
    })

    $(document).on('click', closeButton, function () {
        $(searchForm).find('input').val('');
        $('.map__info,.map__region').remove();
        FirmList = [];
        mapManager.redrawPoints();
        userInterface.setH1('Поиск по сайту');
        userInterface.setDescription('Поиск по сайту')
        userInterface.setTitle('Поиск по сайту');
    })

    $(document).on('click', firmFilials, function (event) {
        event.preventDefault();
        event.stopPropagation();
        var id = $(this).data('id');
        paramsManager.setDefault();
        paramsManager.set('type', 'filial');
        paramsManager.set('filial', id);
        paramsManager.setUrl('type');
        paramsManager.setUrl('filial');
        userInterface.setFilters();
    })
    
    $(document).on('click', firmAddress, function (event) {
        event.preventDefault();
        userInterface.cardHide();
        var id = $(this).data('id');
        mapManager.setVisitMarker(id);
        mapManager.showMarker(id);
    })

    $(document).on('click', firmInList + ' .map__info-list-item-title', function (event) {
        if ($(this).parent().hasClass('category-item')) {
            window.location.href = $(this).attr('href');
            return false;
        }
        event.preventDefault();
    })

    $(document).on('click', firmInList, function () {
        var id = $(this).data('id');
        ajaxManager.setFirm(id);
        $('.map__info-filter').hide();
        $(filterButton).removeClass('active');
        mapManager.setFirmActive(id);
        mapManager.setVisitMarker(id);
        mapManager.showMarker(id);
        
        if (jQuery(window).width() > 1000) {            
            mapManager.moveMapTo(id, null, 0);
        }
    })
    $(document).on('click', filterButton, function () {
        $('.map__info-card, .frame__mover').remove();
        paramsManager.set('card', '');
        paramsManager.setUrl('card');
        userInterface.setTitle(startTitle);
        userInterface.setH1(h1);
        userInterface.setDescription(startDescription);
    })

    $(document).on('mouseenter', firmInList, function () {
        var firm_id = $(this).data('id');
        mapManager.setActiveMarker(firm_id);
    });

    $(document).on('mouseleave', firmInList, function () {
        var index = $(this).data('id');
        mapManager.setDefaultMarker(index);
    });

    $(document).on('click', distanceButton, function () {
        paramsManager.set('distance', $(this).data('distance'));
        userInterface.setFilters();
    });

    $(document).on('click', attributes, function () {
        var id = $(this).data('id');
        paramsManager.toggleAtribute(id);
        userInterface.setFilters();
    });

    $(document).on('click', fastFilter, function () {
        var type = $(this).data('type');
        let typeMode = $(this).data('type-mode');
        if (type == 'near' && !typeMode) {
            $(this).data('type-mode', true);
            const current = mapManager.getCurrentPosition();
            console.log(current);
            localStorage.setItem('saveLat', current['lat']);
            localStorage.setItem('saveLng', current['lng']);
            localStorage.setItem('saveZoom', mapManager.getMap().getZoom());
            if (!cookieManager.get('lat')) {
                geocodeManager.getMyPosition(function () {
                    paramsManager.toggle(type);
                    mapManager.drawMyPosition(true);
                    userInterface.setFilters();
                });
            } else {
                paramsManager.toggle(type);
                userInterface.setFilters();
                mapManager.drawMyPosition(true);
            }
        } else {
            $(this).data('type-mode', false);
            mapManager.getMap().setView([
                localStorage.getItem('saveLat'),
                localStorage.getItem('saveLng')
            ],
                    localStorage.getItem('saveZoom')
                    );
            paramsManager.toggle(type);
            userInterface.setFilters();
        }
    })

    { //Часы работы
        $(document).on('click', timeFilter, function () {
            if ($(this).hasClass('active')) {
                return;
            }
            var value = $('#amount').val();
            var type = $(this).data('type');
            var day = new Date().getDay() - 1;
            $('.map__info-filter-field--days, .map__info-filter-field-slider').css('opacity', 1);
            $(workDay).removeClass('active');
            $(workDay + ":eq(" + day + ")").addClass('active');
            paramsManager.set('is24x7', NO_CHECKED);
            paramsManager.set('worktime', NO_CHECKED);
            paramsManager.set(type, value);
            paramsManager.toggleDay(day);
            userInterface.setFilters();
        })

        $(document).on('click', workDay, function () {
            if (!$(timeFilter).hasClass('active')) {
                return;
            }
            $(workDay).removeClass('active');
            $(this).addClass('active');
            var day = $(this).index();
            paramsManager.set('is24x7', NO_CHECKED);
            paramsManager.set('worktime', NO_CHECKED);
            paramsManager.toggleDay(day);
            userInterface.setFilters();
        })

        $(document).on('click', isOpen + ',' + is24x7, function () {
            $('.map__info-filter-field--days, .map__info-filter-field-slider').css('opacity', 0.4);
            paramsManager.set('time', NO_CHECKED);
            paramsManager.set('workday', NO_CHECKED);
            $(timeFilter).removeClass('active');
            $(workDay).removeClass('active');
        })
    }

    $(document).on('click', pagerItem, function (event) {
        var page = $(this).text();
        if ($.isNumeric(page)) {
            paramsManager.setPage(page);
            userInterface.setFilters();
        }
        event.preventDefault();
    })

    $(document).on('click', clearFilters, function () {
        paramsManager.setDefault();
        userInterface.setFilters();
        userInterface.refreshSelectFilters();
    })

    $(document).on('click', filterSelect, function () {
        var value = $(this).find(':selected').val();
        var type = $(this).data('type');


        if (paramsManager.set(type, value)) {
            if (type == 'district') {
                paramsManager.set('street', NO_CHECKED);
            }
            userInterface.setFilters();
            setTimeout(function () {
                userInterface.refreshSelectFilters();
                mapManager.flyTo([FirmList[0]['lat'],FirmList[0]['lon']], 13);
            }, 400);
        }

    })

    {  //Маршрут позиция
        $(document).on('click', myPosition, function () {
            geocodeManager.getMyPosition();
            mapManager.drawMyPosition(true)
        })

        $(document).on('click', inputClear, function () {
            $(this).parent().find('input').val('');
            mapManager.redrawPoints();
        })

        $(document).on('click', createWay, function () {
            var to = $(this).parents('.map__info-list-item, .map__info-card').find('.map__info-list-item-address, .map__info-card-block--address .map__info-card-block-content').text();
            var from = geocodeManager.getCoordinates() ? geocodeManager.getCoordinates() : geocodeManager.getCurrentAddress();
            if (!from) {
                geocodeManager.getMyPosition(function () {
                    mapManager.createWay(geocodeManager.getCoordinates(), City.name + ',' + to);
                });
            }
            else {
                if (geocodeManager.getCurrentAddress()) {
                    $('#address').val(geocodeManager.getCurrentAddress());
                }
                else {
                    geocodeManager.setAddressByCoords();
                }
                mapManager.createWay(from, City.name + ',' + to);
            }
            $('#to').val(to.replace(/\s+/g, ' ').trim());
            $('.map__search-location-input--route.route-to').show();
            if (!isMob) {
                $('.map__search-location-input-wrap').show();
            } else {
                $('.on-map').click();
            }
            return false;
        })

        $(positions).keypress(function (event) {
            if (event.keyCode == KEY_ENTER) {
                var from = $('#from').val();
                from = from ? from : $('#address').val()
                var to = $('#to').val();
                if (to)
                    mapManager.createWay(from, to);
                var address = $('#address').val();
                if (geocodeManager.getCurrentAddress() != address) {
                    geocodeManager.setMyAddress(address);
                }
                $('.map__search-location-input-wrap').hide();
            }
        })
    }

    { //Live search

        $(searchForm).find('input').keyup(function (event) {
            var value = $(this).val();
            var key = event.keyCode;
            if (value.length == 0) {
                $(searchFormClear).hide();
            }
            if (value.length == 1) {
                $(searchFormClear).show();
            }
            if (value.length > 2 && key != KEY_DOWN && key != KEY_UP && key != KEY_ESC && key != KEY_ENTER) {
                ajaxManager.liveSearch(value);
            } else {
                userInterface.searchHistorySelect(event);
            }
        })
        $(searchForm).find('input').focusin(function () {
            var value = $(this).val();
            ajaxManager.liveSearch(value);
        });
        $(searchForm).find('input').focusout(function () {
            setTimeout(function () {
                $('.search__history ul').remove();
            }, 200)

        })

    }

    { //Services
        $(document).on('click', printButton, function () {
            if (paramsManager.get('card')) {
                var link = $('.map__info-card-review-link--more').attr('href');
                var win = window.open(link + "?print", '_blank');
                win.focus();
            } else {
                window.print()
            }
        })
        $(document).on('click', shareButton, function () {
            $('.map__client-service #share').html('<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>' +
                '<script src="//yastatic.net/share2/share.js"></script>' +
                '<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,twitter,viber,whatsapp,skype,telegram"></div>');
        })
    }

    //Session comments
    {
        $(document).on('focusout', firmComment, function () {
            var text = $(this).val();
            if (text)
                mapManager.setFirmComment(text)
        })
        $(document).on('click', '.gis-label', function (e) {
            var elm = $(this);
            var yPos = e.pageY - elm.offset().top;
            if (yPos > 1 && yPos < 10) {
                elm.remove();
            }
        })
    }

});


