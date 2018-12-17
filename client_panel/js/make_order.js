$(document).ready( function() {

    getTotal();

    // Змінюємо атрибут disabled в input type number на зміну властивості чекбоксу
    $('.products_list_table input[type=checkbox]').on('change', function() {
        var productsQuantityInput = $(this).parent().next().find('input[type=number]');
        if($(this).prop('checked') == true ) {
            var startVal = 1;
            $(productsQuantityInput).removeAttr('disabled').val(startVal);
            var price = $(this).parent().prev().text();
            /* Забираємо пробіли в ціні за допомогою регулярного виразу*/
            price = String(price.replace(/\s/g, ""));
            var value = startVal * parseFloat(price);
            var parent = $(this).parents('tr');
            var sum = $('.sum', parent);
            sum.text(thousandSeparator(value.toFixed(2)));
            getTotal(); 
        }
        if($(this).prop('checked') == false ) {
            $(productsQuantityInput).attr('disabled', 'disabled');                
            $(productsQuantityInput).val('');                
            var parent = $(this).parents('tr');
            var price = $(this).parent().prev().text();
            /* Забираємо пробіли в ціні за допомогою регулярного виразу*/
            price = String(price.replace(/\s/g, ""));
            var sum = $('.sum', parent);
            var value = parseFloat(+$(this).next().text()) * parseFloat(price);
            sum.text(thousandSeparator(value.toFixed(2)));
            getTotal();            
        }
    });        
    
    /* виводимо вартість замовлення по товарних позиціях і виклакаємо функцію для підрахунку загальної вартості 
       змовлення при зміні значення в input number */
    $('.products_list_table input[type=number]').change(function() {
        var parent = $(this).parents('tr');
        var price = $(this).parent().prev().prev().text();
        price = String(price.replace(/\s/g, ""));
        var sum = $('.sum', parent);
        var value = parseFloat(this.value) * parseFloat(price);
        sum.text(thousandSeparator(value.toFixed(2)));
        getTotal();
    });
    
});

// функція для підрахунку загальної вартості замовлення
function getTotal() {
    var total = 0;
    $('.sum').each(function() {
        total += parseFloat(String($(this).text()).replace(/\s/g, ""));
    });
    $('#order_sum').text(thousandSeparator(total.toFixed(2)));
}

// Добавлення пробілу в загальну суму для кращого візуального сприйняття
function thousandSeparator(str) {
    var parts = (str + '').split('.'),
        main = parts[0],
        len = main.length,
        output = '',
        i = len - 1;
    
    while(i >= 0) {
        output = main.charAt(i) + output;
        if ((len - i) % 3 === 0 && i > 0) {
            output = ' ' + output;
        }
        --i;
    }

    if (parts.length > 1) {
        output += '.' + parts[1];
    }
    return output;
};


/*Вспливаюче вікно із замовленням на натиск кнопки "Замовити"*/
$(document).ready(function () {
    var product_name = [];

    var product_quant = [];
    var product_sum = [];

    $('#pre-order').click(function () {

        $('.products_list_table input:checked').each(function () {
            product_name.push($(this).parent().prev().prev().prev().text());
            product_quant.push($(this).parent().next().find('input[type=number]').val());
            product_sum.push($(this).parent().next().next().text());
        });

        if (product_name.length > 0 && product_quant.length > 0) {
            $('h4.modal-title').text('Ваше замовлення:');
            $('#order-container').append(
                '<div class="table-responsive ">' +
                '<table class="table order-confirm-table table-products" id="order-confirm-table">' +
                '<th><b>Товар</b></th>' +
                '<th style="text-align: center;"><b>Кількість, шт.</b></th>' +
                '<th style="text-align: center;"><b>Вартість, грн.</b></th>' +
                '</table>' +
                '</div>'
            );
            var table = $('#order-confirm-table').addClass('table');
            for (i = 0; i < $('.products_list_table input:checked').length; i++) {
                table.append(
                    '<tr><td>' + product_name[i] + '</td><td align="center">' + product_quant[i] + '</td><td align="center">' + product_sum[i] + '</td></tr>'
                );
            }
            table.append(
                '<tr><td colspan="2"><b>Загальна вартість замовлення:</b></td><td align="center"><span style="color:red">' + $('#order_sum').text() + '</span></td></tr>'
            );
            $('#submit-order').removeClass('disabled').addClass('active');

            $('#submit-order').click(function(){
                $("input[name='order_sum']").val(
                    parseFloat(String($('#order_sum').text()).replace(/\s/g, ""))
                );
            });

        } else {
            $('h4.modal-title').text('');
            $('#submit-order').removeClass('active').addClass('disabled');
            $('#order-container').append(
                '<div class="text-center" style="width:100%"><i class="far fa-frown fa-10x"></i></div>' +
                '<h4 class="modal-title" align="center">НАЖАЛЬ, ВИ НІЧОГО НЕ ВИБРАЛИ</h4>' +
                '<br>'
            );
            $('#submit-order').click(function(e){
                e.preventDefault();
            });
        }
    });

    $('#cancel-order').click(function () {
        product_name.length = 0;
        product_quant.length = 0;
        product_sum.length = 0;
        setTimeout(cart_clear, 1000);

    });

    function cart_clear() {
        $('#order-container').text('');
    }

}); // End of document.body option



/*Pagination for orders history table*/

$(document).ready(function () {
    $('#table_with_goods').after("<div id='table_nav'><button class='a-prev btn'><i class=\"fas fa-backward\"></i> </button> <button class='a-next btn'><i class=\"fas fa-forward\"></i> </button> <span class='pages-descr'>Сторінка <span class='page-num'> </span> із <span class='total_pages'> </span></span></div>");

    $('button.a-prev').prop('disabled', true);
    var rows_to_show = 5;
    var total_rows_quantity = $('#table_with_goods tbody').find('tr').length;
    var pages_quantity = Math.ceil(total_rows_quantity / rows_to_show);

    if(pages_quantity == 1){
        $('button.a-next').prop('disabled', true);
    }

    $('#table_with_goods tbody').find('tr').hide();
    $('#table_with_goods tbody').find('tr').slice(0, rows_to_show).show();

    var current_page = 0;

    $('#table_nav .a-next').bind('click', function () {
        $('button.a-prev').prop('disabled', false);
        if(current_page < pages_quantity-1){
            current_page++;
        }
        if (current_page == pages_quantity-1) {
            $(this).prop('disabled', true);
        }

        var start = current_page * rows_to_show;
        var end = start + rows_to_show;

        $('#table_with_goods tbody').find('tr').css('opacity', '0.0').hide().slice(start, end).css('display', 'table-row').animate({'opacity': 1}, 300);
        $('span.page-num').text(current_page+1);
    });

    $('#table_nav .a-prev').bind('click', function () {
        $('button.a-next').prop('disabled', false);

        if(current_page > 0){
            current_page--;
        }
        if (current_page == 0) {
            $(this).prop('disabled', true);
        }

        var start = current_page * rows_to_show;
        var end = start + rows_to_show;

        $('#table_with_goods tbody').find('tr').css('opacity', '0.0').hide().slice(start, end).css('display', 'table-row').animate({'opacity': 1}, 300);
        $('span.page-num').text(current_page+1);
    });

    $('span.page-num').text(current_page+1);
    $('span.total_pages').text(pages_quantity);

});





/*For displaying only 2 line of products and changing all rest lines for + few more*/

$(document).ready(function(){

    $('td.order-list').each(function(index, value){

        var all_lines = $(this).find('p.p_order_table').length;
        var show_lines = 2;
        var lines_left = all_lines - show_lines;
        var insert = '<p style="margin-top:7px; margin-bottom:0px;"><b>+ ще ' + correct_spell(lines_left,"товарна позиція","товарні позиції","товарних позицій") + '</b></p>';

        var counter = 0;

        $(this).find('p.p_order_table').each(function(index, value){
            if(index >= show_lines ){
                $(this).hide();
                counter++
            } else {
                counter = 0;
            }
        });

        if(counter >= 1){
            $(this).append(insert);
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