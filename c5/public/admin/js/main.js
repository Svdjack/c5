var AppRouter = Backbone.Router.extend({
    accepted_firms_types: ['new', 'updated', 'approved', 'deleted', 'up'],
    accepted_reviews_types: ['new', 'approved', 'deleted'],
    accepted_users_types: ['created', 'name', 'email'],
    accepted_cities_types: ['name', 'url', 'region'],
    routes: {
        "": "listFirms",
        "firm/:id/attach_user/:user_id": "attachUser",
        "firms/page/:page": "listFirms",
        "firms/add": "addFirm",
        "firms": "listFirms",
        "firm/edit/:id": "firmDetails",
        "firm/delete/:id": "removeFirm",
        "firms/:type/page/:page": "listFirmsWithType",
        "firms/:type": "listFirmsWithType",
        "reviews/:type/page/:page": "listReviewsWithType",
        "reviews/:type": "listReviewsWithType",
        "review/edit/:id": "reviewDetails",
        "review/delete/:id": "removeReview",
        "users/:type": "listUsersWithType",
        "users/:type/page/:page": "listUsersWithType",
        "users/search/:name": "userSearch",
        "user/:id": "userDetails",
        "cities/:type/page/:page": "listCitiesWithType",
        "cities/:type": "listCitiesWithType",
        "city/:id": "cityDetails",
        "about": "about",
        "login": "login",
        "logout": "logout"
    },

    initialize: function () {

        cookieManager.set('admin_edit', 1, {expire: 3600*24*30, path: '/'});

        if (utils.entryPoint == null) {
            utils.entryPoint = window.location.hash
        }

        if(utils.entryPoint == '#login'){
            utils.entryPoint = '';
        }
        
        utils.initializeRegions();
        this.sessionManager = new SessionModel();
        this.sessionManager.checkAuth();
        $(document).on('sidebar', function () {
            utils.initializeCounters();
        });
        this.sideView = new SideView();
        $('#left-sidebar-nav').html(this.sideView.el);

        

        $(document)
            .ajaxStart(function () {
                $('#loading-page').show();
            })
            .ajaxStop(function () {
                $('#loading-page').hide();
            });
    },

    attachUser: function (id, user_id) {
        $.ajax({
            url: '/admin_api/firm/' + id + '/attach_user/' + user_id,
            contentType: 'application/json',
            dataType: 'json',
            type: 'GET',
            crossDomain: true,
            success: function () {
                utils.showAlert('Success', 'Компания привязана к пользователю!', 'green');
                window.location.replace('/admin/#firm/edit/' + id);
                // window.location.reload();
            },
            error: function (response) {
                response = JSON.parse(response.responseText);
                utils.showAlert('Error', response.error, 'red');
                window.location.replace('/admin/#firm/edit/' + id);
                // window.location.reload();
            }
        });
    },

    cityDetails: function (id) {
        var city = new City({id: id});
        city.fetch({
            success: function () {
                $("#content").html(new CityView({model: city}).el);
            }
        });
    },

    listCitiesWithType: function (type, page) {
        var p = page ? parseInt(page, 10) : 1;
        if (this.accepted_cities_types.indexOf(type) != -1) {
            this.listCities(p, type);
        }
    },

    listCities: function (page, type) {
        var p = page ? parseInt(page, 10) : 1;
        var t = type ? 'cities-' + type : 'cities';
        var cityList = new CitiesCollection({page: p, type: t});
        cityList.fetch({
            success: function () {
                $("#content").html(new CitiesListView({model: cityList, page: p, type: t}).el);
            }
        });
        this.sideView.selectMenuItem(t);
    },

    listUsersWithType: function (type, page) {
        var p = page ? parseInt(page, 10) : 1;
        if (this.accepted_users_types.indexOf(type) != -1) {
            this.listUsers(p, type);
        }
    },

    userSearch: function (name) {
        var p = 1;
        var t = 'search';
        var userList = new UserSearchCollection({page: p, type: t, name: name});
        userList.fetch({
            success: function () {
                $("#content").html(new UsersListView({model: userList, page: p, type: t}).el);
            }
        });
        this.sideView.selectMenuItem(t);
    },

    listUsers: function (page, type) {
        var p = page ? parseInt(page, 10) : 1;
        var t = type ? 'users-' + type : 'users';
        var userList = new UsersCollection({page: p, type: t});
        userList.fetch({
            success: function () {
                $("#content").html(new UsersListView({model: userList, page: p, type: t}).el);
            }
        });
        this.sideView.selectMenuItem(t);
    },

    listReviewsWithType: function (type, page, search) {
        var p = page ? parseInt(page, 10) : 1;
        let s = search || jQuery('.reviews-search-input:visible').val();
        if (this.accepted_reviews_types.indexOf(type) != -1) {
            this.listReviews(p, type, s);
        }
    },

    listReviews: function (page, type, search) {
        var p = page ? parseInt(page, 10) : 1;
        var t = type ? 'reviews-' + type : 'reviews';
        let s = search || jQuery('.reviews-search-input:visible').val();
        var reviewsList = new ReviewCollection({page: p, type: t, search: s});
        reviewsList.fetch({
            success: function () {
                $("#content").html(new ReviewsListView({model: reviewsList, page: p, type: t}).el);
            }
        });
        this.sideView.selectMenuItem(t);
    },

    listFirmsWithType: function (type, page) {
        var p = page ? parseInt(page, 10) : 1;
        if (this.accepted_firms_types.indexOf(type) != -1) {
            this.listFirms(p, type, jQuery('.firms-search-input:visible').val());
        }
    },


    listFirms: function (page, type, search) {
        var p = page ? parseInt(page, 10) : 1;
        var t = type ? 'firms-' + type : 'firms';
        var firmList = new FirmCollection({page: p, type: t, search: search || jQuery('.firms-search-input:visible').val()});
        firmList.fetch({
            success: function () {
                if (t == 'firms-up')
                    $("#content").html(new FirmsUpListView({model: firmList, page: p, type: t}).el);
                else
                    $("#content").html(new FirmsListView({model: firmList, page: p, type: t}).el);
            }
        });
        this.sideView.selectMenuItem(t);
    },

    firmDetails: function (id) {
        var firm = new Firm({id: id});
        firm.fetch({
            success: function () {
                $("#content").html(new FirmView({model: firm}).el);
            }
        });
        this.sideView.selectMenuItem('firms');
    },

    userDetails: function (id) {
        var user = new User({id: id});
        user.fetch({
            success: function () {
                $("#content").html(new UserView({model: user}).el);
            }
        });
    },

    removeReview: function (id) {
        var review = new Review({id: id});
        review.fetch();
        review.destroy();
        utils.showAlert('Success!', 'Отзыв успешно удален', 'green');
    },

    reviewDetails: function (id) {
        var review = new Review({id: id});
        review.fetch({
            success: function () {
                $("#content").html(new ReviewView({model: review}).el);
            }
        });
        this.sideView.selectMenuItem('reviews-new');
    },

    removeFirm: function (id) {
        var firm = new Firm({id: id});
        firm.fetch();
        firm.destroy();
        utils.showAlert('Success!', 'Компания успешно удалена', 'green');
    },

    addFirm: function () {
        var firm = new Firm();
        $('#content').html(new FirmView({model: firm}).el);
        this.sideView.selectMenuItem('add-menu');
    },


    login: function () {
        $('body').html(new LoginView().el);
    },

    logout: function () {
        $.ajax({
            url: '/admin_api/auth/logout',
            contentType: 'application/json',
            dataType: 'json',
            type: 'POST',
            crossDomain: true,
            xhrFields: {
                withCredentials: true
            },
            success: function (res) {
                window.location.replace('/admin/');
            }
        });
    },

    about: function () {
        if (!this.aboutView) {
            this.aboutView = new AboutView();
        }
        $('#content').html(this.aboutView.el);
        this.sideView.selectMenuItem('about-menu');
    }

});

utils.loadTemplate(['SideView', 'FirmView', 'FirmsListItemView', 'FirmsUpListItemView',
    'AboutView', 'LoginView', 'ReviewsListItemView', 'ReviewView',
    'UsersListItemView', 'UserView', 'CitiesListItemView', 'CityView'], function () {
    app = new AppRouter();
    Backbone.history.start();
});


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

function globalFirmSearch() {
    let route = window.location.hash.substr(1);
    app.navigate('zzz', {trigger: true, replace: true});
    app.navigate(route, {trigger: true, replace: true});
}
function globalReviewSearch() {
    let route = window.location.hash.substr(1);
    app.navigate('zzz', {trigger: true, replace: true});
    app.navigate(route, {trigger: true, replace: true});
}