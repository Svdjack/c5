window.UserView = Backbone.View.extend({

    initialize: function () {
        this.render();
    },

    render: function () {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },

    events: {
        "change": "change",
        "click .save": "saveUser",
        "click .delete": "deleteUser"
    },

    change: function (event) {
        // Remove any existing alert message
        utils.hideAlert();

        // Apply the change to the model
        var target = event.target;
        var change = {};
        change[target.name] = target.value;
        this.model.set(change);

        // // Run validation rule (if any) on changed item
        // var check = this.model.validateItem(target.id);
        // if (check.isValid === false) {
        //     utils.addValidationError(target.id, check.message);
        // } else {
        //     utils.removeValidationError(target.id);
        // }
    },

    saveUser: function (event) {
        event.preventDefault();
        var self = this;
        this.model.save(null, {
            success: function (model) {
                window.history.back();
                utils.showAlert('Success!', 'Пользователь сохранен!', 'green');
            },
            error: function () {
                utils.showAlert('Error', 'При сохранении произошла ошибка...', 'red');
            }
        });
    },

    deleteUser: function () {
        this.model.destroy({
            success: function () {
                utils.showAlert('Пользователь удален!');
                window.history.back();
            }
        });
        return false;
    }

});