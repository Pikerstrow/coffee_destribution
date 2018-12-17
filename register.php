<?php
ob_start();
include_once 'includes/init.php';

if (isset($_POST['client_registration'])) {

   // Reciving data from the form
   $data = mf_get_data(['name', 'establishment', 'person_name', 'phone_number', 'email', 'login', 'password', 'password_confirm', 'client_antibot'], 'post');

   // Validating received data
   $form_data = form_validate($data);

   // Reciving data after validation
   $a_data = (isset($form_data['data'])) ? $form_data['data'] : [];
   $a_error = (isset($form_data['errors'])) ? $form_data['errors'] : [];

   // Additional validation for matching password and password confirm fields
   if (isset($a_data['password']) and isset($a_data['password_confirm'])) {
      if (!is_bool(password_confirm($a_data['password'], $a_data['password_confirm']))) {
         $a_error['password_confirm'] = password_confirm($a_data['password'], $a_data['password_confirm']);
      }
   } else {
      $a_error['password_confirm'] = 'Попереднє поле - "пароль" не відповідає мінімальним вимогам! Необхідно виправити помилку там.';
   }

   //Anti-bots validation
   if ('' != $a_data['client_antibot'])
      $a_error['client_antibot'] = 'Бот!!!';


   // Extra validation for existing email, phone number and login in db
   if (isset($a_data['email'])) {
      if (client_email_exists($a_data['email']) or admin_email_exists($a_data['email'])) {
         $a_error['email'] = 'Введена email-адреса вже зареєстрована в системі!';
      }
   }
   if (isset($a_data['login'])) {
      if (client_login_exists($a_data['login']) or admin_login_exists($a_data['login'])) {
         $a_error['login'] = 'Введений логін вже зареєстрований в системі!';
      }
   }
   if (isset($a_data['phone_number'])) {
      if (phone_number_exists($a_data['phone_number'])) {
         $a_error['phone_number'] = 'Введений номер телефону вже зареєстрований в системі!';
      }
   }


   if ([] == $a_error) {
      $token = session_id();

      //Removing 2 last elem from data array, because it is password-confirm field and antibot field wich is not neccessary id db
      $a_data = array_slice($a_data, 0, (count($a_data) - 2));

      //Hashing the user password
      $a_data['password'] = md5($a_data['password'] . SALT);

      //Sending data to db
      if (!is_bool(insert_to_db($a_data, 'my_coffee_clients', 'client_'))) {
         $a_error['db_connection'] = insert_to_db($a_data, 'my_coffee_clients', 'client_');
      } else {

         // Sending email notification to admin about new client
         $to = ADMIN_EMAIL;
         $subject = 'Новий клієнт';

         $message = " 
            <html> 
                <head> 
                    <title>Новий клієнт</title> 
                </head> 
                <body> 
                    <div class='container'>
                        <p>На вашому сайті зареєструвався новий клієнт!</b></p>
                        <p>Перейдіть в особистий кабінет і підтвердіть або скасуйте реєстрацію.</p>
                        <a href='coffee.web-ol-mi.pp.ua/admin_panel' class='btn btn-primary'>Перейти в кабінет</a>
                    </div>                    
                </body> 
            </html>";
         $headers = 'From: admin@kostacoffee.ua' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=utf-8';

         mail($to, $subject, $message, $headers);

         header("Location:welcome.php?new_user={$a_data['login']}&token={$token}");
         unset($a_data);
      }
   } else {
      $form_mistakes_notification = "Форма містить помилки!";
   }

}

?>﻿
<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <title>KostaCoffee - кава та кавоварки в м. Львів та області!</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/png" href="../images/favicon.png"/>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
         integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
   <link rel="stylesheet" href="css/styles.css">


   <style>
      body {
         background-image: url(../images/backgrounds/coffee_register_background.jpg);
         background-position: center center;
         background-repeat: no-repeat;
         background-attachment: fixed;
         background-size: cover;
         height: 100%;
         overflow: auto;
      }
   </style>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
   <script src="js/main.js"></script>
</head>
<body>
<!-- PreLoader -->
<div class="preloader">
   <div class="container">
      <div class="row">
         <div class="loader">
            <img width="70" src="images/logos/Logo_cup_white.png">
            KostaCoffee
            <span></span>
            <span></span>
            <span></span>
            <span></span>
         </div>
      </div>
   </div>
</div>
<?php if (!isset($_SESSION['auth'])): ?>
   <div class="container-registration">
      <div class="container">
         <div class="row">
            <form action="register.php" method="POST" role="form" name="registration_form">
               <div style="margin-bottom: 25px; padding-right: 15px; padding-left: 15px;" class="row text-center">
                  <h2 class="h2-register">ФОРМА РЕЄСТРАЦІЇ</h2>
                  <?php if (!isset($form_mistakes_notification)): ?>
                     <div class="helper">
                        <p style="color: #eee82f; font-size: 17px;">Увага! Всі поля є обв'язковими до
                           заповнення!</p>
                     </div>
                  <?php else: ?>
                     <div class="helper">
                        <p class="bg-danger form_mistakes_notifications"><?php echo $form_mistakes_notification; ?></p>
                     </div>
                  <? endif; ?>
                  <?php echo(isset($a_error['db_connection']) ? '<h3 style="color: red">Помилка з\'єднання із Базою Даних. Повідомте про дану помилку власника ресурсу.</h3>' : ''); ?>
               </div>
               <div class="row row-register">
                  <div class="col-sm-6 col-xs-12 registration-form">
                     <div class="form-group form-group-register">
                        <label for="name" class="label-register">НАЗВА ЮРИДИЧНОЇ ОСОБИ
                           <span class="question" data-descr="В даному полі необхідно ввести скорочену назву юридичної особи. Наприклад, ТОВ 'Назва' для
                        юридичних осіб або ФОП Прізвище І.П. - для фізичних осіб-підприємців. Приймаються значення введені лише кирилицею.">?
                  </span>
                        </label>
                        <?= '<br><span>' . ((isset($a_error['name'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['name'] . '</span></span>' : '') ?>
                        <!-- В даному місці та нижче, тег <span class="ok-span"> добавлений виключно для можливості застосувати
                        bootstrap класи валідації форми, так як валідації проводиться php скриптом. Даний тег із класом
                        "ok-span" використовується для навігації по DOM js-скриптом, який написаний в файлі main.js -->
                        <?= '<span>' . ((isset($a_data['name'])) ? '<span class="ok-span"></span></span>' : '') ?>
                        <div class="input-group input-group-register">
                  <span class="input-group-addon addon-register">
                    <span class="glyphicon glyphicon-briefcase"></span>
                  </span>
                           <input type="text" class="form-control reg-page input-register" id="client_name"
                                  name="name" placeholder="Юридична особа"
                                  required='required' maxlength="50"
                                  value="<?= (isset($a_data['name']) ? htmlspecialchars($a_data['name']) : '') ?>">
                        </div>
                     </div>
                  </div>

                  <div class="col-sm-6 col-xs-12 registration-form">
                     <div class="form-group form-group-register">
                        <label for="establishment" class="label-register">НАЗВА ЗАКЛАДУ
                           <span class="question" data-descr="В даному полі необхідно ввести назву закладу.">?
                  </span>
                        </label>
                        <?= '<br><span>' . ((isset($a_error['establishment'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['establishment'] . '</span></span>' : '') ?>
                        <?= '<span>' . ((isset($a_data['establishment'])) ? '<span class="ok-span"></span></span>' : '') ?>
                        <div class="input-group input-group-register">
                  <span class="input-group-addon addon-register">
                    <span class="glyphicon glyphicon-cutlery"></span>
                  </span>
                           <input type="text" class="form-control reg-page input-register"
                                  id="client_establishment" name="establishment"
                                  placeholder="Заклад" required='required' maxlength="50"
                                  value="<?= (isset($a_data['establishment']) ? htmlspecialchars($a_data['establishment']) : '') ?>">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row row-register">
                  <div class="col-sm-6 col-xs-12 registration-form">
                     <div class="form-group form-group-register">
                        <label for="person_name" class="label-register">КОНТАКТНА ОСОБА
                           <span class="question" data-descr="В даному полі необхідно ввести П.І.Б. контактної особи. Прізивще, ім'я та по-батькові повинні
                        бути записане в повному форматі. Нариклад: Прізвище Ім'я Побатькові">?
                  </span>
                        </label>
                        <?= '<br><span>' . ((isset($a_error['person_name'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['person_name'] . '</span></span>' : '') ?>
                        <?= '<span>' . ((isset($a_data['person_name'])) ? '<span class="ok-span"></span></span>' : '') ?>
                        <div class="input-group input-group-register">
                  <span class="input-group-addon addon-register">
                    <span class="glyphicon glyphicon-user"></span>
                  </span>
                           <input type="text" class="form-control form-for-name reg-page input-register"
                                  id="person_name" name="person_name" placeholder="Прізвище Ім'я Побатькові"
                                  required='required' maxlength="100"
                                  value="<?= (isset($a_data['person_name']) ? htmlspecialchars($a_data['person_name']) : '') ?>">
                        </div>
                     </div>
                  </div>

                  <div class="col-sm-6 col-xs-12 registration-form">
                     <div class="form-group form-group-register">
                        <label for="phone_number" class="label-register">МОБІЛЬНИЙ ТЕЛЕФОН
                           <span class="question"
                                 data-descr="В даному полі необхідно ввести номер телефону контактної особи обов'язково у форматі: +38(097)111-11-11.">?
                  </span>
                        </label>
                        <?= '<br><span>' . ((isset($a_error['phone_number'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['phone_number'] . '</span></span>' : '') ?>
                        <?= '<span>' . ((isset($a_data['phone_number'])) ? '<span class="ok-span"></span></span>' : '') ?>
                        <div class="input-group input-group-register">
                  <span class="input-group-addon addon-register">
                    <span class="glyphicon glyphicon-phone"></span>
                  </span>
                           <input type="text" class="form-control form_for_phone reg-page input-register"
                                  id="phone_number" name="phone_number" placeholder="+38(XXX)XXX-XX-XX"
                                  required='required' maxlength="17"
                                  value="<?= (isset($a_data['phone_number']) ? htmlspecialchars($a_data['phone_number']) : '') ?>">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row row-register">
                  <div class="col-sm-6 col-xs-12 registration-form">
                     <div class="form-group form-group-register">
                        <label for="email" class="label-register">EMAIL
                           <span class="question"
                                 data-descr="В даному полі необхідно ввести електронну адресу закладу або контактної особи.">?
                  </span>
                        </label>
                        <?= '<br><span>' . ((isset($a_error['email'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['email'] . '</span></span>' : '') ?>
                        <?= '<span>' . ((isset($a_data['email'])) ? '<span class="ok-span"></span></span>' : '') ?>
                        <div class="input-group input-group-register">
                           <span class="input-group-addon addon-register">@</span>
                           <input type="email" class="form-control reg-page input-register" name="email"
                                  id="client_email" placeholder="email" maxlength="255"
                                  value="<?= (isset($a_data['email']) ? htmlspecialchars($a_data['email']) : '') ?>">
                        </div>
                     </div>
                  </div>

                  <div class="col-sm-6 col-xs-12 registration-form">
                     <div class="form-group form-group-register">
                        <label for="login" class="label-register"> ЛОГІН
                           <span class="question" data-descr="В даному полі необхідно ввести логін, за допомогою якого буде здійснюватися вхід у
                        власний кабінет. Логін повинен складатися із літер латинського алфавіту (a-z) та може
                        містити цифри. Максимальна довжина логіна - 20 символів; мінімальна - 5;">?
                  </span>
                        </label>
                        <?= '<br><span>' . ((isset($a_error['login'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['login'] . '</span></span>' : '') ?>
                        <?= '<span>' . ((isset($a_data['login'])) ? '<span class="ok-span"></span></span>' : '') ?>
                        <div class="input-group input-group-register">
                  <span class="input-group-addon addon-register">
                    <span class="glyphicon glyphicon-pencil"></span>
                  </span>
                           <input type="text" class="form-control reg-page input-register" id="client_login"
                                  name="login" placeholder="login" required='required' maxlength="20"
                                  value="<?= (isset($a_data['login']) ? htmlspecialchars($a_data['login']) : '') ?>">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row row-register">
                  <div class="col-sm-6 col-xs-12 registration-form">
                     <div class="form-group form-group-register">
                        <label for="password" class="label-register"> ПАРОЛЬ
                           <span class="question" data-descr="В даному полі необхідно ввести пароль, за допомогою якого буде здійснюватися вхід у
                        власний кабінет. Пароль повинен складатися із літер латинського алфавіту (a-z) та цифер.
                        Максимальна довжина паролю - 32 символи; мінімальна - 6;">?
                  </span>
                        </label>
                        <?= '<br><span>' . ((isset($a_error['password'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['password'] . '</span></span>' : '') ?>
                        <?= '<span>' . ((isset($a_data['password'])) ? '<span class="ok-span"></span></span>' : '') ?>
                        <div class="input-group input-group-register">
                           <span class="input-group-addon addon-register"><i class="fas fa-key"></i></span>
                           <input type="password" class="form-control reg-page input-register" id="password"
                                  name="password" required='required' maxlength="32">
                        </div>
                     </div>
                  </div>

                  <div class="col-sm-6 col-xs-12 registration-form">
                     <div class="form-group form-group-register">
                        <label for="password_confirm" class="label-register">ПІДТВЕРДІТЬ ПАРОЛЬ
                           <span class="question" data-descr="Поле для підтвердження паролю.">?
                  </span>
                        </label>
                        <?= '<br><span>' . ((isset($a_error['password_confirm'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['password_confirm'] . '</span></span>' : '') ?>
                        <?= '<span>' . ((isset($a_data['password_confirm'])) ? '<span class="ok-span"></span></span>' : '') ?>
                        <div class="input-group input-group-register">
                           <span class="input-group-addon addon-register"><i class="fas fa-key"></i></span>
                           <input type="password" class="form-control reg-page input-register"
                                  id="password_confirm" name="password_confirm" required='required'
                                  maxlength="32">
                        </div>
                     </div>
                  </div>
               </div>
         </div>
         <div class="row ">
            <div class="col-sm-6 col-xs-12 clearfix" style="margin-top: 10px;">
               <input type="text" class="form-control reg-page" style="display:none" name="client_antibot">
            </div>
         </div>

         <div class="row">
            <div class="col-sm-2 col-sm-offset-5 col-xs-8 col-xs-offset-2" style="margin-bottom: 10px;">
               <div class="form-group form-group-register">
                  <input type="submit" class="register-submit" name='client_registration' id="client_registration"
                         value="ГОТОВО!">
               </div>
            </div>
         </div>
         </form>
      </div>
   </div>
   </div>
<?php else: ?>


   <div class="container">
      <div class="row">
         <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 ">
            <div class="panel panel-default login-box">
               <div class="panel-body">
                  <div class="text-center">

                     <h3 class="panel-h3"><i class="far fa-frown fa-4x"></i></h3>
                     <p class="text-center" style="font-size: 16px">
                        На даний момент Ви авторизовані в системі як
                        <b><?php echo(isset($_SESSION['client']) ? $_SESSION['client'] : $_SESSION['admin']); ?>
                           !</b>
                     </p>
                     <p class="text-center" style="font-size: 16px">
                        Для того, щоб зареєструватися як новий користувач, Вам необхідно
                        <a href="<?php echo(isset($_SESSION['admin']) ? 'admin_panel/index.php?logout=true' : 'client_panel/index.php?logout=true'); ?>">вийти</a>
                        із поточного облікового запису.
                     </p>
                     <br>
                     <p class="text-center">
                        <a class="btn btn-sm btn-primary" href="index.php">Повернутися на головну сторінку</a>
                     </p>
                     <div class="panel-body">
                     </div><!-- Body-->

                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>


<?php endif ?>
<script src="js/login_box_alignment.js"></script>
<script src="js/preloader.js"></script>
</body>
</html>