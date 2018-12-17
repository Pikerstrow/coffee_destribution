<h2 class="h2-panel">
    <span>Історія замовлень</span>
</h2>
<hr>
<div class="row">
    <div class="col-xs-12 col-sm-8">
        <div class="row">
            <div class="col-xs-4">
                <h3 style="margin:0;">Фільтр: </h3>
            </div>
            <div class="col-xs-8 text-right">
                <button onclick="location.href='orders.php'" class="btn btn-primary btn-xs" <?php echo (!isset($_GET['view_current']) and !isset($_GET['view_done'])) ? 'disabled' : '' ?>>Всі</button>
                <button onclick="location.href='orders.php?view_done=true'" class="btn btn-success btn-xs" <?php echo (isset($_GET['view_done'])) ? 'disabled' : '' ?>>Виконані</button>
                <button onclick="location.href='orders.php?view_current=true'" class="btn btn-danger btn-xs" <?php echo (isset($_GET['view_current'])) ? 'disabled' : '' ?>>Не виконані</button>
            </div>
        </div>

        <div class="table-responsive clearfix" id="table_with_goods">
            <table class="table table-bordered table-admin table-products table-orders">
                <thead>
                <tr>
                    <th style="vertical-align: middle">ID</th>
                    <th style="vertical-align: middle;">Дата</th>
                    <th style="vertical-align: middle;">Замовлення</th>
                    <th style="vertical-align: middle">Сума</th>
                    <th style="vertical-align: middle">Статус</th>
                    <th style="vertical-align: middle; text-align: center;">Дія</th>
                </tr>
                </thead>
                <tbody>

                <?php

                $client_id = find_client_by_login($_SESSION['client']);

                if(isset($_GET['view_current'])){
                    $query = "SELECT order_id, order_client_id, order_description, date_of_order, products_and_prices_for_history, order_sum, order_status ";
                    $query .= " FROM my_coffee_orders WHERE order_client_id = {$client_id} AND order_status = 0 ORDER BY order_id DESC";
                } else if(isset($_GET['view_done'])) {
                    $query = "SELECT order_id, order_client_id, order_description, date_of_order, products_and_prices_for_history, order_sum, order_status ";
                    $query .= " FROM my_coffee_orders WHERE order_client_id = {$client_id} AND order_status = 1 ORDER BY order_id DESC";
                } else {
                    $query = "SELECT order_id, order_client_id, order_description, date_of_order, products_and_prices_for_history, order_sum, order_status ";
                    $query .= " FROM my_coffee_orders WHERE order_client_id = {$client_id} ORDER BY order_id DESC";
                }

                $result = mysqli_query($link, $query);
                check_the_query($result);

                if (mysqli_num_rows($result) < 1) {
                    echo "<td colspan='9' class='text-center'><h3 style='margin:0;'>На даний момент Ви не зробили жодгого замовленя.</h3></td>";
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?php echo $order_id = $row['order_id'] ?></td>
                            <td><?php echo correct_date($row['date_of_order']); ?></td>
                            <td class="order-list">
                                <?php
                                $client_order = unserialize($row['order_description']); // десеріалізуємо замовлення в масив
                                $prod_and_prices = unserialize($row['products_and_prices_for_history']); // десеріалізуємо доступні товари та їх ціни на момент замовлення

                                foreach ($client_order as $key => $quantity) {
                                    echo "<p class='p_order_table'>" . $key . ' x ' . $quantity . ' шт. Вартість: ' . number_format(round(($prod_and_prices[$key] * $quantity), 2), 2, '.', ' ') . " грн.</p>";
                                }
                                ?>
                            </td>
                            <td><?php echo number_format($row['order_sum'], 2, '.', ' ') . " грн "; ?></td>
                            <td style="width:100px;"><?php echo ($row['order_status'] == 1) ? "<span style='color: darkgreen;'>Виконане</span>" : "<span style='color: darkred;'>Не виконане</span>"; ?> </td>
                            <td><a href="orders.php?order_id=<?php echo $order_id ?>" class="btn btn-info btn-xs order-details">
                                    Детально</a></td>
                        </tr>
                        <?php
                    } // end of while
                }//end of else
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="order-details-div" class="col-xs-12 col-sm-4 text-center order-detail-container">
        <img src="images/wait.gif" id="img_preload" style="display:none;margin-top:100px;">
        <div class="col-xs-12" id="order-details-place">
            <h3 style="margin-top:10px; color:grey;">Деталі замовлення будуть тут</h3>
            <span class="ord-det-smile"><i style="color:grey; padding:30px" class="far fa-smile fa-7x"></i></span>
        </div>
    </div>
</div>
