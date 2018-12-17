

/*MENU*/

$(document).ready(function () {

    /*ВЕРТИКАЛЬНЕ ВИРІВНЮВАННЯ МЕНЮ НА МОБІЛЬНИХ ПРИСТРОЯХ*/
    var ul_menu_container_height = $('div#main_menu_inner').height();
    var ul_menu_height = $('div#main_menu_ul').find('ul#main_menu').height();


    $('ul#main_menu').css('padding-top', function () {
        var menu_padding_top = ((ul_menu_container_height/2 - ul_menu_height/2));
        return menu_padding_top;
    });


    $('#hamburger_menu').click(function () {
        $('#hamburger_menu').find('span').toggleClass('active');
        $('div#main_menu_ul').toggleClass('active');

        /*АНІМАЦІЯ БОРДЕРІВ НА КЛІК ПО МЕНЮ - ПОЧАТОК*/
        if ($('div[class^="border"]').hasClass('inner_menu_borders_active')) {
            $('div[class^="border"]').css({
                'transition-duration': '0s',
                'transition-delay': '.5s'
            });
            $('div[class^="border"]').toggleClass('inner_menu_borders_active');
        } else {
            $('div[class^="border"]').css({
                'transition-duration': '.8s',
                'transition-delay': '.5s'
            });
            $('div[class^="border"]').toggleClass('inner_menu_borders_active');
        }
        /*АНІМАЦІЯ БОРДЕРІВ НА КЛІК ПО МЕНЮ - КІНЕЦЬ*/
        $('div.fon_color').toggleClass('fon_color_active');// Затемення контенту при активіації меню

        if ($('#hamburger_menu').find('#menu_name').text() == 'меню') {
            $('#hamburger_menu').find('#menu_name').text('закрити').css('left', '-5px');
        } else {
            $('#hamburger_menu').find('#menu_name').text('меню').css('left', '0px');
        }
    });

});


/*FOR THE HELP TO TYPE PHONE NUMBER IN NECESSARY FORMAT (+38(XXX)XXX-XX-XX)*/
$(document).ready(function () {

    $('.form_for_phone').on('focus', function () {
        $(this).val('+38(0');
    });

    $('.form_for_phone').on('keyup input', function () {
        var value = $(this).val();
        if (value.length == 7) {
            $(this).val(value + ')');
        }
        if (value.length == 11) {
            $(this).val(value + '-');
        }
        if (value.length == 14) {
            $(this).val(value + '-');
        }
    });
});


/*FOR TRANSFORIMING TO UPPERCASE FIRST LETTER IN EACH NEW WORD IN CLIENT'S NAME*/
$(document).ready(function () {
    $('.form-for-name').on('keyup', function (event) {
        $(this).val(wordToUpperCase($(this).val()));
    });

    function wordToUpperCase(str) {
        return str.split(" ").map(function (word) {
            return word.charAt(0).toUpperCase() + word.substring(1).toLowerCase()
        }).join(" ");
    }
});


/*FOR APPLYNG BOOTSTRAP VALIDATION CLASSES TO FORMS*/
$(document).ready(function () {
    /*Adds bootstrap validation classes to form after it's validation on back-end*/
    $('.ok-span').each(function () {
        var input = $(this).closest('.form-group');
        input.addClass('has-success has-feedback');
        input.find('.input-group').append('<span class="glyphicon glyphicon-ok form-control-feedback"></span>');
    });
    $('.error-span').each(function () {
        var input = $(this).closest('.form-group');
        input.addClass('has-error has-feedback');
        input.find('.input-group').append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
    });

    /*Remove bootstrap validation classes on focus and change*/
    $('.reg-page').on('focus', function () {
        var input = $(this).closest('.form-group');
        var errorSpan = $(this).closest('.form-group').find('.error-span');

        if (input.hasClass('has-error')) {
            input.removeClass('has-error has-success has-feedback');
            input.find('.glyphicon-remove, .glyphicon-ok').remove();
            errorSpan.text('');
        }

        if (input.hasClass('has-success')) {
            $(this).on('change', function () {
                $(this).closest('.form-group').removeClass('has-error has-success has-feedback');
                $(this).closest('.form-group').find('.glyphicon-remove, .glyphicon-ok').remove();
            });
        }
    });
});

/*ENABLING WOW.JS*/
$(document).ready(function(){
    new WOW().init();
});


/*PARALAX SECTION*/
$(document).ready(function(){
    var paralSection = $('#paralax_section');

    $(window).scroll(function(){

        if($(window).scrollTop() > 684 && $(window).scrollTop() < 3500){

            var varticalPos = -($(window).scrollTop() / $(paralSection).data('speed'));
            var pos = 'center ' + varticalPos + 'px';

            $(paralSection).css('background-position', pos);
        }

    });
});

/*COFFEE LIST SECTION*/
$(document).ready(function(){
    var subUlContainer = $('.book-sub-ul');
    var startSubUlLi   = $('.book-main-ul-ul').find('li')[0];
    var startSubUl     = $(startSubUlLi).find('ul').html();


    if($(window).width() >= 975){

        $(startSubUlLi).addClass('list-group-coffee-active');
        var counterStartMainUl = 0.2;

        $('.book-main-ul-ul').find('li').each(function(){
            $(this).addClass("wow fadeIn").attr({"data-wow-duration":"0.5s", "data-wow-delay": counterStartMainUl + "s"});
            counterStartMainUl += 0.05;
        });

        $(subUlContainer).html("<ul class='list-group text-center'>" + startSubUl + "</ul>");
        $(startSubUlLi).find('.coffee-li-descr').show();

        var counterStart = 0.2;
        $(subUlContainer).find('ul').find('li').each(function(){
            $(this).addClass("wow fadeIn").attr({"data-wow-duration":"0.5s", "data-wow-delay": counterStart + "s"});
            counterStart += 0.05;
        });

        $('.book-main-ul-ul').find('li').each(function(){
            $(this).click(function(){

                /*setting active class*/
                $('.book-main-ul-ul').find('li').each(function(){
                    $(this).removeClass('list-group-coffee-active');
                });
                $(this).addClass('list-group-coffee-active');
                /*end of setting active class*/

                var counter = 0.2;
                $(this).find('ul').find('li').each(function(){
                    $(this).addClass("wow fadeIn").attr({"data-wow-duration":"0.5s", "data-wow-delay": counter + "s"});
                    counter += 0.05;
                });
                var subUl = $(this).find('ul').html();
                $(subUlContainer).html('');
                $(subUlContainer).html("<ul class='list-group text-center'>" + subUl + "</ul>");

                $(this).find('.coffee-li-descr').slideToggle();
                //Hide the other panels
                $('.coffee-li-descr').not($(this).find('.coffee-li-descr')).slideUp();
            });
        });
    } else {

        $('.book-main-ul-ul').find('.coffee-li-descr').each(function(){
            $(this).show();
        });

        $('.book-main-ul-ul').find('.coffee-li-descr').find('.coffee-li-descr-after').each(function(){
            $(this).css('transform', 'rotate(360deg)');
        });

        $('.book-main-ul-ul').find('.main-list-li').each(function() {

            $(this).click(function () {
                $(this).find('.book-main-ul-sub').slideToggle();
                //Hide the other panels
                $('.book-main-ul-sub').not($(this).find('.book-main-ul-sub')).slideUp();

                if($(this).hasClass('list-group-coffee-active')){
                    $(this).removeClass('list-group-coffee-active');
                } else{
                    $(this).addClass('list-group-coffee-active');
                    $('.book-main-ul-ul').find('.main-list-li').not($(this)).each(function(){
                        $(this).removeClass('list-group-coffee-active');
                    });
                }
            });
        });

        // $(startSubUlLi).addClass('list-group-coffee-active');
        // $(startSubUlLi).find('ul').show();
    }
});




/*ANIMATION MOVING NAVBAR LINKS*/
$(document).ready(function(){
    $('.main_link ,.coffee_link, .equipment_link, .write_us, .emotion_link, .notation_link').click(function() {
        var sectionTo = $(this).attr('href');
        $('html, body').animate({
            scrollTop: $(sectionTo).offset().top
        }, 1000);
    });
});


/*SET VALIDATION CLASSES ON BACK-END VALIDATION (BOOTSTRAP 4)*/
$(document).ready(function(){
    var validFormGroup = $('span.valid').closest('div.form-group');
    var invalidFormGroup = $('span.invalid').closest('div.form-group');
    var validInput = $(validFormGroup).find('input');
    var invalidInput = $(invalidFormGroup).find('input');

    $(validInput).each(function(){
        $(this).addClass('is-valid');
    });
    $(invalidInput).each(function(){
        $(this).addClass('is-invalid');
    });
    $(invalidInput).on('focus',function(){
        $(this).removeClass('is-invalid');
        $(this).parent().find('.invalid-feedback').remove();
    });

});



/*AJAX CONTACT FORM MESSAGES*/
$(document).ready(function(){

    $('#send_message').click(function(e) {
        e.preventDefault();
        var formData = new FormData($('#write_us_form')[0]);

        if (formData.get('name') == false) {
            $('div.name-help').html("<span style='color:red'>Поле не може бути порожнім!</span>");
        } else if (formData.get('email') == false) {
            $('div.email-help').html("<span style='color:red'>Поле не може бути порожнім!</span>");
        } else if (formData.get('ph_number') == false) {
            $('div.phone-help').html("<span style='color:red'>Поле не може бути порожнім!</span>");
        } else if (formData.get('message') == false) {
            $('div.message-help').html("<span style='color:red'>Поле не може бути порожнім!</span>");
        } else {
            $.ajax({
                url: 'includes/ajax_write_to_us_form.php',
                type: 'POST',
                data: formData,
                beforeSend: function(){
                    /*Removing classes from the previous validation (it it took place)*/
                    $('#write_us_form').find('input').each(function(){
                        $(this).removeClass('is-valid is-invalid');
                    });
                    $('#write_us_form').find('textarea').removeClass("is-valid-textarea is-invalid-textarea");
                    /*--end of 'removing' section*/

                    /*Disabling send button and displaying preloader*/
                    $('#send_message').prop("disabled", true);
                    $('#send_message').empty();
                    $('#send_message').append("<img width='30' class='gif-form-preload' src='images/preloader_wait.gif'>");
                },
                success: function (data) {
                    /*Checking what type of data we received (errors comes as JSON string, successful massage as a html code)*/
                    if(isJSON(data)){
                        setTimeout(function(){
                            $('#send_message').prop("disabled", false);
                            $('#send_message').empty();
                            $('#send_message').text("Відправити");

                            var response = JSON.parse(data);

                            if(response.hasOwnProperty('errors')){
                                var form = $('#write_us_form');
                                var inputName = $(form).find("input[name='name']");
                                var inputEmail = $(form).find("input[name='email']");
                                var inputPhone = $(form).find("input[name='ph_number']");
                                var inputText = $(form).find("textarea");

                                if(response.errors.hasOwnProperty('name')){
                                    $(inputName).addClass('is-invalid');
                                    $(inputName).parent().find('div.invalid-feedback').text(response.errors.name);
                                } else {
                                    $(inputName).addClass('is-valid');
                                }
                                if(response.errors.hasOwnProperty('email')){
                                    $(inputEmail).addClass('is-invalid');
                                    $(inputEmail).parent().find('div.invalid-feedback').text(response.errors.email);
                                } else {
                                    $(inputEmail).addClass('is-valid');
                                }
                                if(response.errors.hasOwnProperty('ph_number')){
                                    $(inputPhone).addClass('is-invalid');
                                    $(inputPhone).parent().find('div.invalid-feedback').text(response.errors.ph_number);
                                } else {
                                    $(inputPhone).addClass('is-valid');
                                }
                                if(response.errors.hasOwnProperty('message')){
                                    $(inputText).addClass('is-invalid-textarea');
                                    $(inputText).parent().find('div.message-help').html("<span style='color:#b5102a;'>" + response.errors.message + "</span>").addClass('is-invalid-textarea-helper');
                                } else {
                                    $(inputText).addClass('is-valid-textarea');
                                }
                            } else if(response.hasOwnProperty('create_error')){
                                $('#create-error-message-problem').addClass('alert alert-danger').append("<a href='#' class='close' data-dismiss='alert'>&times;</a><span>" + response.create_error + "</span>");
                            }
                        },1500);
                    } else {
                        setTimeout(function(){
                            $('#send_message').empty();
                            $('#send_message').text("Відправлено!");
                            $('#write-to-us-form-row').empty().html(data);
                        },1500);
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });

            $('div.phone-help').empty();
            $('div.email-help').empty();
            $('div.name-help').empty();
            $('div.message-help').empty();
        }

    });

});


/*TESTS WHETHER STRING IS JSON OR NOT*/
function isJSON(data) {
    try {
        JSON.parse(data);
    }catch(e) {
        return false;
    }
    return true;
}

/*TO UP*/
$(document).ready(function() {

    if ($(window).width() >= 991) {
        $(window).on('scroll', function () {
            if($(window).scrollTop() > 500){
                $('#up').fadeIn();
            }
            if ($(window).scrollTop() == 0) {
                $('#up').fadeOut();
            }
        });
    }

    $('#up').click(function () {
        $('body,html').animate({scrollTop: 0}, 'slow');
    });
});


// /*Remove preloader*/
// ;(function($) {
//     "use strict";
//
//     function preloader(){
//         if ( $('.preloader').length ){
//             $(window).load(function() {
//                 $('.preloader').delay(10).fadeOut('slow');
//                 $('body').delay(10).css({'overflow':'visible'});
//             });
//         }
//     };
//
//     preloader ();
//
// })(jQuery);
// $(document).ready(function() {
//     "use strict";
//     function preloader(){
//         if ( $('.preloader').length ){
//             $(window).on('load', function() {
//                 $('.preloader').delay(100).fadeOut('100');  // 5000 slow
//                 $('body').delay(100).css({'overflow':'visible'}); // 5000
//             });
//         }
//     };
//
//     preloader ();
// });