window.CityView = Backbone.View.extend({

    initialize: function () {
        this.render();
    },

    render: function () {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },

    events: {
        "change": "change",
        "click .save": "saveCity",
        "click .delete": "deleteCity"
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

    saveCity: function (event) {
        event.preventDefault();
        var self = this;
        this.model.save(null, {
            success: function (model) {
                window.history.back();
                utils.showAlert('Success!', 'Город сохранен!', 'green');
            },
            error: function (model, response) {
                response = JSON.parse(response.responseText);
                utils.showAlert('Error', response.error, 'red');
            }
        });
    },

    deleteCity: function () {
        this.model.destroy({
            success: function () {
                utils.showAlert('Город удален, компании скрыты');
                window.history.back();
            }
        });
        return false;
    }

});