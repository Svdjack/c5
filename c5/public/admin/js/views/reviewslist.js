window.ReviewsListView = Backbone.View.extend({
    initialize: function () {
        this.render();
    },

    render: function () {
        var reviews = this.model.models;
        var type = this.options.type;
        var len = reviews.length;
        var startPos = 0;
        var endPos = Math.min(100, len);

        $(this.el).html(new Paginator({model: this.model, page: this.model.currentPage, type: type}).render().el);

        $(this.el).append('<table id="table" class="comment-list highlight bordered"><thead>' +
            '<tr><td>Название/Дата/Имя</td><td>Отзыв</td><td>Статус</td><td>Действия</td></tr>' +
            '</thead></table>');

        for (var i = startPos; i < endPos; i++) {
            $('#table', this.el).append(new ReviewsListItemView({model: reviews[i], type: type}).render().el);
        }

        $(this.el).append(new Paginator({model: this.model, page: this.model.currentPage, type: type}).render().el);


        return this;
    }
});

window.ReviewsListItemView = Backbone.View.extend({
    
    tagName: "tr",

    initialize: function () {
        this.model.bind("change", this.render, this);
        this.model.bind("destroy", this.close, this);
    },

    render: function () {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },

    events: {
        "click .text .show": "showReview",
        "click .delete-review": "deleteReview",
        "click .approve-review" : "approveReview",
        "click .tooltipped" : "hideTooltip",
        "click .restore-review" : "restoreReview"
    },

    hideTooltip: function(){
        $('.material-tooltip').hide();
    },

    showReview: function (event) {
        var _this = $(event.currentTarget);
        _this.parent().css('max-height', 'none');
        _this.remove();
    },

    deleteReview: function (event) {
        event.preventDefault();

        this.model.destroy();
        //utils.showAlert('Success!', 'Отзыв успешно удален!', 'green');
        $(this.el).html('');
        utils.decCounter('reviews');
        utils.incCounter('reviews-deleted');
    },

    approveReview: function (event){
        event.preventDefault();
        var _this = $(event.currentTarget);

        this.model.set('status', 1);
        var time = Math.floor(new Date().getTime()/1000);
        this.model.set('moderationtime', time);
        this.model.save();

        console.log(this);

        this.$el.fadeOut();
        //utils.showAlert('Success!', 'Отзыв одобрен!', 'green');
        utils.decCounter('reviews');
        utils.incCounter('reviews-approved');
    },

    restoreReview: function(event){
        event.preventDefault();
        this.model.set('active', 1);
        this.model.set('edited', 1);
        this.model.save();
        utils.showAlert('Усп', 'Отзыв восстановлен', 'green');
        $(this.el).html('');
        utils.decCounter(this.options.type);
        utils.incCounter('reviews-approved');
    }

});