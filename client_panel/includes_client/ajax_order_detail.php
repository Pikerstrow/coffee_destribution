<?php

require_once '../../includes/init.php';
require_once '../../includes/db.php';


if (isset($_POST['order_id'])) {

$order_id = $_POST['order_id'];

    if (!ctype_digit($order_id)) {
        echo "Не вірне значення ідентифікатора замовлення!";
        die;
    } else if (empty($order_id) or $order_id == '') {
        echo "Не передане значення ідентифікатора замовлення!";
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

        $result = "<h3 style='margin-top:0;margin-bottom: 17px;'>Замовлення № {$row['order_id']}</h3>";
        $result .= "<div class=\"panel panel-primary panel-order-primary\">";
        $result .= "<div class=\"panel-heading text-center panel-heading-order\">";
        $result .= "<h3 class=\"panel-title\"><i class=\"far fa-calendar-alt\"></i> " . correct_date($row['date_of_order']) . " року; </h3>";
        $result .= "</div>";
        $result .= "<div class=\"panel-body panel-body-order\">";
        $result .= "<div class=\"table-responsive\">";
        $result .= "<thead><tr><th><span style='font-size: 16px; font-weight:bold;'>Ви замовляли: </span></th></tr></thead>";
        $result .= "<table class=\"table\" style=\"font-size: 14px; margin-bottom:0\">";


        $client_order = unserialize($row['order_description']); // десеріалізуємо замовлення в масив
        $prod_and_prices = unserialize($row['products_and_prices_for_history']); // десеріалізуємо доступні товари та їх ціни на момент замовлення


        foreach ($client_order as $key => $quantity) {
            $product_with_price = "<tr><td style='text-align:left;'>" . $key . " x " . $quantity . " шт. <br>На суму: " . number_format(round(($prod_and_prices[$key] * $quantity), 2), 2, '.', ' ') . " грн.</td></tr>";
            $result .= $product_with_price;
        }

        $result .= "<tr><td style='text-align:left;'><u>Загальна вартість замовлення: " . number_format($row['order_sum'], 2, '.', ' ') . " грн.</u></td></tr>";
        $result .= "</table></div></div>";
        $result .= "<div class=\"panel-footer\">";

        $order_status = ($row['order_status'] == 1) ? "<span style='color: darkgreen;'>Виконане</span>" : "<span style='color: darkred;'>Не виконане</span>";

        $result .= "<p style=\"font-size: 16px; text-align:left; font-weight:bold; margin-bottom:0;\">Статус замовлення: {$order_status} </p></div></div>";

        echo $result;

    }
}

















