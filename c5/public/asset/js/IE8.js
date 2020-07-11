(function ($) {

    function header_adapt(){
         var doc_w = $(window).width();

       if(doc_w > 515)
            {
                 $('body').addClass('min_width515');
            }
       if(doc_w > 706)
            {
                 $('body').addClass('min_width706');
            }

       if(doc_w <= 961 && doc_w > 800)
            {

                $('body').attr('id', 'max_width960');
            }
        else if(doc_w <= 800 && doc_w > 705)
            {
                $('body').attr('id', 'max_width800');
            }
        else if(doc_w <= 705 && doc_w > 514)
            {

                $('body').attr('id', 'max_width705');
            }
        else if(doc_w <= 514 && doc_w > 309)
            {
                $('body').attr('id' , 'max_width514');
            }
        else if(doc_w <= 309)
            {
                $('body').attr('id', 'max_width309');
            }

    };
    $(document).ready(header_adapt);
   window.onload=header_adapt;
   $( window ).resize(header_adapt);
}) (jQuery);
