<?php
// Пепрвіряємо на наявність ерсональної знижки в клієнта
$discount = find_client_discount($_SESSION['client']);


// Вибираємо всі наявні на дату замовлення товари та їх ціни на випадок, якщо адміном будуть видалені або змінені товари до моменту обробки замовлення
$avail_prods_and_prices = take_all_products_and_prices($discount);


if (isset($_POST['make_order'])) {

   $a_data = $a_error = [];

   $a_data['ordered_products'] = mf_get_array('ordered_products', 'post');
   $a_data['checkboxes'] = mf_get_array('checkbox', 'post');
   $a_data['order_sum'] = mf_get_string('order_sum', 'post');

   /*ВАЛІДАЦІЯ*/
   /*У випадку, якщо клієнт в поле для кількості товару введе не число - в змінну $bad_key
      запишеться id товару, який передавали із нечисловим значенням в кількості.
      Використовується для підсвітки інпуту червоним кольором у випадку такої помилки
   */
   if (ordered_products_bad_quantity($a_data['ordered_products']))
      $bad_key = ordered_products_bad_quantity($a_data['ordered_products']);
   else
      $bad_key = NULL;




   /*Перевірка чи відмічені чекбокси та чи передані товари*/
   if ([] === $a_data['checkboxes'] or [] === $a_data['ordered_products']) {
      $a_error['checkboxes'] = 'Ви не вибрали жодного товару!';
      /*перевірка чи всі поля для введення кількості товарів передали числове значення*/
   } else if (!check_ordered_products($a_data['ordered_products'])) {
      $a_error['ordered_products'] = 'Кількість товарів може бути лише числовим значенням!';
   }

   /*Додаткова валідація - 2: первірка id товару на навність в БД */
   $sql = " SELECT product_id, product_title FROM  my_coffee_products";

   if ($result = mysqli_query($link, $sql)) {
      if ($rows = mysqli_num_rows($result)) {
         // формуємо один масив для можливості порівняння id які надійшли від користувача із тими, що є в бд.
         // id від користувача - є ключами масиву $a_data['ordered_products']
         // формуємо масив із назвами всіх товарів, де ключами будуть їх айді
         while ($row = mysqli_fetch_assoc($result)) {
            $prods_id[] = $row['product_id'];
            $prods_names[$row['product_id']] = $row['product_title'];
         }

         if (array_diff_key($a_data['ordered_products'], array_flip($prods_id))) {
            $a_error['ordered_products'] = 'Вибраного Вами товару не існує!!!';
         }

      } else {
         echo "<h1>Товари відсутні</h1>";
      }
   } else {
      echo "<h1>Сталася помилка з'єднання з БД. Повідомте, будь ласка, про дану помилку власника ресурсу</h1>";
   }

   /*ФОРМУЄМО ЗАМОВЛЕННЯ*/
   if ([] === $a_error) {

      // Формуємо масив із замовленням, де ключами будуть назви товарів, а значеннями - їх кількість
      foreach ($a_data['ordered_products'] as $key => $value) {
         $order[$prods_names[$key]] = $value;
      }

      $client_order = serialize($order);
      $prods_and_prices = serialize($avail_prods_and_prices);
      $date_of_order = date('Y-m-d');

      $client_id = find_client_by_login($_SESSION['client']);
      $order_sum = $a_data['order_sum'];

      $sql = "INSERT INTO my_coffee_orders (order_client_id, order_description, products_and_prices_for_history, order_sum, date_of_order) ";
      $sql .= "VALUES (?, ?, ?, ?, ?)";


      if ($stmt = mysqli_prepare($link, $sql)) {
         mysqli_stmt_bind_param($stmt, 'issss', $client_id, $client_order, $prods_and_prices, $order_sum, $date_of_order);
         mysqli_stmt_execute($stmt);
         mysqli_stmt_close($stmt);
         mysqli_close($link);


         // Sending email notification to admin about new order
         $to = ADMIN_EMAIL;
         $subject = 'Нове замовлення';

         $message = " 
            <html> 
                <head> 
                    <title>Нове замовлення</title> 
                </head> 
                <body> 
                    <div class='container'>
                        <p>На вашому сайті нове замовлення від <b> {$_SESSION['client']}!</b></p>
                        <p>Переглянути деталі замовлення можна в особистому кабінеті.</p>
                        <a href='coffee.web-ol-mi.pp.ua/admin_panel' class='btn btn-primary'>Перейти в кабінет</a>
                    </div>                    
                </body> 
            </html>";
         $headers = 'From: admin@kostacoffee.com.ua' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=utf-8';

         mail($to, $subject, $message, $headers);

         set_success('Замовленя успішно сформоване!');
         header('Location: ./orders.php?route=make_order');
      } else {
         $a_error['db_connection'] = 'Помилка з\'єднання із базою даних!';
      }

   } else {
      echo "<div class='alert alert-danger alert-form-errors'>";
      echo "<h3 align='center'>ФОРМА МІСТИТЬ ПОМИЛКИ</h3>";
      foreach ($a_error as $value) {
         echo "<p><b>Помилка: </b>" . $value . "!</p>";
      }
      echo "</div>";
   }

}


?>

<!-- Navigation -->
<?php include_once "includes_client/client_navigation.php"; ?>
<!-- End of navigation -->

<!-- Main Content -->
<h2 class="h2-panel">
   <span>Нове замовлення</span>
</h2>
<hr>
<div class="row">
   <div class="col-xs-12">
      <small class="text-justify"><b>Важливо!</b> Для того, щоб зробити замовлення, Вам необхідно вибрати із списку
         продукцію, яка Вас цікавить, поставити відмітку
         у чекбоксі для здійснення вибору та ввести кількість у відповідному полі. Псля вибору всіх необхідних
         товарів,
         необхідно натиснути кнопку "Замовити" під таблицею.
      </small>
      <hr>
      <div class="all-products">
         <?php
         $counter = 1; // For JS and bootstrap accordion
         $query = "SELECT * FROM my_coffee_prod_categories";
         $stmt = mysqli_prepare($link, $query);
         mysqli_stmt_execute($stmt);
         mysqli_stmt_bind_result($stmt, $cat_id, $cat_title);
         mysqli_stmt_store_result($stmt);
         $result = mysqli_stmt_num_rows($stmt);

         if (!$result) {
            ?>
            <div class="container">
               <div class="row">
                  <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 ">
                     <div class="panel panel-default login-box">
                        <div class="panel-body">
                           <div class="text-center">

                              <h3 class="panel-h3"><i class="far fa-frown fa-10x"></i></h3>
                              <h3>Досутпні до замовлення товари відсутні!</h3>
                              <br>

                              <p class="text-center">
                                 <a class="btn btn-sm btn-primary" href="../../index.php">Повернутися на
                                    сайт</a>
                              </p>
                              <div class="panel-body">
                              </div><!-- Body-->

                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <?php
         } else {
         ?>

         <form action="" method="POST" role="form" id="order-form">
            <div class="panel-group" id="accordion">
               <div class="panel panel-default">
                  <?php
                  while (mysqli_stmt_fetch($stmt)) {
                     ?>
                     <div class="panel-heading">
                        <h4 class="panel-title panel-title-order">
                           <a style="text-decoration: none; display:inline-block; width:100%;" data-toggle="collapse"
                              data-parent="#accordion"
                              href="#collapse<?php echo $counter; ?>"><?php echo $cat_title; ?></a>
                        </h4>
                     </div>
                     <div id="collapse<?php echo $counter; ?>"
                          class="panel-collapse collapse <?php echo($counter == 1 ? 'in' : ''); ?>">
                        <div class="panel-body">
                           <?php
                           $query = "SELECT product_id, product_image, product_title, product_description, product_price, product_category_id FROM my_coffee_products WHERE product_category_id = {$cat_id}";
                           $result = mysqli_query($link, $query);

                           if (mysqli_num_rows($result)) {
                              ?>
                              <div class="table-responsive"
                                   id="table_with_goods<?php echo $counter; ?>">
                                 <table class="table table-bordered table-admin table-products products_list_table">
                                    <thead>
                                    <tr>
                                       <th style="vertical-align: middle">ID</th>
                                       <th style="vertical-align: middle; width:120px;">Фото</th>
                                       <th style="vertical-align: middle; width:150px; ">Назва</th>
                                       <th style="vertical-align: middle">Опис</th>
                                       <th style="vertical-align: middle; width: 90px;">Ціна,
                                          <small>грн</small>
                                       </th>
                                       <th style="vertical-align: middle">Замовити</th>
                                       <th style="vertical-align: middle">Кількість</th>
                                       <th style="vertical-align: middle; width: 110px;">Вартість,
                                          <small>грн</small>
                                       </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                       <tr>
                                          <td
                                             style="vertical-align: middle"><?php echo $id = $row['product_id']; ?></td>
                                          <td
                                             style="vertical-align: middle"><?php echo "<img class='prod-img' width='45' src='../images/products/" . image_is_set($row['product_image']) . "'>" ?></td>
                                          <td style="vertical-align: middle"><?php echo $row['product_title']; ?></td>
                                          <td
                                             style="vertical-align: middle"><?php echo $row['product_description']; ?></td>
                                          <td
                                             style="vertical-align: middle"><?php echo number_format(round($row['product_price'] * (1 - ($discount / 100)), 2), 2, '.', ' ') ?></td>
                                          <td style="vertical-align: middle">
                                             <input type="checkbox"
                                                    name="checkbox[<?php echo $id ?>]"
                                                    style="width: 18px; height: 18px; margin-left: 0;"
                                                    value="<?php echo $id ?>" <?php echo((isset($a_data['checkboxes'][$id]) and $a_data['checkboxes'][$id] == $id) ? 'checked="checked"' : '') ?>>
                                          </td>
                                          <td style="vertical-align: middle">
                                             <input type="hidden" name="order_sum">
                                             <input type="number"
                                                    name="ordered_products[<?= $id ?>]"
                                                    value="<?php echo((isset($a_data['ordered_products'][$id])) ? $a_data['ordered_products'][$id] : '') ?>"
                                                    style="width: 80px;"
                                                    min='0' <?= ((isset($a_data['checkboxes'][$id]) and $a_data['checkboxes'][$id] == $id) ? '' : 'disabled="disabled"') ?>>
                                             <?php echo((isset($a_error['ordered_products']) and $bad_key == $id) ? '<span class="err-span"></span>' : '') ?>
                                          </td>
                                          <td style="vertical-align: middle" class='sum'>
                                             <span>0.00</span>
                                          </td>
                                       </tr>
                                    <?php endwhile ?>
                                    </tbody>
                                 </table>
                              </div>

                              <?php
                           } else {
                              echo "<h3 style='margin:0;' class='text-center'>В даній категорії товари відсутні!</h3>";
                           }
                           ?>
                        </div>
                     </div>
                     <!-- JS PAGINATION FOR TABLES WITH PRODUCTS-->
                     <script>
                         $(document).ready(function () {
                             $('#table_with_goods<?php echo $counter; ?>').after("<div id='table_nav<?php echo $counter; ?>'><span style='font-size:18px'>Сторінки: </span></div>");

                             var rows_to_show = 6;
                             var total_rows_quantity = $('#table_with_goods<?php echo $counter; ?> tbody').find('tr').length;
                             var pages_quantity = Math.ceil(total_rows_quantity / rows_to_show);

                             for (i = 0; i < pages_quantity; i++) {
                                 var numb_of_page = i + 1;
                                 $('#table_nav<?php echo $counter; ?>').append("<a href='#' class='goods-table-a' rel=" + i + "> " + numb_of_page + "</a>");
                             }

                             $('#table_with_goods<?php echo $counter; ?> tbody').find('tr').hide();
                             $('#table_with_goods<?php echo $counter; ?> tbody').find('tr').slice(0, rows_to_show).show();

                             $('#table_nav<?php echo $counter; ?> a:first').addClass('goods-table-a-active');
                             $('#table_nav<?php echo $counter; ?> a').bind('click', function () {
                                 $('#table_nav<?php echo $counter; ?> a').removeClass('goods-table-a-active');
                                 $(this).addClass('goods-table-a-active');

                                 var current_page = $(this).attr('rel');
                                 var start = current_page * rows_to_show;
                                 var end = start + rows_to_show;

                                 $('#table_with_goods<?php echo $counter; ?> tbody').find('tr').css('opacity', '0.0').hide().slice(start, end).css('display', 'table-row').animate({'opacity': 1}, 300);

                             });
                         });
                     </script>
                     <?php
                     $counter++;
                  } // End of the first while
                  ?>
               </div>
            </div>

            <?php
            } // End of else
            ?>
            <hr>
            <div class="row">
               <div class="col-sm-6 col-xs-12 order-total-sum-col">
                  <p class="for-order-sum">ЗАГАЛЬНА ВАРТІСТЬ ЗАМОВЛЕННЯ: <span id="order_sum"
                                                                               style="color:red;">0.00</span>
                     грн</p>
               </div>
            </div>
            <br>

            <!--<input type="submit" onclick="event.preventDefault()" class="order-submit" name="pre_order" id="pre_order" value="Замовити!">-->

            <div class="text-center my-order-div-subm">
               <!-- Button HTML (to Trigger Modal) -->
               <div id="pre-order-link-container">
                  <a href="#myModal" class="trigger-btn order-submit" data-toggle="modal"
                     id="pre-order">Замовити!</a>
               </div>
            </div>

            <!-- ВСПЛИВАЮЧЕ ВІКНО ДЛЯ ПІДТВЕРДЖЕННЯ ЗАМОВЛЕННЯ -->
            <!-- Modal HTML -->
            <div style="padding-left:0" id="myModal" class="modal fade" data-keyboard="false"
                 data-backdrop="static">
               <div class="col-md-6 col-md-offset-3 col-xs-12 col-sm-8 col-sm-offset-2">
                  <div class="modal-dialog modal-confirm">
                     <div class="modal-content">
                        <div class="modal-header">
                           <div class="icon-box">
                              <!--<i class="material-icons">&#xE876;</i>-->
                              <i class="fas fa-check"></i>
                           </div>
                           <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body" id="order-container">
                        </div>
                        <div class="modal-footer">
                           <div class="row">
                              <div class="col-xs-6">
                                 <button type="submit" id="submit-order"
                                         class="btn btn-success btn-block" name="make_order">
                                    <i class="far fa-check-circle"></i> Підтвердити
                                 </button>
                              </div>
                              <div class="col-xs-6">
                                 <button id="cancel-order" class="btn btn-warning btn-block"
                                         data-dismiss="modal"><i class="far fa-times-circle"></i>
                                    Відмінити
                                 </button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
<script>
    $(document).ready(function () {
        $('#pre-order').click(function () {
            if ($('#submit-order').hasClass('disabled')) {
                $('.icon-box').css('background', '#a4421c').html('<i class="fas fa-exclamation"></i>');

            } else {
                $('.icon-box').css('background', '#82ce34').html('<i class="fas fa-check"></i>');
            }
        });

    });
</script>
<!-- End of Main Content section -->

