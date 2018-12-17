<?php include_once "includes_admin/view_order.php"; ?>

<div class="col-xs-12 ">
    <h2 class="h2-panel">
        <span>Поточні замовлення</span>
    </h2>
    <span style="color:red"><?php echo isset($error_update_message) ? $error_update_message : '' ?></span>
    <hr>
    <div class="table-responsive" id="table_with_goods">
        <table class="table table-bordered table-admin table-products table-orders current-orders-table">
            <thead>
            <tr>
                <th style="vertical-align: middle">ID</th>
                <th style="vertical-align: middle;">Клієнт</th>
                <th style="vertical-align: middle;">Заклад</th>
                <th style="vertical-align: middle">Контактна особа </th>
                <th style="vertical-align: middle;">Телефон </th>
                <th style="vertical-align: middle">Дата замовлення </th>
                <th style="vertical-align: middle;">Замовлення </th>
                <th style="text-align: center; vertical-align: middle" colspan="2">Дії</th>
            </tr>
            </thead>
            <tbody>

            <?php
            $query = "SELECT o.order_id, o.order_client_id, c.client_name, c.client_establishment, c.client_person_name, ";
            $query .= "c.client_phone_number, c.client_discont, o.date_of_order, o.order_sum, o.order_description, o.products_and_prices_for_history ";
            $query .= "FROM my_coffee_clients AS c LEFT JOIN my_coffee_orders AS o ";
            $query .= "ON o.order_client_id = c.client_id WHERE o.order_status = 0 ORDER BY o.order_id DESC";

            $result = mysqli_query ($link, $query);
            check_the_query($result);

            if (mysqli_num_rows($result) < 1) {
                echo "<td colspan='9' class='text-center'><h3 style='margin:0;'>На даний момент не виконані замовлення відсутні.</h3></td>";
            } else {
                while ($row = mysqli_fetch_assoc ( $result )) {
                    ?>
                    <tr>
                        <td><?= $row['order_id'] ?></td>
                        <td><?= $row['client_name'] ?></td>
                        <td><?= $row['client_establishment'] ?></td>
                        <td><?= $row['client_person_name'] ?></td>
                        <td><?= $row['client_phone_number'] ?></td>
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
                        <td class="text-center"><a style='width:35px;' title="Переглянути" class='btn btn-primary btn-xs order-details' href="orders.php?order_id=<?php echo $row['order_id']; ?>"><i class="far fa-eye fa-lg"></i></a></td>
                        <td class="text-center"><a style='width:35px;' title="Відправити в архів" class='btn btn-danger btn-xs' href="orders.php?to_archive=true&order_id=<?php echo $row['order_id']; ?>"><i class="far fa-trash-alt fa-lg"></i></a></td>
                    </tr>
                    <?php
                } // end of while
            }//end of else
            ?>

            <?php delete_orders() ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Pagination for table with products -->
<script>
    $(document).ready(function () {
        $('#table_with_goods').after("<div id='table_nav'><span style='font-size:18px'>Сторінки: </span></div>");

        var rows_to_show = 5;
        var total_rows_quantity = $('#table_with_goods tbody').find('tr').length;
        var pages_quantity = Math.ceil(total_rows_quantity / rows_to_show);

        for (i = 0; i < pages_quantity; i++) {
            var numb_of_page = i + 1;
            $('#table_nav').append("<a href='#' class='goods-table-a' rel=" + i + "> " + numb_of_page + "</a>");
        }

        $('#table_with_goods tbody').find('tr').hide();
        $('#table_with_goods tbody').find('tr').slice(0, rows_to_show).show();

        $('#table_nav a:first').addClass('goods-table-a-active');
        $('#table_nav a').bind('click', function () {
            $('#table_nav a').removeClass('goods-table-a-active');
            $(this).addClass('goods-table-a-active');

            var current_page = $(this).attr('rel');
            var start = current_page * rows_to_show;
            var end = start + rows_to_show;

            $('#table_with_goods tbody').find('tr').css('opacity', '0.0').hide().slice(start, end).css('display', 'table-row').animate({'opacity': 1}, 300);

        });

    });
</script>