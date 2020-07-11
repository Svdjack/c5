window.FirmsListView = Backbone.View.extend({

    initialize: function () {
        this.render();
    },

    render: function () {
        var firms = this.model.models;
        var type = this.options.type;
        var len = firms.length;
        var startPos = 0;
        var endPos = Math.min(100, len);

        $(this.el).html(new Paginator({model: this.model, page: this.model.currentPage, type: type}).render().el);
        $(this.el).append('<table id="table" class="highlight bordered"><thead>' +
            '<tr><td>Название</td><td>Дата</td><td>Пользователь</td><td>Статус</td><td>Действия</td></tr>' +
            '</thead></table>');

        for (var i = startPos; i < endPos; i++) {
            $('#table', this.el).append(new FirmsListItemView({model: firms[i], type: type}).render().el);
        }
        
        $(this.el).append(new Paginator({model: this.model, page: this.model.currentPage, type: type}).render().el);

        return this;
    }
});

window.FirmsListItemView = Backbone.View.extend({

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
        "click .delete-firm": "deleteFirm",
        "click .approve-firm" : "approveFirm",
        "click .tooltipped" : "hideTooltip",
        "click .restore-firm" : "restoreFirm"
    },

    hideTooltip: function(){
        $('.material-tooltip').hide();
    },

    deleteFirm: function (event) {
        event.preventDefault();
        this.model.destroy();
        //utils.showAlert('Success!', 'Компания успешно удалена!', 'green');
        $(this.el).html('');
        utils.decCounter(this.options.type);
        utils.incCounter('firms-deleted');
    },

    approveFirm: function (event){
        event.preventDefault();
        this.model.set('status', 1);
        var time = Math.floor(new Date().getTime()/1000);
        this.model.set('changed', 0);
        this.model.set('moderation_time', time);
        this.model.save();
        //utils.showAlert('Success!', 'Компания одобрена!', 'green');
        utils.decCounter(this.options.type);
        utils.incCounter('firms-approved');
    },

    restoreFirm: function(event){
        event.preventDefault();
        console.log(this);
        this.model.set('active', 1);
        this.model.set('status', 1);
        this.model.save();
        utils.showAlert('Усп', 'Компания востановлена', 'green');
        $(this.el).html('');
        utils.decCounter(this.options.type);
        utils.incCounter('firms-approved');
    }

});