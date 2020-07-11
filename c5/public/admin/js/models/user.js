window.SessionModel = Backbone.Model.extend({
    defaults: {
        logged_in: false,
        user_id: ''
    },
    cookie: "admin_panel_auth",
    initialize: function () {
        //this.user = new UserModel({});
    },

    checkAuth: function () {
        var self = this;
        // var postData = {cookie: this.getCookie(this.cookie)};
        // console.log(postData);
        $.ajax({
            url: '/admin_api/auth',
            contentType: 'application/json',
            dataType: 'json',
            type: 'POST',
            // data: JSON.stringify(postData),
            success: function(res){
                if(res.error){
                    window.location.replace('#login');
                }
            }
        });
    },
    login: function () {
        $.ajax(
            
        );
    },
    logout: function () {

    },

    setCookie: function (cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    },

    getCookie: function (cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
        }
        return "";
    }
});