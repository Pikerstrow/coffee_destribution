<?php
ob_start();
include_once 'includes/init.php';

if (!isset($_GET['new_user']) and !(isset($_GET['token']))) {
   header('Location:index.php');
} else {
   if (is_string($_GET['new_user']) and is_string($_GET['token'])) {

      $new_user_login = trim(strip_tags($_GET['new_user']));
      $token = trim(strip_tags($_GET['token']));

      if ($token != session_id())
         exit(header('Location:index.php'));
   } else {
      exit(header('Location:index.php'));
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

<div class="container">
   <div class="row">
      <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 ">
         <div class="panel panel-default login-box">
            <div class="panel-body">
               <div class="text-center">

                  <h3 class="panel-h3"><i class="far fa-smile fa-4x"></i></h3>
                  <h4>Дякуємо за реєстрацію!</h4>
                  <br>
                  <p class="text-center" style="font-size: 15px">
                     Ви зареєструвалися в системі як <b><?php echo(isset($new_user_login) ? $new_user_login : ''); ?>
                        !</b>
                  </p>
                  <p class="text-center" style="font-size: 15px">
                     Для того, щоб мати змогу користуватися особистим кабінетом,
                     Ваша реєстрація повинна бути підтверджена адміністратором!
                     Як тільки це буде зроблено - Ви отримаєте відповідне повідомлення на
                     адресу електронної пошти, яка вказана при реєстрації.
                  </p>
                  <br>
                  <p class="text-center">
                     <a class="btn btn-sm btn-primary" href="index.php">Повернутися на сайт</a>
                  </p>
                  <div class="panel-body">
                  </div><!-- Body-->

               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
    $(document).ready(function () {
        var deviceScreenHeight = $(window).height();
        var loginBoxHeight = $('div.login-box').height();

        $('div.login-box').css('margin-top', function () {
            var marginTop = (deviceScreenHeight / 2) - (loginBoxHeight / 2);
            return marginTop;
        });
        $('div.login-box').css('margin-bottom', function () {
            var marginTop = (deviceScreenHeight / 2) - (loginBoxHeight / 2);
            return marginTop;
        });
    });
</script>
</body>
</html>