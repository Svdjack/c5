var gisRegion;
var ajaxManager = (function () {

    return {
        setFirm: function (id) {
            $('.map__info-content').css('opacity', '0.5');
            $.ajax({
                url: '/search/firm/' + id,
                type: 'POST',
                success: function (html) {
                    //var prev_card = paramsManager.get('card');
                    $('.map__info-card, .frame__mover').remove();
                    $('.map__info').append(html);
                    $('.map__info-card').show(400);
                    $('.map__info-card').addClass('left-to-right');
                    userInterface.cardBlockRoll();
                    userInterface.setTitle(title);
                    userInterface.setH1(h1);
                    userInterface.setDescription(description)
                    paramsManager.set('card', id);
                    paramsManager.setUrl('card');
                    $('.map__info-list-item').removeClass('active');
                    $('[data-id="' + id + '"]').addClass('active');
                    if (mapManager.isYandexMap()) {
                        mapManager.redrawPoints();
                    }
                    mapManager.setDefaultMarker(id);
                    mapManager.setVisitMarker(id);
                    $('.map__info-content').css('opacity', '1');
                    userInterface.saveActiveFirm(id);
                    
                    if (jQuery(window).width() > 800) {
                        mapManager.moveMapTo(id, null, 0);
                    }
                    
                    try {
                        let h = jQuery('.map__info-card-rating').height() + jQuery('.map__info-card-title').height() + 22;
                        jQuery('.map__background').height(h);
                    } catch (e) {
                        console.error(e);
                    }
                    
                },
                error: function () {
                    $('.map__info-content').css('opacity', '1');
                    return false;
                }
            });
        },
        setFilters: function () {
            $('.map__info-content').css('opacity', '0.5');
            var data = paramsManager.getAll();
            data.city = City.url;
            $.ajax({
                url: '/search/ajax/' + paramsManager.getTextQuery(),
                type: 'GET',
                data: data,
                success: function (html) {
                    $('.map__info-content').html(html);
                    $('.map__info-content').css('opacity', '1');
                    mapManager.redrawPoints();
                    userInterface.setTitle(title);
                    userInterface.setH1(h1);
                    userInterface.setDescription(description);
                },
                error: function () {
                    $('.map__info-content').css('opacity', '1');
                    FirmList = [];
                    userInterface.setCounters();
                    mapManager.redrawPoints();
                    return false;
                }
            });
        },

        getPoints: function (callback) {
            var data = paramsManager.getAll();
            data.city = City.url;
            $.ajax({
                url: '/search/markers/' + paramsManager.getTextQuery(),
                type: 'GET',
                data: data,
                success: function (markers) {
                    FirmList = markers;
                    callback();
                    userInterface.setCounters();
                },
                error: function () {
                    return false;
                }
            });
        },

        liveSearch: function (value) {
            var cityId = City.id;
            $('#search-autocomplete').empty();
            if (!gisRegion) {
                ajaxManager.getGisRegion();
            } else {
                ajaxManager.gisSuggest(value);
            }

            $('.close-button').addClass('load');
            // $.ajax({
            //     url: '/search/ajax_search',
            //     type: 'POST',
            //     data: {city_id: cityId, text: value},
            //     success: function (html) {
            //         $('.close-button').removeClass('load');
            //         userInterface.liveSearchResult(html);
            //     }
            // });
        },
        getGisRegion: function () {
            $.ajax({
                url: 'https://catalog.api.2gis.ru/2.0/region/search?q=' + City.lon + ',' + City.lat + '&key=ruoedw9225',
                type: 'GET',
                success: function (result) {
                     if (result && result.result && result.result.items) {
                        gisRegion = result.result.items[0];
                    }
                }
            });
        },
        gisSuggest: function (query) {
            $.ajax({
                url: 'https://catalog.api.2gis.ru/2.0/suggest/list?key=rutnpt3272&region_id=' + gisRegion.id + '&locale=ru_RU&q=' + query,
                type: 'GET',
                success: function (result) {
                    var list = '';
                    $.each(result.result.items, function (index, value) {
                        var text = value.hint.text;
                        list += '<li><a href="/' + City.url + '/поиск/' + text + '">' + text + '</a></li>';
                    });
                    list = '<ul>' + list + '</ul>'
                    userInterface.liveSearchResult(list);
                    $.post("/search/live", {result: result, city: City}, function () {});
                }
            });
        }
    }
}());
