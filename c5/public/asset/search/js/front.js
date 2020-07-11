$(document).ready(function () {
    var filters = {
        city: City.url,
        street: 0,
        district: 0,
        near: 0,
        worktime: 0,
        lat: 0,
        lon: 0,
        by_rating: 0,
        by_title: 0
    };
    var query = $('.front-search-page').data('query');
    var cookieLifetime = new Date(new Date().getTime() + 60 * 60 * 12 * 1000);
    var cookieOptions = new Object();
    cookieOptions.path = '/';
    cookieOptions.expires = cookieLifetime;

    $('.front-search-page .filter-select--js').on('change', function () {
        var val = $(this).val();
        var type = $(this).data('type');
        filters[type] = val;
        setFilters();
    });

    $('.front-search-page .filter--js').on('click', function () {
        var val = $(this).val();
        var type = $(this).data('type');
        $(this).toggleClass('btn_active');
        filters[type] ^= val;//toggle

        if (type == 'near' && filters[type]) {
            ymaps.geolocation.get({
                provider: 'auto',
                mapStateAutoApply: true
            }).then(function (result) {
                var iam = result.geoObjects;
                filters['lat'] = iam.position[0];
                filters['lon'] = iam.position[1];
                cookieManager.set('lat', iam.position[0], cookieOptions);
                cookieManager.set('lon', iam.position[1], cookieOptions);
                setFilters();
            });
        } else {
            setFilters();
        }

    });

    function setFilters() {
        $('.firm_list').css('opacity', '0.5');
        $.ajax({
            url: '/search/front-ajax/' + query,
            type: 'GET',
            data: filters,
            success: function (html) {
                $('.firm_list').html(html);
                $('.firm_list').css('opacity', '1');
            },
            error: function () {
                $('.firm_list').css('opacity', '1');
                return false;
            }
        });
    }
});
