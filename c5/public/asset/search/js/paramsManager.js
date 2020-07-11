var CHECKED = 1;
var NO_CHECKED = 0;
var distance = [500, 1000, 2000, 3000];

var paramsManager = (function () {

    var h1 = document.getElementsByTagName("h1")[0]['text'];

    var fparams = {};
    fparams.page = NO_CHECKED;
    fparams.worktime = NO_CHECKED;
    fparams.workdays = NO_CHECKED;
    fparams.time = NO_CHECKED;
    fparams.is24x7 = NO_CHECKED;
    fparams.street = NO_CHECKED;
    fparams.district = NO_CHECKED;
    fparams.site = NO_CHECKED;
    fparams.review = NO_CHECKED;
    fparams.photo = NO_CHECKED;
    fparams.rubric = NO_CHECKED;
    fparams.tag = NO_CHECKED;
    fparams.near = NO_CHECKED;
    fparams.by_title = NO_CHECKED;
    fparams.by_rating = NO_CHECKED;
    fparams.by_distance = NO_CHECKED;
    fparams.filial = NO_CHECKED;
    fparams.card = NO_CHECKED;
    fparams.distance = distance[1];
    fparams.attributes = {};
    fparams.center;
    fparams.zoom = 12;

    return {
        clear: function (type) {
            fparams[type] = NO_CHECKED;
            userInterface.setFilters();
        },
        setFromUrl: function () {
            var hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for (var i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                if (hash[0] != 'page')
                    paramsManager.set(hash[0], hash[1])
                else
                    fparams.page = hash[1]
            }
        },
        setDefault: function () {
            for (var type in fparams) {
                paramsManager.set(type, NO_CHECKED);
                paramsManager.clearUrl();
            }
        },
        toggle: function (type) {
            if (paramsManager.get(type) == NO_CHECKED) {
                paramsManager.set(type, CHECKED);
            } else {
                paramsManager.set(type, NO_CHECKED);
            }
        },
        getAll: function () {
            return fparams;
        },
        getTextQuery: function () {
            return $('.map__search-input-name').val();
        },
        setPage: function (page) {
            fparams.page = page;
            paramsManager.setUrl('page');
        },
        toggleDay: function (day) {
            fparams.workdays = day;
        },
        set: function (type, value) {
            if (paramsManager.get(type) != value) {
                paramsManager.setPage(NO_CHECKED);
                return fparams[type] = value;
            }
            return false;
        },
        toggleAtribute: function (atrribute) {
            if (fparams.attributes[atrribute]) {
                delete fparams.attributes[atrribute];
            } else {
                fparams.attributes[atrribute] = atrribute;
            }
        },
        get: function (type) {
            return fparams[type];
        },
        getMapCenter: function () {
            var center = fparams['center'];
            if (typeof center === 'string')
                return center ? center.split(',') : '';
            return center;
        },
        setUrl: function (type) {
            var base = [location.protocol, '//', location.host, location.pathname].join(''),
                query = document.location.search,
                param = paramsManager.get(type) != NO_CHECKED ? type + '=' + paramsManager.get(type) : '',
                params = '?' + param;

            if (query) {
                var keyRegex = new RegExp('([\?&])' + type + '[^&]*');
                if (query.match(keyRegex) !== null) {
                    params = query.replace(keyRegex, "$1" + param);
                } else {
                    params = param != '' ? query + '&' + param : query;
                }
            }
            params = params.replace(/&$/, '');
            var url = base + params;
            url = url.replace(/\?$/, '');
            window.history.replaceState({}, "", url);
        },
        clearUrl: function () {
            var base = [location.protocol, '//', location.host, location.pathname].join('');
            window.history.pushState('', '', base);
        }

    }
}());