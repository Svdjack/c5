var authModule = (function () {
    return {
        login: function (login, password) {
            $.ajax({
                processData: false,
                url: path.ajaxLogin,
                type: 'POST',
                dataType: 'json',
                data: "login=" + login + '&passwd=' + password,
                success: function (data) {
                    console.log(data);
                    if (data.error) {
                        authModule.showError(data.error);
                    } else {
                        messageManager.show('Авторизация прошла успешно');
                        $('.search__authblock').hide();
                        $('.login').unbind('click');
                        $('header .login').text('Личный кабинет');
                    }
                }
            });
        },

        showError: function (text) {
            userInterface.showMessage(text);
        },

        createUser: function (email) {
            $.ajax({
                processData: false,
                url: path.ajaxRegister,
                type: 'POST',
                dataType: 'json',
                data: "email=" + email,
                success: function (data) {
                    console.log(data);
                    if (data.error) {
                        authModule.showError(data.error);
                    } else {
                        userInterface.showMessage('Аккаунт ' + email + ' успешно создан. Пароль отправлен Вам на почту');
                        $('.simplemodal-close').click();
                    }
                }
            });
        },

        socialAuth: function (info, redirect) {
            var info = JSON.stringify(info);
            $.ajax({
                processData: false,
                url: '/search/ajax_social_login',
                type: 'POST',
                dataType: 'json',
                data: "data=" + info,
                success: function (data) {
                    if (data.error) {
                        authModule.showError(data.error);
                    } else {
                        userInterface.showMessage('Авторизация прошла успешно');
                        $('.simplemodal-close').click();
                        if (redirect) {
                            setTimeout(function () {
                                document.location = document.referrer;
                            }, 1000)
                        }
                    }
                }
            });
        },
    }
}());
