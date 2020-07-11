window.FirmView = Backbone.View.extend({

    days: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],

    initialize: function () {
        this.preRender();
    },

    render: function () {
        $(this.el).html(this.template(this.model.toJSON()));

        var tareas = document.querySelectorAll('textarea');
        [].forEach.call(tareas, function(o) {
            o.style.height = "1px";
            o.style.height = (25+o.scrollHeight)+"px";
        });
        return this;
    },

    events: {
        "change": "change",
        "click .save": "beforeSave",
        "click .delete": "deleteFirm",
        "change .user-company-category": "getCategoryChildren",
        "click .addGroup": "attachGroup",
        "click .delete-group": "detachGroup",
        "keyup #keyword-input-form": "loadKeywords",
        "keydown #keyword-input-form": "moveKeywords",
        "click .delete-keyword": "deleteKeyword",
        "click #drop-down-keywords-block div": "clickAddKeywordHandler",
        "mouseover #drop-down-keywords-block div": "mouseoverKeywordHandler",
        "change #region": "changeRegion",
        "click .delete-phone": "deletePhone",
        "click .delete-site": "deleteSite",
        "click .delete-fax": "deleteFax",
        "click .delete-email": "deleteEmail",
        "click #add-phone": "addPhone",
        "click #add-site": "addSite",
        "click #add-fax": "addFax",
        "click #add-email": "addEmail",
        "click #add-child": "addChild",
        "click #add-okved": "addOkved",
        "click .tooltipped": "hideTooltip",
        "click .nonstop": "worktime_24",
        "click .notworking": "worktime_rest",
        "change .work input.time": "worktimeChange",
        "change .obed input.obed": "worktimeObedChange",
        "click .delete-obed": "deleteObed",
        "change .children": "changeChild",
        "change .okved": "changeOkved",
        "click .add-obed": "addObed",
        "render-done #worktime-table": "worktimeSet",
        "click .contact-input": "changeContactInput",
        "blur .contact-input-form": "applyContactChanges",
        "keyup .contact-input-form": "applyContactChangesEnter",
        "click .selected-keywords span.name": "editKeywordInput",
        "blur .keyword-form-input": "editKeywordApply",
        "keyup .keyword-form-input": "editKeywordApplyEnter",
        "click legend": "toggleSneakyLegend"
    },

    actualizeMutableProps: function () {
        this.changeContactsModel();
        this.changeKeywordsModel();
    },

    applyContactChangesEnter: function (event) {
        if(event.keyCode == 13){
            $(event.currentTarget).blur();
        }
    },

    applyContactChanges: function(event){
        var _this = $(event.currentTarget);
        var _span;
        var _parent = _this.parent();
        _span = $('<span />', {"class": 'name contact-input'});
        _span.text(_this.val());
        _parent.prepend(_span);
        _this.remove();
        this.actualizeMutableProps();
    },

    changeContactInput: function (event) {
        var _this = $(event.currentTarget);
        var _input;
        var _parent = _this.parent();
        var value = _this.text();
        _input = $('<input />', {"class": 'name contact-input-form'});
        _input.val(value);
        _parent.prepend(_input);
        _input.focus();
        _this.remove();
    },

    worktimeSet: function () {
        var _this = this;
        $.each(this.model.get('worktime'), function (index, value) {
            if(value.type == 'rest') {
                $('#notwork' + _this.days.indexOf(index)).click();
            }
            if(value.type == 'nonstop') {
                $('#nonstop' + _this.days.indexOf(index)).click();
            }
        });
    },

    changeOkved: function () {
        var okved = [];
        $('input.okved').each(function () {
            if($(this).val() != '')
                okved.push($(this).val());
        });

        var jur = this.model.get('jur');
        jur['activities'] = okved;
        this.model.set('jur', jur);
    },

    changeChild: function () {
        var children = [];
        $('textarea.children').each(function () {
            if ($(this).val() != '')
                children.push($(this).val());
        });

        this.model.set('children', children);
    },

    deleteObed: function (event) {
        var $this = $(event.currentTarget);
        $this.closest('.obed_container').hide();
        $this.closest('tr.workday').find('.obed_button').show();
        var day = $this.closest('tr.workday').attr('data-day');

        var worktime = this.model.get('worktime');
        worktime[day]['type'] = 'normal';
        this.model.set('worktime', worktime);
    },

    addObed: function (event) {
        var $this = $(event.currentTarget);
        var day = $this.closest('tr.workday').attr('data-day');

        $this.closest('.obed_button').hide();
        $this.closest('tr.workday').find('.obed_container').show();
        var worktime = this.model.get('worktime');
        worktime[day]['type'] = 'normal_with_rest';
        this.model.set('worktime', worktime);
    },

    hideTooltip: function () {
        $('.material-tooltip').hide();
    },

    worktime_rest: function (event) {
        var $this = $(event.currentTarget);
        var day_offset = parseInt($this.prop('id').replace('notwork', ''));
        if ($this.is(':checked')) {
            $this.closest('.workday').find('input').prop('disabled', 'disabled');
            $this.closest('.workday').find('.delete-obed').hide();
            $this.prop('disabled', '');
        } else {
            $this.closest('.workday').find('input').prop('disabled', '');
            $this.closest('.workday').find('.delete-obed').show();
        }
        var worktime = this.model.get('worktime');
        worktime[this.days[day_offset]]['type'] = 'rest';
        this.model.set('worktime', worktime);
    },

    worktimeChange: function (event) {
        var $this = $(event.currentTarget);

        if ($this.attr('name') === 'monday') {
            var worktime = this.model.get('worktime');
            let pos = $this.attr('data-pos');

            for (let key in worktime) {
                worktime[key][pos] = encodeURI($this.val());
            }
            this.model.set('worktime', worktime);
            jQuery('.time-pick-' + pos).val($this.val());
        } else {
            var worktime = this.model.get('worktime');
            worktime[$this.prop('name')][$this.attr('data-pos')] = encodeURI($this.val());
            this.model.set('worktime', worktime);
        }
    },

    worktimeObedChange: function (event) {
        var $this = $(event.currentTarget);
        var worktime = this.model.get('worktime');
        worktime[$this.prop('name')]['obed'][$this.attr('data-pos')] = encodeURI($this.val());
        this.model.set('worktime', worktime);
    },

    worktime_24: function (event) {
        var $this = $(event.currentTarget);
        var day_offset = parseInt($this.prop('id').replace('nonstop', ''));
        if ($this.is(':checked')) {
            $this.closest('.workday').find('input').prop('disabled', 'disabled');
            $this.closest('.workday').find('.delete-obed').hide();
            $this.prop('disabled', '');
        } else {
            $this.closest('.workday').find('input').prop('disabled', '');
            $this.closest('.workday').find('.delete-obed').show();
        }
        var worktime = this.model.get('worktime');
        worktime[this.days[day_offset]]['type'] = 'nonstop';
        this.model.set('worktime', worktime);
    },

    preRender: function () {
        var _this = this;
        $.getJSON("/admin_api/getRegions", function (data) {
            _this.model.set('regionsList', data);
            _this.render();
        });
    },

    changeCity: function (event) {
        var $this = $(event.currentTarget);
        this.model.set('city_id', $this.val());
        console.log(this.model.get('city_id'));
    },

    changeRegion: function (event) {
        var $this = $(event.currentTarget);
        var $city = $('#city');
        $city.html('');
        $.getJSON("/admin_api/getCities/" + $this.val(), function (data) {
            $.each(data, function (key, value) {
                $city.append('<option value="' + value.id + '">' + value.name + '</option>');
            });
            $('#region').trigger('render');
        });
    },

    //these are basically same... should've used one event bind instead of two
    deletePhone: function (event) {
        var $this = $(event.currentTarget);
        utils.showAlert('Норм', 'Телефон удален', 'green');
        $this.parent().remove();
        this.actualizeMutableProps();
    },

    deleteFax: function (event) {
        var $this = $(event.currentTarget);
        utils.showAlert('Норм', 'Факс удален', 'green');
        $this.parent().remove();
        this.actualizeMutableProps();
    },

    deleteEmail: function (event) {
        var $this = $(event.currentTarget);
        utils.showAlert('Норм', 'Email удален', 'green');
        $this.parent().remove();
        this.actualizeMutableProps();
    },

    deleteSite: function (event) {
        var $this = $(event.currentTarget);
        utils.showAlert('Норм', 'Сайт удален', 'green');
        $this.parent().remove();
        this.actualizeMutableProps();
    },

    addFax: function (event) {
        var $this = $('#insert-fax');
        var value = $this.val();
        if (!value) {
            utils.showAlert('Ошибка!', 'Введите номер факса', 'red');
            return false;
        }

        $('#faxes-block').append('<div class="collection-item"><span class="name contact-input">' + value + '</span> \
                        <span class="remove red white-text delete-fax">Удалить</span></div>');

        $this.val('');
        this.actualizeMutableProps();
    },

    addEmail: function (event) {
        var $this = $('#insert-email');
        var value = $this.val();
        if (!value) {
            utils.showAlert('Ошибка!', 'Введите Email', 'red');
            return false;
        }

        $('#emails-block').append('<div class="collection-item"><span class="name contact-input">' + value + '</span> \
                        <span class="remove red white-text delete-email">Удалить</span></div>');

        $this.val('');
        this.actualizeMutableProps();
    },

    addPhone: function (event) {
        var $this = $('#insert-phone');
        var value = $this.val();
        if (!value) {
            utils.showAlert('Ошибка!', 'Введите номер телефона', 'red');
            return false;
        }

        $('#phones-block').append('<div class="collection-item"><span class="name contact-input">' + value + '</span> \
                        <span class="remove red white-text delete-phone">Удалить</span></div>');

        $this.val('');
        this.actualizeMutableProps();
    },


    addSite: function () {
        var $this = $('#insert-site');
        var value = $this.val();

        if (!value) {
            utils.showAlert('Ошибка!', 'Введите адрес сайта', 'red');
            return false;
        }

        $('#sites-block').append('<div class="collection-item"><span class="name contact-input">' + value + '</span> \
                        <span class="remove red white-text delete-site">Удалить</span></div>');

        $this.val('');

        this.actualizeMutableProps();
    },

    addChild: function () {
        var $this = $('.children').last();
        var newField = $this.clone();

        $this.after(newField);
        newField.val('');
    },

    addOkved: function () {
        var $this = $('.okved').last();
        var newField = $this.clone();

        $this.after(newField);
        newField.val('');
    },

    changeContactsModel: function () {
        var phones = [];
        var sites = [];
        var faxes = [];
        var emails = [];
        $('#phones-block .name').each(function () {
            phones.push($(this).text());
        });
        $('#sites-block .name').each(function () {
            sites.push($(this).text());
        });
        $('#faxes-block .name').each(function () {
            faxes.push($(this).text());
        });
        $('#emails-block .name').each(function () {
            emails.push($(this).text());
        });

        this.model.set("phones", phones);
        this.model.set("sites", sites);
        this.model.set("faxes", faxes);
        this.model.set("emails", emails);
    },

    attachGroup: function (event) {
        var $group = $(event.target).parent().find('select:last');

        var name = $group.find('option:selected').text();
        var id = $group.val();
        if (id == 0) {
            utils.showAlert('Ошибка!', 'Выберите категорию!', 'red');
            return false;
        }

        var groups = this.model.get('groups');
        for (var i = 0, k = groups.length; i < k; i++) {
            if (groups[i].id == id) {
                utils.showAlert('Ошибка!', 'Данная категория уже выбрана!', 'red');
                return false;
            }
        }

        $('.selected-rubrics').append('<div class="collection-item"><span class="name">' + name + '</span> \
                        <span class="badge red white-text delete-group remove">Удалить</span> \
                        <input type="hidden" class="groups" name="groups[]" value="' + id + '"/></div>');
        this.changeGroupsModel();
    },

    mouseoverKeywordHandler: function (event) {
        var $target = $(event.currentTarget);
        $target.siblings().removeClass('active');
        $target.addClass('active');
    },

    detachGroup: function (event) {
        $(event.target).parent().remove();
        this.changeGroupsModel();
    },

    changeGroupsModel: function () {
        var groups = [];
        $('.groups-select .collection-item').each(function () {
            groups.push({
                name: $(this).find('span.name').text(),
                id: $(this).find('input.groups').val()
            });
        });
        console.log(groups);
        this.model.set("groups", groups);
    },

    loadKeywords: function (event) {
        if (event.keyCode == 38 || event.keyCode == 40) {
            return false;
        }
        var $this = $(event.currentTarget);
        if (event.keyCode == 13) {
            if ($('#drop-down-keywords-block > div.active').length != 0) {
                this.addNewKeyword($('#drop-down-keywords-block > div.active').html());
            }
            else {
                this.addNewKeyword($this.val());
            }
            $this.val('');
        }
        if ($this.val() == '') {
            $('#drop-down-keywords-block').html('');
            return false;
        }
        var search = $this.val();
        $.getJSON("/ajax/user_company_keywords/" + search, function (data) {
            var items = [];
            $.each(data, function (key, value) {
                items.push("<div class='collection-item' value='" + value.name + "'>" + value.name + "</div>");
            });
            $('#drop-down-keywords-block').html(items.join(""));
        });
    },

    clickAddKeywordHandler: function (event) {
        this.addNewKeyword($(event.currentTarget).html());
        $('#keyword-input-form').val('');
        $('#drop-down-keywords-block').html('');
    },

    moveKeywords: function () {
        var obj = event.currentTarget;
        if (event.keyCode == 38 || event.keyCode == 40) {
            var $active = $(obj).parent().find('#drop-down-keywords-block > .active');
            if ($active.length == 0) {
                $(obj).parent().find('#drop-down-keywords-block > div:first-child').addClass('active');
            }
            else {
                if (event.keyCode == 38) {
                    if ($active.prev().length == 0) {
                        return
                    }
                    else {
                        $active.prev().addClass('active');
                        $active.removeClass('active');
                    }
                }
                if (event.keyCode == 40) {
                    if ($active.next().length == 0) {
                        return
                    }
                    else {
                        $active.next().addClass('active');
                        $active.removeClass('active');
                    }
                }
            }
            event.stopPropagation();
            return false;
        }
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    },

    addNewKeyword: function (keyword) {
        if (!keyword) {
            utils.showAlert('Ошибка!', 'Введите ключевое слово', 'red');
            return false;
        }

        var keywords = this.model.get('keywords');
        for (var i = 0, k = keywords.length; i < k; i++) {
            if (keywords[i] == keyword) {
                utils.showAlert('Ошибка!', 'Данное ключевое слово уже выбрано!', 'red');
                return false;
            }
        }

        $('.selected-keywords').append('<div class="collection-item"><span class="name">' + keyword + '</span> \
                        <span class="badge red white-text delete-keyword remove">Удалить</span></div>');
        this.actualizeMutableProps();
    },

    deleteKeyword: function (event) {
        $(event.currentTarget).parent().remove();
        this.actualizeMutableProps();
    },

    editKeywordInput: function (event) {
        var _this = $(event.currentTarget);
        var _input;
        var _parent = _this.parent();
        var value = _this.text();
        _input = $('<input />', {"class": 'name keyword-form-input'});
        _input.val(value);
        _parent.prepend(_input);
        _input.focus();
        _this.remove();
        this.actualizeMutableProps();
    },

    editKeywordApply: function (event){
        var _this = $(event.currentTarget);
        var _span;
        var _parent = _this.parent();
        _span = $('<span />', {"class": 'name'});
        _span.text(_this.val());
        _parent.prepend(_span);
        _this.remove();
        this.actualizeMutableProps();
    },

    editKeywordApplyEnter: function (event){
        if(event.keyCode == 13){
            $(event.currentTarget).blur();
        }
    },

    changeKeywordsModel: function () {
        var keywords = [];
        $('.keywords-block .collection-item span.name').each(function () {
            keywords.push($(this).text());
        });
        console.log(keywords);
        keywords = $.unique(keywords);
        this.model.set("keywords", keywords);
    },

    toggleSneakyLegend: function(event){
        var _this = $(event.currentTarget);
        _this.next().toggle();
        _this.toggleClass('unseen');
    },

    change: function (event) {
        // Remove any existing alert message
        utils.hideAlert();

        // Apply the change to the model
        var target = event.target;
        var change = {};
        change[target.name] = target.value;
        this.model.set(change);

        // Run validation rule (if any) on changed item
        var check = this.model.validateItem(target.id);
        if (check.isValid === false) {
            utils.addValidationError(target.id, check.message);
        } else {
            utils.removeValidationError(target.id);
        }
    },

    beforeSave: function () {
        this.model.set('changed', 0);
        var self = this;
        var check = this.model.validateAll();
        if (check.isValid === false) {
            utils.displayValidationErrors(check.messages);
            return false;
        }
        // Upload picture file if a new file was dropped in the drop area
        console.log('picture', this.pictureFile);
        if (this.pictureFile) {
            this.model.set("picture", this.pictureFile.name);
            utils.uploadFile(this.pictureFile,
                function () {
                    self.saveFirm();
                }
            );
        } else {
            this.saveFirm();
        }
        return false;
    },

    saveFirm: function () {
        var self = this;
        this.model.set('status', 1);
        this.model.set('changed', 0);
        utils.decCounter('firms');
        utils.incCounter('firms-approved');
        this.model.save(null, {
            success: function (model) {
                self.render();
                window.location.replace(self.model.get('url'));
                //utils.showAlert('Success!', 'Фирма сохранена!', 'green');
            },
            error: function () {
                utils.showAlert('Error', 'При сохранении произошла ошибка...', 'red');
            }
        });
    },

    deleteFirm: function () {
        this.model.destroy({
            success: function () {
                //utils.showAlert('Success!', 'Фирма удалена!', 'green');
                window.location.replace('#firms');
            }
        });
        return false;
    },

    dropHandler: function (event) {
        event.stopPropagation();
        event.preventDefault();
        var e = event.originalEvent;
        e.dataTransfer.dropEffect = 'copy';
        this.pictureFile = e.dataTransfer.files[0];

        // Read the image file from the local file system and display it in the img tag
        var reader = new FileReader();
        reader.onloadend = function () {
            $('#picture').attr('src', reader.result);
        };
        reader.readAsDataURL(this.pictureFile);
    },

    addCategory: function (event) {
        void 0
    },

    getCategoryChildren: function (event) {
        var $select = ($(event.target));
        var $new_select = $select.clone();
        var level = parseInt($select.attr('data-level'));
        $new_select.attr('data-level', level + 1);
        $select.siblings("select").each(function () {
            if ($(this).attr('data-level') > level) {
                $(this).remove();
            }
        });
        var id = $select.val();
        console.log(id);
        $select.prop('disabled', true);
        $.getJSON("/ajax/user_company_category/" + id, function (data) {
            var items = [];
            $.each(data, function (key, value) {
                items.push("<option value='" + value.id + "'>" + value.name + "</option>");
            });
            if (items.length != 0) {
                $new_select.html(items.join(""));
                $new_select.hide();
                $select.after($new_select);
                $new_select.fadeIn();
                $new_select.change(); // to instantly invoke next level select out of this, if present
            }
            $select.prop('disabled', false);
        });
        $('select').material_select();
    }

});