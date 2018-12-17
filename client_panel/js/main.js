/*SIDEBAR DROPDOWN SUBMENU SWITCHER*/
$(document).ready(function(){
    $('ul.sidebar-menu').find('li.dropdown>a').click(function(){
        $(this).closest('.dropdown').find('a>i.fa-angle-right').toggleClass('down');
        $(this).siblings('ul.sidebar_submenu').slideToggle(200);
    });
});

/* HIDE / SHOW SIDEBAR */
$(document).ready(function(){
    var menuClicksCounter = 0;
    $('#hamburger_menu').click(function(){ 
        if ($(window).width() > 815 ) {     
            if (menuClicksCounter == 0) {
                $('#sidebar-nav').animate({'left' : '-200px'},200);
                menuClicksCounter++;
            } else {
                $('#sidebar-nav').animate({'left' : '0px'},200);
                menuClicksCounter = 0;
            } 
            /*$('#sidebar-nav').toggleClass('sidebar-hide');*/
            $('#main-content').find('div.content').toggleClass('content_full_width');
        } else { 
            $('.sidebar-menu').slideToggle();            
        }
    });
});

/*HIDE INFO BLOCK IN A 3 SECONDS*/
$(document).ready( () => {
    setTimeout( () => {
        $('.info_block').fadeOut();
    }, 3000); 
 });
 
/*FOR THE HELP TO TYPE PHONE NUMBER IN NECESSERY FORMAT (+38(XXX)XXX-XX-XX)*/
$(document).ready(function(){
    
    $('.form_for_phone').on('focus', function() {
        $(this).val('+38(0');
    });

    $('.form_for_phone').on('keyup input', function() {
        var value = $(this).val();
        if (value.length == 7 ) {
            $(this).val(value + ')');
        }
        if (value.length == 11 ) {
            $(this).val(value + '-');
        }
        if (value.length == 14 ) {
            $(this).val(value + '-');
        }
    });
});


/*FOR TRANSFORIMING TO UPPERCASE FIRST LETTER IN EACH NEW WORD IN CLIENT'S NAME*/
$(document).ready(function(){
    $('.form-for-name').on('keyup', function(event) {
        $(this).val( wordToUpperCase( $(this).val() ) );
    });
    
    function wordToUpperCase(str) {
        return str.split(" ").map(function(word) {
            return word.charAt(0).toUpperCase() + word.substring(1).toLowerCase()
        }).join(" ");
    }
});


/*FOR APPLYNG BOOTSTRAP VALIDATION CLASSES TO FORMS*/
$(document).ready(function(){
    /*Adds bootstrap validation classes to form after it's validation on back-end*/   
    $('.ok-span').each(function() {
        var input = $(this).closest('.form-group');
            input.addClass('has-success has-feedback');
            input.find('.input-group').append('<span class="glyphicon glyphicon-ok form-control-feedback"></span>');
    });
    $('.error-span').each(function(){
        var input = $(this).closest('.form-group');
            input.addClass('has-error has-feedback');
            input.find('.input-group').append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
    });
    
    /*Remove bootstrap validation classes on focus and change*/
    $('.reg-page').on('focus', function() {
        var input = $(this).closest('.form-group');

        if ( input.hasClass('has-error') ) {
             input.removeClass('has-error has-success has-feedback');
             input.find('.glyphicon-remove, .glyphicon-ok').remove();
        }

        if ( input.hasClass('has-success') ) {
            $(this).on('change', function() {
                $(this).closest('.form-group').removeClass('has-error has-success has-feedback');
                $(this).closest('.form-group').find('.glyphicon-remove, .glyphicon-ok').remove();
            });
        }
    }); 
});


/*ORDER DETAILS FROM ORDER HISTORY*/
$(document).ready(function(){
    var viewOrderLink;
    var viewOrderLinkSplitted;
    var orderId;

    $('a.order-details').click(function(e){
        $('#order-details-place').css('display', 'none');
        e.preventDefault();
        viewOrderLink = $(this).prop('href');
        viewOrderLinkSplitted = viewOrderLink.split('=');
        orderId = viewOrderLinkSplitted[viewOrderLinkSplitted.length - 1];

        $.ajax({
            url: "includes_client/ajax_order_detail.php",
            data: {
                order_id: orderId
            },
            type: "post",
            beforeSend: function(){
                $('#img_preload').show();
            },
            complete: function(){
                $('#img_preload').hide();
            },
            success: function(data){
                if(!data.error){
                    $('#order-details-div').html(data);
                    //console.log(data);
                }
            }
        })
    });
});

