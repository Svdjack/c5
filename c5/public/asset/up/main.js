{
    var type = 'premium';

    $(document).on("change", '.types input', function () {
        type = $(this).val();
        setPrice(1);
        $('#edit-date-type option').removeAttr("selected");
    });


    $(document).on("change", '#edit-date-type', function () {
        setPrice($(this).val())
    });

    function setPrice(month) {
        var email = $('.form-item-email');
        var date = $('.form-item-date-type');
        var pricer = $('#pricer');
        var oferta = $('#oferta');
        if (Prices[type]) {
            var sum = Prices[type][month];
            var oneMonth = sum / month;
            oneMonth = Math.ceil(oneMonth);
            $('#one-month').text(oneMonth);
            $('#sum').text(sum);
            email.show();
            email.children('input').attr('required', true);
            date.show();
            pricer.show();
        } else {
            email.hide();
            email.children('input').removeAttr('required');
            date.hide();
            pricer.hide();
            oferta.removeAttr('required');
        }
        $('.pr_icon').css('opacity', 0.2);
        $('.' + type).css('opacity', 1);
    }

    setPrice(1);

}
