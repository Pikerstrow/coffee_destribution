<!-- Header -->
<?php include_once "includes_admin/admin_header.php"; ?>
<!-- End of header -->

<?php
if (isset($_GET['logout'])) {
   unset($_SESSION['auth']);
   unset($_SESSION['admin']);
   unset($_SESSION['role']);
   exit(header('Location: ../index.php'));
}

?>
<!-- Navigation -->
<?php include_once "includes_admin/admin_navigation.php"; ?>
<!-- End of navigation -->

<!-- Main Content -->
<section id="main-content">
   <div class="content">
      <div class="row">
         <div class="col-lg-12">
            <h2 class="admin-welcome-h2">
               Вітаємо в панелі адміністратора, <span> <?php echo $_SESSION['admin']; ?>!</span>
            </h2>
            <hr>
         </div>
      </div>

      <div class="row">

         <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="panel panel-lightgreen">
               <div class="panel-heading">
                  <div class="row">
                     <div class="col-xs-3 info-panel">
                        <i class="fas fa-donate fa-4x"></i>
                     </div>
                     <div class="col-xs-9 text-right info-panel">
                        <div class="huge"><?php echo get_quantity_of_orders(); ?></div>
                        <div>Замовлень</div>
                     </div>
                  </div>
               </div>
               <a href="orders.php">
                  <div class="panel-footer">
                     <span class="pull-left">До замовлень</span>
                     <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                     <div class="clearfix"></div>
                  </div>
               </a>
            </div>
         </div>

         <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="panel panel-red">
               <div class="panel-heading">
                  <div class="row">
                     <div class="col-xs-3 info-panel">
                        <i class="fas fa-bullhorn fa-4x"></i>
                     </div>
                     <div class="col-xs-9 text-right info-panel">
                        <div class="huge"><?php echo get_quantity_of_notifications(); ?></div>
                        <div>Несправностей</div>
                     </div>
                  </div>
               </div>
               <a href="breakdown_massages.php">
                  <div class="panel-footer">
                     <span class="pull-left">До повідомлень</span>
                     <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                     <div class="clearfix"></div>
                  </div>
               </a>
            </div>
         </div>


         <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="panel panel-yellow">
               <div class="panel-heading">
                  <div class="row">
                     <div class="col-xs-3 info-panel">
                        <i class="fas fa-comment fa-4x"></i>
                     </div>
                     <div class="col-xs-9 text-right info-panel">
                        <div class="huge"><?php echo get_quantity_in_db('my_coffee_messages'); ?></div>
                        <div>Запитань</div>
                     </div>
                  </div>
               </div>
               <a href="questions.php">
                  <div class="panel-footer">
                     <span class="pull-left">До запитань</span>
                     <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                     <div class="clearfix"></div>
                  </div>
               </a>
            </div>
         </div>


         <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="panel panel-lightblue">
               <div class="panel-heading">
                  <div class="row">
                     <div class="col-xs-4 info-panel">
                        <i class="fas fa-users fa-4x"></i>
                     </div>
                     <div class="col-xs-8 text-right info-panel">
                        <div class="huge"><?php echo get_quantity_of_new_clients(); ?></div>
                        <div>Реєстрацій</div>
                     </div>
                  </div>
               </div>
               <a href="clients.php?route=new_clients">
                  <div class="panel-footer">
                     <span class="pull-left">До клієнтів</span>
                     <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                     <div class="clearfix"></div>
                  </div>
               </a>
            </div>
         </div>

      </div>

      <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                  <h3 style="margin:0; margin-bottom: 5px;">Важлива інформація:</h3>
                  <div id="panel-statistic" class="panel panel-statistic">
                     <div class="panel-heading">
                        На даний час, у Вас:
                     </div>
                     <div class="panel-body">
                        <div class="row">
                           <div class="col-xs-2 text-center">
                              <i class="far fa-user fa-lg"></i>
                           </div>
                           <div class="col-xs-7">
                              Клієнтів
                           </div>
                           <div class="col-xs-3 text-right">
                              <?php echo get_quantity_of_exists_clients() ? get_quantity_of_exists_clients() : 0; ?>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-xs-2 text-center">
                              <i class="far fa-file-alt fa-lg"></i>
                           </div>
                           <div class="col-xs-7">
                              Категорій
                           </div>
                           <div class="col-xs-3 text-right">
                              <?php echo get_quantity_in_db('my_coffee_prod_categories') ? get_quantity_in_db('my_coffee_prod_categories') : 0; ?>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-xs-2 text-center">
                              <i class="fas fa-boxes"></i>
                           </div>
                           <div class="col-xs-7">
                              Товарів
                           </div>
                           <div class="col-xs-3 text-right">
                              <?php echo get_quantity_in_db('my_coffee_products') ? get_quantity_in_db('my_coffee_products') : 0; ?>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-xs-2 text-center">
                              <span style="font-size:20px; font-weight: bold" class="icon-coffee_machine"></span>
                           </div>
                           <div class="col-xs-7">
                              Кавоварок
                           </div>
                           <div class="col-xs-3 text-right">
                              <?php echo get_quantity_in_db('my_coffee_machines') ? get_quantity_in_db('my_coffee_machines') : 0; ?>
                           </div>
                        </div>
                     </div>
                  </div>
         </div>

         <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8">



                  <h3 style="margin:0; margin-bottom: 5px;">Останніх 10 замовлень:</h3>
                  <?php

                  $query = "SELECT order_id, date_of_order, order_sum";
                  $query .= " FROM my_coffee_orders ORDER BY order_id DESC LIMIT 10";

                  $result = mysqli_query($link, $query);
                  check_the_query($result);

                  $date_of_orders = [];
                  $sum_of_order = [];


                  if (mysqli_num_rows($result) >= 1) {
                     while ($row = mysqli_fetch_assoc($result)) {

                        $date_of_orders[] = $row['date_of_order'];
                        $sum_of_order[] = $row['order_sum'];
                     }
                  }

                  ?>
                  <?php if (count($date_of_orders) > 0): ?>

                     <script type="text/javascript">
                         google.charts.load('current', {'packages': ['corechart']});
                         google.charts.setOnLoadCallback(drawChart);

                         function drawChart() {
                             var data = google.visualization.arrayToDataTable([
                                 ['Дата', 'Сума, грн'],
                                <?php
                                for ($i = 0; $i < count($date_of_orders); $i++) {
                                   echo "['{$date_of_orders[$i]}'" . "," . "{$sum_of_order[$i]}],";
                                }
                                ?>
                             ]);

                             var options = {
                                 hAxis: {title: 'Дата замовлення', titleTextStyle: {color: '#333'}},
                                 vAxis: {minValue: 0}
                             };

                             var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                             chart.draw(data, options);
                         }
                     </script>
                     <script>
                         $(document).ready(function () {
                             // var width = ($(window).width() - $('#chart_div').offset().left);
                             //     width *= 0.97;
                             var height = $('#panel-statistic').height();

                             $('#chart_div').css('width', 100 + "%");
                             $('#chart_div').css('height', height + "px");
                         });
                     </script>
                     <div id="chart_div" style="margin:0; padding:0;"></div>
                  <?php else: ?>
                     <div class="bg-success text-center no-orders-div">
                        <h3 style="font-size: 20px; margin:0; padding-top:35px;">На даний момент замовлення
                           відсутні.</h3>;
                        <p><i class="far fa-frown fa-10x"></i></p>
                     </div>
                     <script>
                         $(document).ready(function () {
                             // var width = ($(window).width() - $('#chart_div').offset().left);
                             //     width *= 0.97;
                             var height = $('#panel-statistic').height();
                             $('.no-orders-div').css('width', 100 + "%");
                             $('.no-orders-div').css('height', height + "px");
                         });
                     </script>
                  <?php endif; ?>
         </div>




      </div>

      <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-6">
            <h3 style="margin:0; margin-bottom: 5px;">Останні замовлення:</h3>
            <div class="table-responsive" id="table-with-last-orders">
               <table class="table table-admin table-orders-list">
                  <thead>
                  <th>№</th>
                  <th colspan="2">Клієнт</th>
                  <th>Дата</th>
                  <th>Сума, грн</th>
                  <th>Статус</th>
                  </thead>
                  <tbody>
                  <?php
                  $query = "SELECT o.order_id, o.order_client_id, c.client_image, c.client_establishment, ";
                  $query .= "DATE_FORMAT(o.date_of_order, '%d-%m-%Y') AS date, o.order_sum, o.order_status FROM my_coffee_clients AS c INNER JOIN my_coffee_orders AS o ";
                  $query .= "ON o.order_client_id = c.client_id ORDER BY o.order_id DESC LIMIT 6";

                  $result = mysqli_query ($link, $query);
                  check_the_query($result);

                  if (mysqli_num_rows($result) < 1) {
                     echo "<tr><td colspan='6' class='text-center'><h3 style='margin:0;'>На даний момент замовлення відсутні.</h3></td></tr>";
                  } else {
                     while ($row = mysqli_fetch_assoc ( $result )) {
                        $client_avatar = isset($row['client_image']) ? $row['client_image'] : 'avatar.png';
                        ?>
                        <tr>
                           <td style="vertical-align: middle"><?= $row['order_id'] ?></td>
                           <td style="vertical-align: middle"><img style="width:35px; border-radius: 50%; border:1px solid #3f4a53" src="../client_panel/images/<?php echo $client_avatar; ?>"></td>
                           <td style="vertical-align: middle"><?php echo $row['client_establishment'] ?></td>
                           <td style="vertical-align: middle"><?php echo $row['date'] ?></td>
                           <td style="vertical-align: middle"><?php echo  number_format($row['order_sum'], 2, '.', ' '); ?></td>
                           <td style="vertical-align: middle"><?php echo ($row['order_status'] == 1) ? "<span style='color:green'>Виконане</span>" : "<span style='color:darkred'>Не виконане</span>" ?></td>
                        </tr>
                        <?php
                     } // end of while
                  }//end of else
                  ?>

                  </tbody>
               </table>
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-6">
            <h3 style="margin:0; margin-bottom: 5px;">Останні повідомлення про несправності:</h3>
            <div class="table-responsive" id="table-with-last-orders">
               <table class="table table-admin table-orders-list">
                  <thead>
                  <th>№</th>
                  <th colspan="2">Клієнт</th>
                  <th>Дата</th>
                  <th>Повідомлення</th>
                  </thead>
                  <tbody>
                  <?php

                  $query = "SELECT m.breakdown_id, m.coffee_client_id, m.coffee_machine_problem, DATE_FORMAT(m.date_of_notification, '%d-%m-%Y') AS date, c.client_image, c.client_establishment ";
                  $query .= " FROM `my_coffee_breakdown_notifications` AS m INNER JOIN my_coffee_clients AS c ";
                  $query .= "ON m.coffee_client_id = c.client_id WHERE m.notification_status = 0 AND c.client_deleted != true ORDER BY m.breakdown_id DESC LIMIT 6";

                  $result = mysqli_query ($link, $query);
                  check_the_query($result);

                  if (mysqli_num_rows($result) < 1) {
                     echo "<tr><td colspan='5' class='text-center'><h3 style='margin:0;'>На даний момент овідомлення про поломки відсутні.</h3></td></tr>";
                  } else {
                     while ($row = mysqli_fetch_assoc ( $result )) {
                        $client_avatar = isset($row['client_image']) ? $row['client_image'] : 'avatar.png';
                        ?>
                        <tr>
                           <td style="vertical-align: middle"><?= $row['breakdown_id'] ?></td>
                           <td style="vertical-align: middle"><img style="width:35px; border-radius: 50%; border:1px solid #3f4a53" src="../client_panel/images/<?php echo $client_avatar; ?>"></td>
                           <td style="vertical-align: middle"><?php echo $row['client_establishment'] ?></td>
                           <td style="vertical-align: middle"><?php echo $row['date'] ?></td>
                           <td style="vertical-align: middle"><?php echo $row['coffee_machine_problem']; ?></td>
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


</section>
<!-- End of Main Content section -->
<!-- Footer -->
<?php include_once "includes_admin/admin_footer.php"; ?>
<!-- End of footer -->
