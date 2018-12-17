<div class="row">
    <div class="col-xs-12 col-sm-8">
        <h3>Історія Ваших замовлень</h3>
        <div class="table-responsive" id="table_with_goods">
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

                $query = "SELECT order_id, order_client_id, order_description, date_of_order, products_and_prices_for_history, order_status ";
                $query .= " FROM my_coffee_orders WHERE order_client_id = {$client_id} ORDER BY order_id DESC";

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

                                $total_sum = 0; // тут буде загальна вартість замовлення
                                $counter = 0;
                                foreach ($client_order as $key => $quantity) {
                                    echo "<p class='p_order_table'>" . $key . ' x ' . $quantity . ' шт. Вартість: ' . number_format(round(($prod_and_prices[$key] * $quantity), 2), 2, '.', ' ') . " грн.</p>";
                                    $total_sum += $prod_and_prices[$key] * $quantity;
                                    $counter++;
                                }
                                ?>
                            </td>
                            <td><?php echo number_format(round($total_sum, 2), 2, '.', ' ') . " грн "; ?></td>
                            <td style="width:100px;"><?php echo ($row['order_status'] == 1) ? "<span style='color: darkgreen;'>Виконане</span>" : "<span style='color: darkred;'>Не виконане</span>"; ?> </td>
                            <td><a href="orders.php?order_id=<?php echo $order_id ?>" class="btn btn-info btn-xs">
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
    <div class="col-xs-12 col-sm-4">
        <?php
        if (isset($_GET['order_id'])) {

            $order_id = mf_get_string('order_id', 'get');

            if (!ctype_digit($order_id)) {
                $error = "Не вірне значення ідентифікатора замовлення!";
                die;
            } else if (empty($order_id) or $order_id == '') {
                $error = "Не передане значення ідентифікатора замовлення!";
                die;
            }

            $order_id = mysqli_real_escape_string($link, strip_tags($order_id));

            $query = "SELECT * FROM `my_coffee_orders` WHERE order_id = {$order_id}";
            $result = mysqli_query($link, $query);
            check_the_query($result);
            if (mysqli_num_rows($result) < 1) {
                echo "<h3 style='margin:0;'>Замовлення з переданим ідентифікатором відсутнє.</h3></td>";
            } else {
                $row = mysqli_fetch_array($result);
                ?>
                <h3>Замовлення № <?php echo $row['order_id']; ?></h3>
                <div class="panel panel-primary panel-order-primary">
                    <div class="panel-heading text-center panel-heading-order">
                        <h3 class="panel-title"><i class="far fa-calendar-alt"></i>&nbsp;&nbsp;<?
                            echo correct_date($row['date_of_order']) . ' року'; ?></h3>
                    </div>
                    <div class="panel-body panel-body-order">
                        <div class="table-responsive">
                            <thead>
                            <tr>
                                <th><span style="font-size: 16px; font-weight:bold;">Ви замовляли: </span></th>
                            </tr>
                            </thead>

                            <table class="table" style="font-size: 14px; margin-bottom:0">
                                <?php
                                $client_order = unserialize($row['order_description']); // десеріалізуємо замовлення в масив
                                $prod_and_prices = unserialize($row['products_and_prices_for_history']); // десеріалізуємо доступні товари та їх ціни на момент замовлення

                                $total_sum = 0; // тут буде загальна вартість замовлення
                                $counter = 0;
                                foreach ($client_order as $key => $quantity) {
                                    $product_with_price = "<tr><td>" . $key . " x " . $quantity . " шт. <br>На суму: " . number_format(round(($prod_and_prices[$key] * $quantity), 2), 2, '.', ' ') . " грн.</td></tr>";
                                    echo $product_with_price;
                                    $total_sum += $prod_and_prices[$key] * $quantity;
                                    $counter++;
                                }
                                echo "<tr><td><u>Загальна вартість замовлення: " . number_format($total_sum, 2, '.', ' ') . " грн.</u></td></tr>"
                                ?>
                            </table>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <p style="font-size: 16px; font-weight:bold; margin-bottom:0;">Статус
                            замовлення: <?php echo ($row['order_status'] == 1) ? "<span style='color: darkgreen;'>Виконане</span>" : "<span style='color: darkred;'>Не виконане</span>"; ?>
                        </p>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>
