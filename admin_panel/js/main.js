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
            //input.find('#quill-prod-descr').addClass('valid-quill');
            input.find('#q-container').addClass('valid-quill');
    });
    $('.error-span').each(function(){
        var input = $(this).closest('.form-group');
            input.addClass('has-error has-feedback');
            input.find('.input-group').append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
            //input.find('#quill-prod-descr').addClass('invalid-quill');
            input.find('#q-container').addClass('invalid-quill');
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



/*For displaying only 2 line of products and changing all rest lines for + few more*/

$(document).ready(function(){

    $('td.order-list').each(function(index, value){

        var all_lines = $(this).find('p.p_order_table').length;
        var show_lines = 2;
        var lines_left = all_lines - show_lines;
        var insert = '<p style="margin:0px;"><b>+ ще ' + correct_spell(lines_left,"товарна позиція","товарні позиції","товарних позицій") + '</b></p>';

        var counter = 0;

        $(this).find('p.p_order_table').each(function(index, value){
            if(index >= show_lines ){
                $(this).hide();
                counter++
            } else {
                counter = 0;
            }
        });

        var last_elem = $(this).children().last();

        if(counter >= 1){
            $(insert).insertBefore(last_elem);
        }

    });
});


function correct_spell(quantity, one, few, many){
    var arr = quantity.toString(10).split('');
    var last = arr[arr.length-1];

    if(last == 1){
        return quantity + ' ' + one;
    } else if(last == 2 || last == 3 || last == 4) {
        return quantity + ' ' + few;
    } else {
        return quantity + ' ' + many;
    }
}

/*TOOLTIP ON EDIT CLIENT DATA PAGE*/
$(document).ready(function(){
    $('#client_login').tooltip();
});


/*SETTING CLIENT DISCOUNT*/
$(document).ready(function(){
    var clientId;

    $('button.btn-set-discount').click(function(e){
        e.preventDefault();
        var modalBody = $(this).closest('.modal-set-discount');

        var clientId = $(this).data('id');
        var clientDiscount = $(this).closest('form').find('input[name="client_discount"]').val();

        var data = {
            client_id: clientId,
            client_discount: clientDiscount
        };

        var errorSpan = $(this).closest('.modal-content').find('span.er-span');


        if (data.client_discount == false) {
            $(errorSpan).text('Помилка: Не передане значення розміру знижки!');
        } else if (parseFloat(data.client_discount) > 99 || parseFloat(data.client_discount) < 0) {
            $(errorSpan).text('Помилка: Розмір знижки не може бути більшим ніж 99% та меншим за 0%');
        } else if (!/^\d{0,2}\.?\d{0,2}?$/.test(data.client_discount)) {
            $(errorSpan).text('Не вірне значення розміру знижки! Допускаються лише числові значення!');
        } else {
            $(modalBody).find('.set-disc-form').css('display', 'none');
            $(modalBody).append('<div class="text-center" style="margin: 0 auto;"><img src="images/wait.gif" class="img_preload"></div>');

            $.ajax({
                url: "includes_admin/ajax_set_discount.php",
                data: {
                    data_for_discount: data
                },
                type: "post",
                complete: function(){
                    $('#img_preload').hide();
                },
                success: function(data){
                    if(!data.error){
                        //$(modalBody).closest('tr').find('td.client-disc-size').text(parseFloat(clientDiscount).toFixed(2));
                        $(".img_preload").css('display', 'none');
                        $(modalBody).closest('.modal-set-discount').append(data);

                        $.get( "includes_admin/ajax_set_discount.php", { client_id: clientId } )
                              .done(function( result ) {
                                  console.log(clientId);
                                  $(modalBody).closest('tr').find('td.client-disc-size').text(result);
                              });

                    }
                }
            })
        }


    });

    $('button.button-close-modal').click(function(){
        $('div.cont').css('display', 'none');
        $("#img_preload").css('display', 'none');
        $(this).closest('.modal-content').find('.set-disc-form').css('display', 'block');
        $(this).closest('.modal-content').find('input[name="client_discount"]').val('')
        $(this).closest('.modal-content').find('span.er-span').text('');
        //alert('ok');
    });

});

/*QUILL EDITOR FOR PRODUCT DESCRIPTION*/
$(document).ready(function(){
    var quill = new Quill('#quill-prod-descr', {
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ list: 'ordered' }, { list: 'bullet' }]
            ]
        },
        theme: 'snow'
    });

    var form = $('#add_product_form');
    $(form).submit(function(){
        console.log(1);
        $(form).find("input[name='description']").val(quill.root.innerHTML);
    });
});