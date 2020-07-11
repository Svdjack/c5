window.UsersListView = Backbone.View.extend({
    initialize: function () {
        this.render();
    },

    events: {
        "click .search-user": "searchUser",
        "keyup #searchUserInput": "searchUserKeyHandler",
        "click .add-user": "addUser",
        "keyup #addUserInput": "addUserKeyHandler"
    },

    addUserKeyHandler: function (event) {
        if(event.keyCode == 13){
            $(event.currentTarget).parent().find('.add-user').click();
        }
    },

    searchUserKeyHandler: function (event) {
        if(event.keyCode == 13){
            $(event.currentTarget).parent().find('.search-user').click();
        }
    },

    searchUser: function(event) {
        var query = $('#searchUserInput').val();
        console.log(query);
        window.location.href= "#users/search/" + query;

    },

    addUser: function (event) {
        var email = $('#addUserInput').val();
        var data = new FormData();
        data.append('email', email);
        $.ajax({
            url: '/admin_api/users/add',
            type: 'POST',
            data: data,
            processData: false,
            cache: false,
            contentType: false,
            success: function (data) {
                if (data['error']){
                    utils.showAlert('Erara0!', data['error'], 'red');
                    return;
                }
                utils.showAlert('Success!', 'Пользователь добавлен', 'green');
            },
            error: function () {
                utils.showAlert('Erara0', 'Ошибка! Возможно пользователь с этим адресом уже зарегистрирован!', 'red');
            }
        });
    },

    render: function () {
        var users = this.model.models;
        var type = this.options.type;
        var len = users.length;
        var startPos = 0;
        var endPos = Math.min(100, len);

        var add_user_html = '<div class="row useradd input-field">' +
            '' +
            '<input class="col s5" type="text" id="searchUserInput" placeholder="Поиск"/>' +
            '<div class="btn search-user button update" style="margin-right: 10px;">Найти</div>' +
            '<input class="col s5" type="text" id="addUserInput" placeholder="user@example.com"/>' +
            '<div class="btn add-user button update">Добавить</div>' +
            '</div>';


        $(this.el).html(add_user_html);

        $(this.el).append(new Paginator({model: this.model, page: this.model.currentPage, type: type}).render().el);

        $(this.el).append('<table id="table" class="highlight bordered"><thead>' +
            '<tr><td>Логин</td><td>Дата</td><td>Имя</td><td>Фирмы</td><td>Действия</td></tr>' +
            '</thead></table>');

        for (var i = startPos; i < endPos; i++) {
            $('#table', this.el).append(new UsersListItemView({model: users[i], type: type}).render().el);
        }

        $(this.el).append(new Paginator({model: this.model, page: this.model.currentPage, type: type}).render().el);
        return this;
    }
});

window.UsersListItemView = Backbone.View.extend({

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
        "click .delete-user": "deleteUser",
        "click .tooltipped": "hideTooltip",
        "click .unbind-firm": "unbindFirm"
    },

    hideTooltip: function () {
        $('.material-tooltip').hide();
    },

    deleteUser: function (event) {
        event.preventDefault();
        console.log(this.model);
        this.model.destroy();
        utils.showAlert('Success!', 'Пользователь удален!', 'green');
        $(this.el).html('');
    },

    unbindFirm: function (event) {
        event.preventDefault();
        var firm = $(event.currentTarget).parent();
        var firm_id = firm.attr('data-firmid');
        $.ajax({
            url: '/admin_api/unbind_firm/' + firm_id,
            type: 'DELETE',
            success: function () {
                utils.showAlert('Success!', 'Компания отвязана', 'green');
                firm.remove();
            }
        });
    }

});