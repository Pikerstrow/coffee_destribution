<?php
require_once '../../includes/init.php';
require_once '../../includes/db.php';

if(isset($_POST['data_for_discount'])){

    $data_for_discount = $_POST['data_for_discount'];

    $client_id = $data_for_discount['client_id'];
    $client_discount = $data_for_discount['client_discount'];

    if (!ctype_digit($client_id)) {
        echo "<div class='cont'><span>Помилка: Не вірне значення ідентифікатора клієнта!</span></div>";
        die;
    } else if (empty($client_id) or $client_id == '') {
        echo "<div class='cont'><span>Помилка: Не передане значення ідентифікатора клієнта!</span></div>";
        die;
    }

    if (!is_numeric($client_discount)) {
        echo "<div class='cont'><span>Помилка: Не вірне значення розміру знижки! Допускаються лише числові значення!</span></div>";
        die;
    } else if (empty($client_discount) or $client_discount == '') {
        echo "<div class='cont'><span>Помилка: Не передане значення розміру знижки!</span></div>";
        die;
    } else if ($client_discount > 99 or $client_discount < 0) {
        echo "<div class='cont'><span>Помилка: Розмір знижки не може бути більшим ніж 99% та меншим за 0%</span></div>";
        die;
    }

    $query = "UPDATE `my_coffee_clients` SET client_discont = {$client_discount} WHERE client_id = {$client_id}";

    $result = mysqli_query($link, $query);
    check_the_query($result);

    if (mysqli_affected_rows($link) < 1) {
        echo "<div class='cont'><div class='alert alert-danger'><h3 style='margin:0; color:darkred;'>Запит не вдався! Спробуйте перезавантажити сторінку і надіслати запит знову.</h3></div>";
    } else {
        $result = "<div class='cont'><div class='alert alert-success'>";
        $result .= "<h3 style='margin:0; color:darkgreen;'><i class='far fa-check-circle fa-lg'></i> Знижку успішно встановлено!</h3>";
        $result .= "</div></div>";

        echo $result;
    }

}

if(isset($_GET['client_id'])){

    $cl_id = $_GET['client_id'];

    if (!ctype_digit($cl_id)) {
        echo "Упс...";
    } else if (empty($cl_id) or $cl_id == '') {
        echo "Упс...";
    }

    $query = "SELECT client_discont FROM `my_coffee_clients` WHERE client_id = {$cl_id}";

    $result = mysqli_query($link, $query);
    check_the_query($result);

    if (mysqli_num_rows($result) < 1) {
        echo "Упс...";
    } else {
       $row = mysqli_fetch_row($result);
       echo $row[0];
    }

}