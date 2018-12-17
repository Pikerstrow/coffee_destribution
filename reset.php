<?php
ob_start();
include_once 'includes/init.php';

// Redirect user on main page if GET params aren't set.
if (!isset($_GET['email']) and !isset($_GET['token'])) {
   header('Location: ./index.php');
}

// Receiving data from GET and cleaning it
$token = mysqli_real_escape_string($link, strip_tags(mf_get_string('token', 'get')));
$email = mysqli_real_escape_string($link, strip_tags(mf_get_string('email', 'get')));
$admin = mysqli_real_escape_string($link, strip_tags(mf_get_string('admin', 'get')));
$client = mysqli_real_escape_string($link, strip_tags(mf_get_string('client', 'get')));

// For admin
if ($admin === 'true') {

   // Selecting admin data from DB
   $query = "SELECT admin_login, admin_email, admin_token FROM my_coffee_admins WHERE admin_token = ?";

   if ($stmt = mysqli_prepare($link, $query)) {
      mysqli_stmt_bind_param($stmt, "s", $token);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $admin_login, $admin_email, $admin_token);
      mysqli_stmt_fetch($stmt);
      mysqli_stmt_close($stmt);

      if (isset($_POST['new_pass_subm'])) {
         // Reciving data from the form
         $data = mf_get_data(['password', 'password_confirm'], 'post');

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

         if ($a_error == []) {
            // Hashing new password
            $new_password = md5($a_data['password'] . SALT);

            // Updating data in db
            if ($stmt = mysqli_prepare($link, "UPDATE my_coffee_admins SET admin_token = '', admin_password = '{$new_password}' WHERE admin_email = ?")) {
               mysqli_stmt_bind_param($stmt, "s", $email);
               mysqli_stmt_execute($stmt);

               if (mysqli_stmt_affected_rows($stmt) >= 1) {
                  header('Location:login.php');
               }
               mysqli_stmt_close($stmt);
            } else {
               $bd_con_error = "Помилка з'єднання із базою даних!";
            }
         }
      }
   } else {
      $bd_con_error = "Помилка з'єднання із базою даних!";
   }

} // End for admin

// For client
if ($client === 'true') {

   // Selecting admin data from DB
   $query = "SELECT client_login, client_email, client_token FROM my_coffee_clients WHERE client_token = ?";

   if ($stmt = mysqli_prepare($link, $query)) {
      mysqli_stmt_bind_param($stmt, "s", $token);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $client_login, $client_email, $client_token);
      mysqli_stmt_fetch($stmt);
      mysqli_stmt_close($stmt);

      if (isset($_POST['new_pass_subm'])) {
         // Reciving data from the form
         $data = mf_get_data(['password', 'password_confirm'], 'post');

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

         if ($a_error == []) {
            // Hashing new password
            $new_password = md5($a_data['password'] . SALT);

            // Updating data in db
            if ($stmt = mysqli_prepare($link, "UPDATE my_coffee_clients SET client_token = '', client_password = '{$new_password}' WHERE client_email = ?")) {
               mysqli_stmt_bind_param($stmt, "s", $email);
               mysqli_stmt_execute($stmt);

               if (mysqli_stmt_affected_rows($stmt) >= 1) {
                  header('Location:login.php');
               }
               mysqli_stmt_close($stmt);
            } else {
               $bd_con_error = "Помилка з'єднання із базою даних!";
            }
         }
      }
   } else {
      $bd_con_error = "Помилка з'єднання із базою даних!";
   }

} // End for admin
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
<div class="container container-main">
   <?php if (isset($bd_con_error)): ?>
      <h2 style="color:red"><?php $bd_con_error; ?></h2>
   <?php endif ?>
   <div class="row">
      <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 ">
         <div class="panel panel-default login-box">
            <div class="panel-body">
               <div class="text-center">
                  <h3 class="panel-h3"><i class="fas fa-unlock-alt fa-3x"></i></h3>
                  <h3 class="text-center h3-login">Відновлення паролю</h3>
                  <div class="panel-body" style="padding-top:0;">

                     <form id="new-password-form" role="form" autocomplete="off" class="form" method="post" action="">

                        <div class="form-group text-left">
                           <?= '<br><span>' . ((isset($a_error['password'])) ? '<span class="error-span error-span-auth"><b>Помилка: </b>' . $a_error['password'] . '</span></span>' : '') ?>
                           <?= '<span>' . ((isset($a_data['password'])) ? '<span class="ok-span"></span></span>' : '') ?>
                           <div class="input-group input-group-register">
                              <span class="input-group-addon addon-register"><i class="fas fa-key"></i></span>
                              <input type="password" class="form-control reg-page input-register" id="password"
                                     name="password" required='required' maxlength="32"
                                     placeholder="Введіть новий пароль">
                           </div>
                        </div>

                        <div class="form-group text-left">
                           <?= '<br><span>' . ((isset($a_error['password_confirm'])) ? '<span class="error-span error-span-auth"><b>Помилка: </b>' . $a_error['password_confirm'] . '</span></span>' : '') ?>
                           <?= '<span>' . ((isset($a_data['password_confirm'])) ? '<span class="ok-span"></span></span>' : '') ?>
                           <div class="input-group input-group-register">
                              <span class="input-group-addon addon-register"><i class="fas fa-key"></i></span>
                              <input type="password" class="form-control reg-page input-register" id="password_confirm"
                                     name="password_confirm" required='required' maxlength="32"
                                     placeholder="Пдтвердіть пароль">
                           </div>
                        </div>

                        <div class="form-group">
                           <input name="new_pass_subm" class="register-submit" value="Готово!" type="submit">
                        </div>

                     </form>

                  </div><!-- Body-->
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script src="js/login_box_alignment.js"></script>
</body>
</html>