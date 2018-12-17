<!-- Header -->
<?php include_once "includes_admin/admin_header.php"; ?>
<!-- End of header -->
<!-- Navigation -->

<?php delete_massage_form_visitor() ?>

<?php include_once "includes_admin/admin_navigation.php"; ?>
<!-- End of navigation -->



<!-- Main Content -->
<section id="main-content">
    <div class="content">
        <div class="row">
            <div class="col-lg-12">

                <div class="col-xs-12 ">
                    <h2 class="h2-panel">
                        <span>Повідомлення від відвідувачів</span>
                    </h2>
                    <hr>


                    <div class="table-responsive" id="table_with_goods">
                        <table class="table table-bordered table-admin table-products table-orders">
                            <thead>
                            <tr>
                                <th style="vertical-align: middle">ID</th>
                                <th style="vertical-align: middle;">Ім'я</th>
                                <th style="vertical-align: middle;">Телефон </th>
                                <th style="vertical-align: middle;">Email </th>
                                <th style="vertical-align: middle;">Повідомлення</th>
                                <th style="vertical-align: middle;">Дата</th>
                                <th style="text-align: center; vertical-align: middle;" >Дії</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $query = "SELECT message_id, message_name, message_ph_number, message_email, message_message, DATE_FORMAT(message_date, '%d-%m-%y') AS message_date FROM `my_coffee_messages`";
                            $result = mysqli_query ($link, $query);
                            check_the_query($result);

                            if (mysqli_num_rows($result) < 1) {
                                echo "<td colspan='8' class='text-center'><h3 style='margin:0;'>На даний момент Повідомлення від користувачів відсутні.</h3></td>";
                            } else {
                                while ($row = mysqli_fetch_assoc ( $result )) {
                                    ?>
                                    <tr class="cl-list-tr">
                                        <td style="vertical-align: middle;"><?php echo $row['message_id'] ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['message_name'] ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['message_ph_number'] ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['message_email'] ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['message_message'] ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['message_date'] ?></td>
                                        <td class="text-center">
                                            <a style="padding: 5px 10px;" class='btn btn-danger btn-xs delete-cl-link' href="write_us_messages.php?delete_massage=true&token=<?php echo session_id(); ?>&massage_id=<?php echo $row['message_id'] ?>">
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


