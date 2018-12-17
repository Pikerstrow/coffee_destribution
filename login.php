<?php
ob_start();
include_once 'includes/init.php';

// Checking if user is looged in and his role. If he is - redirect him in admin or clien panel
if (is_logged_in()) {
   if ($_SESSION['role'] === 'admin')
      header('Location: ./admin_panel');
   else
      header('Location: ./client_panel');
}

// Authorization
if (isset($_POST['login_subm'])) {

   $a_data = $a_errors = [];

   // Reciving data from the form
   $a_data = mf_get_data(['login', 'password'], 'post');

   // Clearing data from potential danger
   foreach ($a_data as $key => $value) {
      $a_data[$key] = mysqli_real_escape_string($link, strip_tags($value));
   }

   //Check if entered client/admin login exists in db and redirect client/admin to his panel
   if (client_login_exists($a_data['login'])) {
      if (!is_bool(login_client($a_data['login'], $a_data['password']))) {
         $a_error['password'] = login_client($a_data['login'], $a_data['password']);
      }
   } else if (admin_login_exists($a_data['login'])) {
      if (!is_bool(login_admin($a_data['login'], $a_data['password']))) {
         $a_error['password'] = login_admin($a_data['login'], $a_data['password']);
      }
   } else {
      $a_error['login'] = 'Користувач із введеним логіном не зареєстрований в системі!';
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
<div class="container container-main">
   <div class="row">
      <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 ">
         <div class="panel panel-default login-box">
            <div class="panel-body">
               <div class="text-center">
                  <h3 class="panel-h3"><i class="fas fa-key fa-3x"></i></h3>
                  <h3 class="text-center h3-login">Авторизуйтеся, будь ласка</h3>
                  <div class="panel-body" style="padding-top:0;">

                     <form id="login-form" role="form" autocomplete="off" class="form" method="post" action="">

                        <div class="form-group text-left">
                           <?= '<br><span>' . ((isset($a_error['login'])) ? '<span class="error-span error-span-auth"><b>Помилка: </b>' . $a_error['login'] . '</span></span>' : '') ?>
                           <?= '<span>' . ((isset($a_data['login'])) ? '<span class="ok-span"></span></span>' : '') ?>
                           <div class="input-group input-group-register">
                              <span class="input-group-addon addon-login"><i
                                    class="glyphicon glyphicon-user color-blue addon-register"></i></span>
                              <input name="login" type="text" class="form-control reg-page input-register"
                                     value="<?php echo(isset($a_data['login']) ? $a_data['login'] : ''); ?>"
                                     placeholder="Введіть логін">
                           </div>
                        </div>

                        <div class="form-group text-left">
                           <?= '<br><span>' . ((isset($a_error['password'])) ? '<span class="error-span error-span-auth"><b>Помилка: </b>' . $a_error['password'] . '</span></span>' : '') ?>
                           <?= '<span>' . ((isset($a_data['password'])) ? '<span class="ok-span"></span></span>' : '') ?>
                           <div class="input-group input-group-register">
                              <span class="input-group-addon addon-login"><i
                                    class="glyphicon glyphicon-lock color-blue addon-register"></i></span>
                              <input name="password" type="password" class="form-control reg-page input-register"
                                     placeholder="Введіть пароль">
                           </div>
                        </div>

                        <div class="form-group">
                           <input name="login_subm" class="register-submit" value="Увійти!" type="submit">
                        </div>

                        <?php if (isset($a_error['password'])): ?>
                           <div class="form-group">
                              <a href="forgot.php?forgot=<?php echo uniqid(true) ?>">Забули пароль?</a>
                           </div>
                        <?php endif ?>
                     </form>

                  </div><!-- Body-->
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script src="js/login_box_alignment.js"></script>
<script src="js/preloader.js"></script>
</body>
</html>