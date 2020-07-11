window.Firm = Backbone.Model.extend({

    urlRoot: "/admin_api/firm",

    initialize: function () {
        this.validators = {};

        this.validators.name = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "You must enter a name"};
        };
    },

    validateItem: function (key) {
        return (this.validators[key]) ? this.validators[key](this.get(key)) : {isValid: true};
    },

    // TODO: Implement Backbone's standard validate() method instead.
    validateAll: function () {

        var messages = {};

        for (var key in this.validators) {
            if (this.validators.hasOwnProperty(key)) {
                var check = this.validators[key](this.get(key));
                if (check.isValid === false) {
                    messages[key] = check.message;
                }
            }
        }

        return _.size(messages) > 0 ? {isValid: false, messages: messages} : {isValid: true};
    },

    defaults: {
        id: null,
        name: "",
        address: "",
        changed: "",
        active: 1,
        children: [],
        city_id: "",
        codes: "",
        created: "",
        deleted: "",
        description: "",
        home: "",
        inn: "",
        kpp: "",
        moderation_time: "",
        office: "",
        official_name: "",
        postal: "",
        premium: "",
        random: "",
        status: "",
        street: "",
        street_url: "",
        subtitle: "",
        user: "",
        top_org: "",
        jur_regdate: "",
        jur_inn: "",
        jur_kpp: "",
        jur_ogrn: "",
        jur_okpo: "",
        jur_okato: "",
        jur_fsfr: "",
        jur_orgform: "",
        jur_okogu: "",
        up_time: "",
        tarif: "",
        worktime: {
            monday: {
                start: '09:00',
                end: '18:00',
                type: 'normal',
                obed: {
                    start: '12:00',
                    end: '13:00'
                }
            },
            tuesday: {
                start: '09:00',
                end: '18:00',
                type: 'normal',
                obed: {
                    start: '12:00',
                    end: '13:00'
                }
            },
            wednesday: {
                start: '09:00',
                end: '18:00',
                type: 'normal',
                obed: {
                    start: '12:00',
                    end: '13:00'
                }
            },
            thursday: {
                start: '09:00',
                end: '18:00',
                type: 'normal',
                obed: {
                    start: '12:00',
                    end: '13:00'
                }
            },
            friday: {
                start: '09:00',
                end: '18:00',
                type: 'normal',
                obed: {
                    start: '12:00',
                    end: '13:00'
                }
            },
            saturday: {
                start: '09:00',
                end: '18:00',
                type: 'normal',
                obed: {
                    start: '12:00',
                    end: '13:00'
                }
            },
            sunday: {
                start: '09:00',
                end: '18:00',
                type: 'normal',
                obed: {
                    start: '12:00',
                    end: '13:00'
                }
            }
        }
    }
});

window.FirmCollection = Backbone.Collection.extend({

    initialize: function (options) {
        this.options = options;
    },
    model: Firm,
    url: function () {
        if (this.options.search) {
            return ("/admin_api/firm-search/" + this.options.type + '/' + this.options.page + '/' + this.options.search + "/");
        }
        
        if (this.options.page) {
            return ("/admin_api/firm/" + this.options.type + "/" + this.options.page);
        }
        return ("/admin_api/firm/" + this.options.page);
    },
    parse: function (response) {
        this.currentPage = response.currentPage;
        this.totalPages = response.totalPages;
        return response.data;
    }
});


window.Review = Backbone.Model.extend({
    urlRoot: '/admin_api/review'
});

window.ReviewCollection = Backbone.Collection.extend({
    initialize: function (options) {
        this.options = options;
    },

    model: Review,
    url: function () {
      console.log(this.options);
      if (this.options.search) {
            return ("/admin_api/review-search/" + this.options.type + '/' + this.options.page + '/' + this.options.search + "/");
        }
      
        return ("/admin_api/review/" + this.options.type + "/" + this.options.page);
    },

    parse: function (response) {
        this.currentPage = response.currentPage;
        this.totalPages = response.totalPages;
        return response.data;
    }
});


window.User = Backbone.Model.extend({
    urlRoot: '/admin_api/user'
});

window.UsersCollection = Backbone.Collection.extend({
    initialize: function (options) {
        this.options = options;
    },

    model: User,

    url: function () {
        return ("/admin_api/users/" + this.options.type + "/" + this.options.page);
    },

    parse: function (response) {
        this.currentPage = response.currentPage;
        this.totalPages = response.totalPages;
        return response.data;
    }
});

window.UserSearchCollection = window.UsersCollection.extend({
    url: function () {
        return ("/admin_api/users/search/" + this.options.name);
    }
});

window.City = Backbone.Model.extend({
    urlRoot: '/admin_api/city'
});

window.CitiesCollection = Backbone.Collection.extend({
    initialize: function (options) {
        this.options = options;
    },

    model: City,

    url: function () {
        return ("/admin_api/cities/" + this.options.type + "/" + this.options.page);
    },

    parse: function (response) {
        this.currentPage = response.currentPage;
        this.totalPages = response.totalPages;
        return response.data;
    }
});