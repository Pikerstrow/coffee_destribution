<!-- Header -->
<?php include_once "includes_client/client_header.php"; ?>
<!-- End of header -->

   <?php
      if(isset($_POST['send_submit'])){
         
         $a_data = $a_error = [];
         
         // Reciving data from the form
         $a_data = mf_get_data(['machine_title', 'machine_problem'], 'post');
         
         // Cleaning data
         foreach($a_data as $key => $value){
            $a_data[$key] = mysqli_real_escape_string($link, strip_tags($value));
         } 
         
         // Validating data
         // Machine_title
         // 1. Check if field wasn't empty
         if ($a_data['machine_title'] == '' or empty($a_data['machine_title'])) {
            $a_error['machine_title'] = "Поле обов'язкове до заповнення!";
         }
         // 2. Check for max length
         if (mb_strlen($a_data['machine_title']) > 255) {
            $a_error['machine_title'] = "Перевищено допустиму кількість символів!";
         }
         // 3. Check if such coffee machine exists
         if(!coffee_machine_exists($a_data['machine_title'])){
            $a_error['machine_title'] = "Переданої кавоварки не існує!";
         }
                  
         //Problem
         if(!is_bool(description($a_data['machine_problem']))){
            $a_error['machine_problem'] = description($a_data['machine_problem']);
         }                  

         if ($a_error == []) {
            $client_id = find_client_by_login($_SESSION['client']);
            $a_data['client_id'] = $client_id;
            
            // Sending data to DB
            if (!is_bool(insert_to_db($a_data, 'my_coffee_breakdown_notifications', 'coffee_'))) {
                $bd_con_error = insert_to_db($a_data, 'my_coffee_breakdown_notifications', 'coffee_');
            } else {
                set_success('Ваше поідомлення успішно відправлене!');

                // Sending email to admin about new breakdown notification
                $to = ADMIN_EMAIL;
                $subject = 'Нове повідомлення про несправність!';

                $message = " 
                <html> 
                    <head> 
                        <title>Нове Повідомлення про несправність!</title> 
                    </head> 
                    <body> 
                        <div class='container'>
                            <p>Вам надійшло нове повідомлення про несправність від <b> {$_SESSION['client']}!</b></p>
                            <p>Переглянути текст повідомлення можна в особистому кабінеті.</p>
                            <a href='coffee.web-ol-mi.pp.ua/admin_panel' class='btn btn-primary'>Перейти в кабінет</a>
                        </div>                    
                    </body> 
                </html>";
                $headers  = 'From: admin@kostacoffee.com.ua' . "\r\n" .
                    'MIME-Version: 1.0' . "\r\n" .
                    'Content-type: text/html; charset=utf-8';

                mail($to, $subject, $message, $headers );


                unset($a_data);
                header('Location:index.php');
            }            
         }         
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
                <h2 class="h2-panel">
                   <span>Повідомити про несправність кавоварки</span>
                </h2>
                <hr>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <?php if(isset($bd_con_error)): ?>
                            <h2 style="color:red"><?php $bd_con_error; ?></h2>
                        <?php endif ?>

                            <form action=""  method="POST" role="form" name="breakdown_notification">
                                <div class="form-group">
                                    <label style="font-size: 14px;" for="machine_title">Кавоварка <span class="question" data-descr="Обов'зкове поле! Виберіть назву кавоварки із списку">?</span></label>
                                    <?= '<br><span>' . ( ( isset($a_error['machine_title']) ) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['machine_title'] . '</span></span>' : '' ) ?>
                                    <?= '<span>' . ( ( isset($a_data['machine_title']) ) ? '<span class="ok-span"></span></span>' : '') ?>
                                    <div class="input-group input-group-register">
                               <span class="input-group-addon my-addon addon-register">
                                  <span class="icon-coffee_machine"></span>
                               </span>
                                        <select class="form-control reg-page input-register" name="machine_title">
                                            <option value="" disabled selected>Виберіть назву зі списку нижче</option>
                                            <?php select_all_coffee_machines(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label style="font-size: 14px;" for="machine_problem">Короткий опис поломки <span class="question" data-descr="Обов'язкове поле! В даному полі необхідно коротко описати поломку (вказати, що сталося із кавоваркою).">?</span></label>
                                    <?= '<br><span>' . ( ( isset($a_error['machine_problem']) ) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['machine_problem'] . '</span></span>' : '' ) ?>
                                    <?= '<span>' . ( ( isset($a_data['machine_problem']) ) ? '<span class="ok-span"></span></span>' : '') ?>
                                    <div class="input-group input-group-register">
                               <span class="input-group-addon my-addon addon-register">
                                  <span class="glyphicon glyphicon-wrench"></span>
                               </span>
                                        <textarea name="machine_problem" id="editor_textarea" class="form-control reg-page input-register" rows="3"><?= ( isset($a_data['machine_problem']) ? htmlspecialchars($a_data['machine_problem']) : '' ) ?></textarea>
                                    </div>
                                </div>
                                <input style="margin-top:10px;" type="submit" class="register-submit" name='send_submit' id="client_registration" value="Відправити!">
                            </form>

                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <br>
                        <div style="margin-top:5px;" class="panel panel-primary panel-order-primary">
                            <div class="panel-heading text-center panel-heading-order">
                                <h3 class="panel-title">
                                    <i class="fas fa-info-circle"></i> Інформативно:
                                </h3>
                            </div>
                            <div class="panel-body panel-body-order">
                                <p>
                                    Для відправки повідомлення, Вам необхідно вибрати модель встановленої у Вас кавоварки із наданого списку.
                                </p>
                                <p>Після чого, опишіть характер поломки у відповіному полі.</p>
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
