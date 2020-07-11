window.utils = {

    entryPoint: null,

    // Asynchronously load templates located in separate .html files
    loadTemplate: function (views, callback) {

        var deferreds = [];

        $.each(views, function (index, view) {
            if (window[view]) {
                deferreds.push($.get('tpl/' + view + '.html', function (data) {
                    window[view].prototype.template = _.template(data);
                }));
            } else {
                alert(view + " not found");
            }
        });

        $.when.apply(null, deferreds).done(callback);
    },

    uploadFile: function (file, callbackSuccess, url) {
        var self = this;
        var data = new FormData();
        data.append('file', file);
        return $.ajax({
                url: url || 'api/upload.php',
                type: 'POST',
                data: data,
                processData: false,
                cache: false,
                contentType: false
            })
            .done(function (r) {
                console.log(file.name + " uploaded successfully");
                callbackSuccess(r);
            })
            .fail(function () {
                self.showAlert('Error!', 'An error occurred while uploading ' + file.name, 'alert-error');
            });
    },

    displayValidationErrors: function (messages) {
        for (var key in messages) {
            if (messages.hasOwnProperty(key)) {
                this.addValidationError(key, messages[key]);
            }
        }
        this.showAlert('Warning!', 'Fix validation errors and try again', 'alert-warning');
    },

    addValidationError: function (field, message) {
        var controlGroup = $('#' + field).parent().parent();
        controlGroup.addClass('error');
        $('.help-inline', controlGroup).html(message);
    },

    initializeCounters: function () {
        var _this = this;
        this.counters = {};
        $.getJSON('/admin_api/firm/counter', function (data) {
            _this.setCounters(data);
        });
        $.getJSON('/admin_api/review/counter', function (data) {
            _this.setCounters(data);
        });
    },

    setCounters: function (counters) {
        var _this = this;
        $.each(counters, function (type, value) {
            _this.counters[type] = value;
        });
        $.each(this.counters, function (type, value) {
            $('#counter-' + type).text(value);
        })
    },

    incCounter: function (type) {
        var insert = {};
        insert[type] = parseInt(this.counters[type]) + 1;
        this.setCounters(insert);
    },

    decCounter: function (type) {
        var insert = {};
        insert[type] = parseInt(this.counters[type]) - 1;
        this.setCounters(insert);
    },

    convertTime: function (unix) {
        var a = new Date(unix * 1000);
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var year = a.getFullYear();
        var month = this.pad(a.getMonth() + 1);
        var date = this.pad(a.getDate());
        var hour = this.pad(a.getHours());
        var min = this.pad(a.getMinutes());
        var sec = this.pad(a.getSeconds());
        var time = date + '.' + month + '.' + year + ' ' + hour + ':' + min + ':' + sec;
        return time;
    },

    pad: function (n) {
        return n < 10 ? '0' + n : n;
    },

    initializeRegions: function () {
        var _this = this;
        $.getJSON('/admin_api/regions', function (data) {
            _this.regions = data;
        })
    },

    removeValidationError: function (field) {
        var controlGroup = $('#' + field).parent().parent();
        controlGroup.removeClass('error');
        $('.help-inline', controlGroup).html('');
    },

    showAlert: function (title, text, klass) {
        Materialize.toast(text, 2000, klass);
        //$('.alert').removeClass("alert-error alert-warning alert-success alert-info");
        //$('.alert').addClass(klass);
        //$('.alert').html('<strong>' + title + '</strong> ' + text);
        //$('.alert').show();
    },

    hideAlert: function () {
        $('.alert').hide();
    },

    setMenuByHash: function () {
        var section = utils.entryPoint.replace('#', '').split('/', 1)[0];
        console.log(section);
        switch(section){
            case 'firms':
            case 'users':
            case 'cities':
            case 'reviews':
                $('a.open-tab[data-tab='+section+'-tab]').click();
                break;

            default:
                $('a.open-tab[data-tab=firms-tab]').click();
                break;
        }
    }
    
};

window.macros = {
    textInput: function (name, label, value) {
        value = this.escapeHtml(value);
        return '<div class="control-group form-item input-field"> \
        <label for="' + name + '" class="control-label ' + (value ? 'active' : '') + '">' + label + ':</label> \
            <input type="text" id="' + name + '" name="' + name + '" value="' + value + '"/> \
        </div>';
    },

    escapeHtml: function (unsafe) {
        if (typeof unsafe == 'string')
            return unsafe
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");

        return ''
    }
};

window.firmLogoUpload = function (id) {
  utils.uploadFile(document.querySelector('#pictureFile').files[0], (r) => {
    alert(r + ', перезагрузите страницу');
  }, '/admin_api/firm/upload_logo/' + id);
  return false;
}

window.firmLogoDelete = function(id) {
  fetch('/admin_api/firm/delete_logo/' + id)
    .then(res => res.text())
    .then(t => alert(t))
  ;
  
  return false;
}