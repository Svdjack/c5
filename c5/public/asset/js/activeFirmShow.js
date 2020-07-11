jQuery(document).ready(function () {
    let activeFirm = localStorage.getItem('activeFirm');

    if (!activeFirm) {
        return;
    }

    let clear = function ( ) {
        localStorage.removeItem('activeFirm', null);
        localStorage.removeItem('activeFirmLink', null);
        localStorage.removeItem('activeFirmExpire', null);
    };

    let check = function () {
        let currentTime = +(new Date());
        let expTime = parseInt(localStorage.getItem('activeFirmExpire'), 10);

        if (!expTime) {
            clear();
        }

        if (currentTime > expTime) {
            clear();
            return true;
        }

        return false;
    };

    check();

    let div = `<div class="activeFirm">
        <a href="` + localStorage.getItem('activeFirmLink') + `">
            Назад на карту
        </a>
    </div>`;
    jQuery('body').append(div);

    let int = setInterval(function () {
        if (check()) {
            jQuery('.activeFirm').hide();
            clearInterval(int);
        }
    }, 1000);
});