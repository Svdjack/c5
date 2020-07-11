window.LoginView = Backbone.View.extend({
    initialize: function () {
        this.render();
    },
    render: function () {
        $(this.el).html(this.template());
        return this;
    },

    events: {
        "click .login": "login"
    },

    login: function () {
        var login = $('input[id=login]').val();
        var password = $('input[id=password]').val();
        var postData = {login: login, password: password};
        $.ajax({
            url: '/admin_api/auth',
            contentType: 'application/json',
            dataType: 'json',
            type: 'PUT',
            crossDomain: true,
            xhrFields: {
                withCredentials: true
            },
            data: JSON.stringify(postData),
            success: function (res) {
                if (res.error) {
                    window.location.replace('#login');
                }
                else {
                    window.location.replace(utils.entryPoint);
                    window.location.reload();
                }
            }
        });
    }

});