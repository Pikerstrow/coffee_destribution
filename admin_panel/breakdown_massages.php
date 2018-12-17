<!-- Header -->
<?php include_once "includes_admin/admin_header.php"; ?>
<!-- End of header -->
<!-- Navigation -->

<?php delete_massage() ?>

<?php include_once "includes_admin/admin_navigation.php"; ?>
<!-- End of navigation -->



<!-- Main Content -->
<section id="main-content">
    <div class="content">
        <div class="row">
            <div class="col-lg-12">

                <div class="col-xs-12 ">
                    <h2 class="h2-panel">
                        <span>Повідомлення про несправності</span>
                    </h2>
                    <hr>


                    <div class="table-responsive" id="table_with_goods">
                        <table class="table table-bordered table-admin table-products table-orders">
                            <thead>
                            <tr>
                                <th style="vertical-align: middle">ID</th>
                                <th style="vertical-align: middle;">Заклад</th>
                                <th style="vertical-align: middle">Конт. особа </th>
                                <th style="vertical-align: middle;">Телефон </th>
                                <th style="vertical-align: middle;">Кавоварка </th>
                                <th style="vertical-align: middle;">Проблема</th>
                                <th style="vertical-align: middle;">Дата</th>
                                <th style="text-align: center; vertical-align: middle;" >Дії</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
$query = <<<SQL
SELECT m.breakdown_id, m.coffee_client_id, m.coffee_machine_title, m.coffee_machine_problem, m.date_of_notification, 
m.notification_status, c.client_establishment, c.client_person_name, c.client_phone_number 
FROM `my_coffee_breakdown_notifications` AS m LEFT JOIN my_coffee_clients AS c 
ON m.coffee_client_id = c.client_id WHERE m.notification_status = 0 AND c.client_deleted != true
SQL;
                            $result = mysqli_query ($link, $query);
                            check_the_query($result);

                            if (mysqli_num_rows($result) < 1) {
                                echo "<td colspan='8' class='text-center'><h3 style='margin:0;'>На даний момент Повідомлення про несправності відсутні.</h3></td>";
                            } else {
                                while ($row = mysqli_fetch_assoc ( $result )) {
                                    ?>
                                    <tr class="cl-list-tr">
                                        <td style="vertical-align: middle;"><?php echo $row['breakdown_id'] ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['client_establishment'] ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['client_person_name'] ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['client_phone_number'] ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['coffee_machine_title'] ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['coffee_machine_problem'] ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['date_of_notification'] ?></td>
                                        <td class="text-center">
                                            <a style="padding: 5px 10px;" class='btn btn-danger btn-xs delete-cl-link' href="breakdown_massages.php?delete_massage=true&token=<?php echo session_id(); ?>&massage_id=<?php echo $row['breakdown_id'] ?>">
                                                <i class="far fa-trash-alt fa-lg"></i>
                                            </a>
                                        </td>

                                    </tr>
                                    <?php
                                } // end of while
                            }//end of else
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>




            </div>
        </div>

    </div>
</section>
<!-- End of Main Content section -->
<!-- Footer -->
<?php include_once "includes_admin/admin_footer.php"; ?>
<!-- End of footer -->


