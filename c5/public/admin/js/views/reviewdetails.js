window.ReviewView = Backbone.View.extend({

    initialize: function () {
        this.render();
    },

    render: function () {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },

    events: {
        "change": "change",
        "click .save": "saveReview",
        "click .delete": "deleteReview"
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

    saveReview: function (event) {
        event.preventDefault();
        utils.decCounter('reviews-new');
        utils.incCounter('reviews-approved');
        var self = this;
        this.model.set('status', 1);
        this.model.save(null, {
            success: function (model) {
                window.history.back();
                utils.showAlert('Success!', 'Отзыв сохранен!', 'green');
            },
            error: function (model, response) {
                console.log(response);
                utils.showAlert('Error', 'При сохранении произошла ошибка...', 'red');
            }
        });
    },

    deleteReview: function () {
        this.model.destroy({
            success: function () {
                utils.showAlert('Отзыв удален!');
                window.history.back();
            }
        });
        return false;
    }

});