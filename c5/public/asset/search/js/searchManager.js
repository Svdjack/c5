var startTitle = document.title;
var startDescription = document.description;
var h1 = document.getElementsByTagName("h1")[0]['text'];
var isMob = window.innerWidth <= 600;

function suggestions() {

    var data = '';
    var token = '594a4e1d0a69de473f8b4569';
    var limit = 5;

    if (!data) {
        setTimeout(function () {
            init();
        }, 2000);
    }

    function init() {
        $.ajax({
            type: 'GET',
            crossDomain: true,
            dataType: 'jsonp',
            url: 'https://kladr-api.ru/api.php',
            data: {token: token, limit: 1, contentType: 'city', query: City.name},
            success: function (result) {
                data = result.result[0];
                initKladr();
            },
        });
    }

    function initKladr() {
        $("#from").kladr({
            oneString: true,
            token: token,
            limit: limit,
            parentId: data.id,
            parentType: data.contentType,
            regionId: data.id,
            withParent: true,
            oneString: true,
            type: 'street',
            labelFormat: function (obj) {
                return labelFormat(obj);
            },
            valueFormat: function (obj) {
                return labelFormat(obj);
            }
        });
        $("#to").kladr({
            oneString: true,
            token: token,
            limit: limit,
            parentId: data.id,
            parentType: data.contentType,
            regionId: data.id,
            withParent: true,
            oneString: true,
            type: 'street',
            labelFormat: function (obj) {
                return labelFormat(obj);
            },
            valueFormat: function (obj) {
                return labelFormat(obj);
            }
        });
        $("#address").kladr({
            oneString: true,
            token: token,
            limit: limit,
            parentId: data.id,
            type: 'street',
            parentType: data.contentType,
            regionId: data.id,
            withParent: true,
            oneString: true,
            labelFormat: function (obj) {
                return labelFormat(obj);
            },
            valueFormat: function (obj) {
                return labelFormat(obj);
            }
        });
    }

    function labelFormat(obj) {
        var label = obj.typeShort + '. ' + obj.name;
        if (obj.parents) {
            for (var k = obj.parents.length - 1; k > -1; k--) {
                var parent = obj.parents[k];
                if (parent.name && parent.contentType == 'city') {
                    label = parent.name + ', ' + label;
                }
            }
        }
        return label;
    }
}


function ucall(token) {
    $.getJSON("//ulogin.ru/token.php?host=" +
        encodeURIComponent(location.toString()) + "&token=" + token + "&callback=?",
        function (data) {
            data = $.parseJSON(data.toString())
            if (!data.error) {
                authModule.socialAuth(data, true);
            } else {
                userInterface.showMessage('Ошибка авторизации');
            }
        });
}