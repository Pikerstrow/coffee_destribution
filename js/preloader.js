
;(function($) {
    "use strict";

    function preloader(){
        if ( $('.preloader').length ){
            $(window).on('load', function() {
                $('.preloader').delay(10).fadeOut('slow');
                $('body').delay(10).css({'overflow':'visible'});
            });
        }
    };

    preloader ();

})(jQuery);