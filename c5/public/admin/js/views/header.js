window.SideView = Backbone.View.extend({

    initialize: function () {
        this.render();
    },

    render: function () {
        $(this.el).html(this.template());
        return this;
    },

    selectMenuItem: function (menuItem) {
        $('.collection-item').removeClass('active');
        if (menuItem) {
            $('.' + menuItem).addClass('active');
        }
    }

});