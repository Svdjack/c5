// вставляем линки JSом

jQuery(document).ready(function () {
    //header

    //footer
    jQuery('#footer_social_buttons').html('<a title="ТвояФирма в ВКонтакте" target="_blank" rel="nofollow" \
    href="http://vk.com/TvoyaFirma"><img src="/asset/images/vk.png"/></a> \
        <a title="ТвояФирма в Facebook" target="_blank" rel="nofollow" \
    href="https://www.facebook.com/TvoyaFirma"><img \
    src="/asset/images/fb.png"/></a> \
        <a title="ТвояФирма в Twitter" target="_blank" rel="nofollow" \
    href="https://twitter.com/TvoyaFirma"><img \
    src="/asset/images/tw.png"/></a>');

    jQuery('#footer_help_link').html('<div class="column">\
        <a rel="nofollow" href="/правила-публикации"\
    title="Правила публикации — справочник предприятий «Твоя Фирма» ">Правила\
    публикации</a>\
    <a rel="nofollow" href="/написать-нам" style="font-weight: bold">Написать администрации сайта</a>\
    </div>\
    <div class="column">\
        <a rel="nofollow" title="Добавить компанию в справочник ТвояФирма.рф"\
    href="/добавить-компанию">Добавить компанию</a>\
    <a rel="nofollow" title="Пользовательское соглашение ТвояФирма.рф"\
    href="/пользовательское-соглашение">Пользовательское соглашение</a>\
    </div>');

    jQuery('#footer_copyright_link').html('<a href="/" title="Адреса, реквизиты, схемы проезда организаций на  сайте ТвояФирма.РФ">ТвояФирма.РФ</a>');
});