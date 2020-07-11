window.CitiesListView = Backbone.View.extend({
    initialize: function () {
        this.render();
    },

    events: {
        "click .add-city": "addCity"
    },

    // addUserKeyHandler: function (event) {
    //     if(event.keyCode == 13){
    //         $(event.currentTarget).parent().find('.add-user').click();
    //     }
    // },
    //

    addCity: function (event) {
        var name = $(event.currentTarget).parent().find('input#addUserInput').val();
        var region = $(event.currentTarget).parent().find('select#addRegion').val();
        var data = new FormData();
        data.append('name', name);
        data.append('region', region);
        $.ajax({
            url: '/admin_api/city/add',
            type: 'POST',
            data: data,
            processData: false,
            cache: false,
            contentType: false,
            success: function (data) {
                utils.showAlert('Success!', data.message, 'green');
                window.location.href = '#city/' + data.city_id;
            },
            error: function (data) {
                utils.showAlert('Erara0', data.responseJSON.error, 'red');
            }
        });
    },

    render: function () {
        var cities = this.model.models;
        var type = this.options.type;
        var len = cities.length;
        var startPos = 0;
        var endPos = Math.min(100, len);

        var options = '';
        $.each(utils.regions, function (key, data) {
            options += '<option value="' + data.id + '">' + data.name + '</option>';
        });

        var select = '<select id="addRegion" name="region" class="browser-default col s4 offset-s1">' + options + '</select>';

        var add_user_html = '<div class="row input-field city-form form-item"><fieldset">' +
            '<legend>Добавить город</legend>' +
            '<input class="col s3" type="text" id="addUserInput" placeholder="Абакан"/>' +
            select +
            '<div class="col offset-s1 button add-city">Добавить</div></fieldset></div>';

        $(this.el).html(new Paginator({model: this.model, page: this.model.currentPage, type: type}).render().el);
        $(this.el).append(add_user_html + '<table id="table" class="highlight bordered"><thead>' +
            '<tr><td>Имя</td><td>URL</td><td>Регион</td><td>Действия</td></tr>' +
            '</thead></table>');

        for (var i = startPos; i < endPos; i++) {
            $('#table', this.el).append(new CitiesListItemView({model: cities[i], type: type}).render().el);
        }

        $(this.el).append(new Paginator({model: this.model, page: this.model.currentPage, type: type}).render().el);

        return this;
    }
});

window.CitiesListItemView = Backbone.View.extend({

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
        "click .delete-city": "deleteCity",
        "click .tooltipped": "hideTooltip"
    },

    hideTooltip: function () {
        $('.material-tooltip').hide();
    },

    deleteCity: function (event) {
        event.preventDefault();
        console.log(this.model);
        this.model.destroy();
        utils.showAlert('Success!', 'Город удален, компании скрыты...', 'green');
        $(this.el).html('');
    },

    // unbindFirm: function (event) {
    //     event.preventDefault();
    //     var firm = $(event.currentTarget).parent();
    //     var firm_id = firm.attr('data-firmid');
    //     $.ajax({
    //         url: '/admin_api/unbind_firm/' + firm_id,
    //         type: 'DELETE',
    //         success: function () {
    //             utils.showAlert('Success!', 'Компания отвязана', 'green');
    //             firm.remove();
    //         }
    //     });
    // }

});