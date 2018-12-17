<?php include_once "includes_admin/view_order.php"; ?>

<div class="col-xs-12 ">
    <h2 class="h2-panel">
        <span>Архів замовлень</span>
    </h2>
    <hr>

    <?php if(!isset($_COOKIE['got_info'])): ?>
    <div class="text-justify attention-small">
        <div class="info-text">
            <i class="fas fa-info-circle fa-lg"></i> <b> Зверніть увагу!</b> В даному розділі відображені замовлення, які були відправлені в архів.
            Всі не виконані замовлення - в розділі "Поточні".
        </div>
        <div class="info-button text-center">
            <button style="padding:5px" id="ok-btn-info" class="btn btn-sm btn-success"><i class="far fa-thumbs-up fa-lg"></i></button>
        </div>
    </div>
    <?php endif; ?>

    <div class="row" style="margin:0; padding:0;">
        <div class="col-xs-12 col-sm-3 filter-form-two" >
            <span class="filter-form-label" >Показати по клієнту:</span>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4 filter-form">
                <form class="" action="" method="post">
                <div class="form-group" style="margin-bottom: 5px;">
                    <div class="input-group">
                    <select style="padding:0; height: 26px; " class="form-control" name="client_name" required>
                        <option value="" selected disabled>Клієнт</option>
                        <?php
                         if(isset($_POST['filter_apply']) and isset($_POST['client_name'])){
                             $client = $_POST['client_name'];
                         } else {
                             $client="";
                         }
                        ?>
                        <?php select_all_clients($client); ?>
                    </select>
                    <span class="input-group-btn">
                        <button style="height:26px;width:32px" type="submit" name="filter_apply" class="btn btn-success btn-xs text-center"><i class="fas fa-check"></i></button>
                        <?php echo (isset($_POST['filter_apply'])) ? "<a style='height:26px; width:32px; padding-top:3px; margin-left: 8px; border-radius: 0;' class='btn btn-danger btn-xs' href='./orders.php?route=view_history_orders'><i class=\"fas fa-times\"></i></a>" : ""; ?>
                    </span>

                    </div>
                </div>


            </form>
        </div>
    </div>
    <div class="table-responsive" id="table_with_goods">
        <table class="table table-bordered table-admin table-products table-orders">
            <thead>
                <tr>
                    <th style="vertical-align: middle">ID</th>
                    <th style="vertical-align: middle;">Заклад</th>
                    <th style="vertical-align: middle;">Клієнт</th>
                    <th style="vertical-align: middle">Дата замовлення </th>
                    <th style="vertical-align: middle;">Замовлення </th>
                    <th style="vertical-align: middle;">Статус </th>
                    <th style="text-align: center; vertical-align: middle">Дії</th>
                </tr>
            </thead>
            <tbody>
                
                <?php

                    if(isset($_POST['filter_apply']) and isset($_POST['client_name'])){
                        $client_name = $_POST['client_name'];

                        $query = "SELECT o.order_id, o.order_client_id, c.client_name, c.client_establishment, c.client_person_name, ";
                        $query .= "c.client_phone_number, c.client_discont, o.date_of_order, o.order_description, o.order_sum, o.products_and_prices_for_history ";
                        $query .= "FROM my_coffee_clients AS c LEFT JOIN my_coffee_orders AS o ";
                        $query .= "ON o.order_client_id = c.client_id WHERE o.order_status = 1 AND client_name = '{$client_name}' ORDER BY o.order_id DESC";
                    } else {
                        $query = "SELECT o.order_id, o.order_client_id, c.client_name, c.client_establishment, c.client_person_name, ";
                        $query .= "c.client_phone_number, c.client_discont, o.date_of_order, o.order_description, o.order_sum, o.products_and_prices_for_history ";
                        $query .= "FROM my_coffee_clients AS c LEFT JOIN my_coffee_orders AS o ";
                        $query .= "ON o.order_client_id = c.client_id WHERE o.order_status = 1 ORDER BY o.order_id DESC";
                    }

                    $result = mysqli_query ($link, $query);
                    check_the_query($result);
                    
                    if (mysqli_num_rows($result) < 1) {
                        echo "<td colspan='9' class='text-center'><h3 style='margin:0;'>На даний момент історія замовлень порожня.</h3></td>";
                    } else { 
                        while ($row = mysqli_fetch_assoc ( $result )) {
                ?>
                <tr>
                    <td><?= $row['order_id'] ?></td>
                    <td><?= $row['client_establishment'] ?></td>
                    <td><?= $row['client_name'] ?></td>
                    <td><?= $row['date_of_order'] ?></td>
                    <td class="order-list">
                        <?php
                            $client_order = unserialize($row['order_description']); // десеріалізуємо замовлення в масив                                            
                            $prod_and_prices = unserialize($row['products_and_prices_for_history']); // десеріалізуємо доступні товари та їх ціни на момент замовлення

                            foreach ($client_order as $key => $quantity) {
                                echo "<p class='p_order_table'>" . $key . ' x ' . $quantity . ' шт. Вартість: ' . number_format( round( ($prod_and_prices[$key] * $quantity), 2), 2, '.', ' ' ) . " грн.</p>";
                            }
                            echo "<span class='total_sum' style='margin-top:10px;display:block;'><b>Загальна вартість замовлення: " . number_format($row['order_sum'], 2, '.', ' ') ." грн.</b></span>";
                        ?>
                    </td>
                    <td style="color: darkgreen">Виконане</td>
                    <td class="text-center"><a class='btn btn-primary btn-xs order-details' href="orders.php?order_id=<?php echo $row['order_id']; ?>">Переглянути</a></td>
                </tr>
                <?php
                        } // end of while 
                    }//end of else
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination for table with products -->
<script>

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

</script>
<script>
    $(document).ready(function(){
        $("#ok-btn-info").click(function(){
            var date = new Date('2037');
            document.cookie = "got_info=ok; path=/; expires="+date;
            $(".attention-small").css("display", "none");
        });
    });
</script>