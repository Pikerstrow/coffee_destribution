<!-- Header -->
<?php include_once "includes_client/client_header.php"; ?>
<!-- End of header -->  
<?php
if (isset($_GET['logout'])) {
    unset($_SESSION['auth']);
    unset($_SESSION['client']);
    unset($_SESSION['role']);
    exit(header('Location: ../index.php'));
}

?>
    <!-- Navigation -->
    <?php include_once "includes_client/client_navigation.php"; ?>
    <!-- End of navigation -->
    
    <!-- Main Content -->
    <section id="main-content">
        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="admin-welcome-h2">
                        Вітаємо в особистому кабінеті, <span> <?php echo $_SESSION['client']; ?>!</span>
                    </h2>
                    <hr>
                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-7">
                          <div class="row">
                             <div class="col-xs-12 col-sm-6">

                                <div class="panel panel-primary">
                                   <div class="panel-heading">
                                      <div class="row">
                                         <div class="col-xs-3 info-panel">
                                            <i class="fas fa-boxes fa-4x"></i>
                                         </div>
                                         <div class="col-xs-9 text-right info-panel">
                                            <div class="huge"><?php echo get_quantity_in_db('my_coffee_products'); ?></div>
                                            <div>Доступно товарів</div>
                                         </div>
                                      </div>
                                   </div>
                                   <a href="orders.php?route=make_order">
                                      <div class="panel-footer">
                                         <span class="pull-left">До замовлень</span>
                                         <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                         <div class="clearfix"></div>
                                      </div>
                                   </a>
                                </div>

                             </div>

                             <div class="col-xs-12 col-sm-6">

                                <div class="panel panel-green" id="panel-green">
                                   <div class="panel-heading">
                                      <div class="row">
                                         <div class="col-xs-3 info-panel">
                                            <i class="fas fa-clipboard-list fa-4x"></i>
                                         </div>
                                         <div class="col-xs-9 text-right info-panel">
                                            <div class="huge"><?php echo get_quantity_in_db('my_coffee_prod_categories'); ?></div>
                                            <div>Доступно категорій</div>
                                         </div>
                                      </div>
                                   </div>
                                   <a href="orders.php?route=make_order">
                                      <div class="panel-footer">
                                         <span class="pull-left">До замовлень</span>
                                         <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                         <div class="clearfix"></div>
                                      </div>
                                   </a>
                                </div>

                             </div>
                          </div>
                          <div class="row">
                             <div class="col-xs-12">
                                <h3 style="margin:0">Ваші останні замовлення:</h3>
                                <?php

                                $client_id = find_client_by_login($_SESSION['client']);

                                $query = "SELECT order_id, order_client_id, date_of_order, order_sum ";
                                $query .= " FROM my_coffee_orders WHERE order_client_id = {$client_id} ORDER BY order_id DESC LIMIT 10";

                                $result = mysqli_query($link, $query);
                                check_the_query($result);

                                $date_of_orders = [];
                                $sum_of_order = [];

                                if (mysqli_num_rows($result) >= 1){
                                   while ($row = mysqli_fetch_assoc($result)) {

                                      $date_of_orders[] = $row['date_of_order'];
                                      $sum_of_order[] = $row['order_sum'];

                                   }
                                }

                                ?>
                                <?php if(count($date_of_orders) > 0): ?>
                                   <script type="text/javascript">
                                       google.charts.load('current', {'packages':['corechart']});
                                       google.charts.setOnLoadCallback(drawChart);

                                       function drawChart() {
                                           var data = google.visualization.arrayToDataTable([
                                               ['Дата', 'Сума,грн'],

                                              <?php
                                              for ($i = 0; $i < count($date_of_orders); $i++) {
                                                 echo "['{$date_of_orders[$i]}'" . "," . "{$sum_of_order[$i]}],"; // Дана строка замінює частину js коду закоментованого нижче а саме ['Posts', 1000] і тд
                                              }
                                              ?>

                                           ]);

                                           var options = {
                                               width: 100 + '%',
                                               hAxis: {title: 'Дата замовлення',  titleTextStyle: {color: '#333'}},
                                               vAxis: {minValue: 0}
                                           };

                                           var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                                           chart.draw(data, options);
                                       }
                                   </script>
                                   <script>
                                       $(document).ready(function () {
                                           var height = $('#panel-green').height();
                                           var height *= 3;
                                           $('#chart_div').css('height', height + "px");
                                       });
                                   </script>
                                   <div id="chart_div" style="width: 100%; height: 316px; margin:0; padding:0;"></div>
                                <?php else: ?>
                                   <div class="bg-success text-center no-orders-div">
                                      <h3 style="font-size: 20px; margin:0; padding-top:35px;">Історія Ваших замовлень порожня.</h3>;
                                      <p><i style="margin-top:2%;" class="far fa-frown fa-10x"></i></p>
                                   </div>
                                   <script>
                                       $(document).ready(function () {
                                           var width = ($(window).width() - $('#inform-panel').offset().left) - 25;
                                           //var height = ($(window).height() - $('#chart_div').offset().top) - 15;
                                           var height = 325;
                                           $('.no-orders-div').css('width', width + "px");
                                           $('.no-orders-div').css('height', height + "px");
                                       });
                                   </script>
                                <?php endif; ?>
                             </div>
                          </div>
                       </div>
                       <div class="col-xs-12 col-sm-12 col-md-5">
                          <div class="panel panel-primary panel-order-primary" id="inform-panel">
                             <div class="panel-heading text-center panel-heading-order">
                                <h3 class="panel-title">
                                   <i class="fas fa-info-circle"></i> Інформативно:
                                </h3>
                             </div>
                             <div class="panel-body panel-body-order">
                                <p>
                                   Використовуючи особистий кабінет клієнта, Ви маєте змогу швидко та зручно:
                                </p>
                                <div class="table-responsive">
                                   <table class="table">
                                      <tr>
                                         <td style='text-align:left;width:170px;'><b>Робити замовлення продукції</b></td>
                                         <td>Для цього Вам необхідно перейти в розділ "Замовлення" в меню кабінету та обрати підрозділ "Нове замовлення". </td>
                                      </tr>
                                      <tr>
                                         <td style='text-align:left;width:170px;'><b>Повідомляти про несправність кавоварки</b></td>
                                         <td>Для цього Вам необхідно перейти в розділ "Повідомлення" в меню кабінету. </td>
                                      </tr>
                                      <tr>
                                         <td style='text-align:left;width:170px;'><b>Переглядати історію всіх замовлень</b></td>
                                         <td>Для цього Вам необхідно перейти в розділ "Замовлення" в меню кабінету та обрати підрозділ "Історія". </td>
                                      </tr>
                                      <tr>
                                         <td style='text-align:left;width:170px;'><b>Переглядати та редагувати особисті дані</b></td>
                                         <td>Для цього Вам необхідно перейти в розділ "Профіль" в меню кабінету. </td>
                                      </tr>
                                   </table>
                                </div>
                             </div>
                          </div>
                       </div>














                </div>
            </div>
            
        </div>
    </section>
    <!-- End of Main Content section -->
    <!-- Footer -->
    <?php include_once "includes_client/client_footer.php"; ?>
<!-- End of footer -->
