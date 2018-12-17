<?php
ob_start();
include_once 'includes/init.php';

// Checking if isset GET parametrer
if (!isset($_GET['forgot'])) {
    header('Location: ./index.php');
}

// Checking if user is looged in and his role. If he is - redirect him in admin or clien panel
if (is_logged_in()) {
   if ($_SESSION['role'] === 'admin')
      header('Location: ./admin_panel');
   else
      header('Location: ./client_panel');
}

// Authorization
if (isset($_POST['pass-reset-subm'])) {
   
   $email_from_the_form = mf_get_string('email', 'post');
   $clean_email = mysqli_real_escape_string($link, strip_tags($email_from_the_form));
   
   $length = 50;
   $token = bin2hex(openssl_random_pseudo_bytes($length));
   
   // For clients
   if(client_email_exists($clean_email)){
      
      $query = "UPDATE my_coffee_clients SET client_token = '{$token}' WHERE client_email = ?";    
      
      if ($stmt = mysqli_prepare($link, $query)) {
                  mysqli_stmt_bind_param($stmt, 's', $clean_email);
                  mysqli_stmt_execute($stmt);
                  mysqli_stmt_close($stmt);
         
         $to = $clean_email;
         $subject = 'Відновлення паролю.';
       
         $message = " 
            <html> 
                <head> 
                    <title>Відновлення паролю</title> 
                </head> 
                <body> 
                    <h3>Будь ласка, перейдіть на сторінку відновлення паролю за посиланням нижче:</h3>
                    <a href='https://kostacoffee.com.ua/reset.php?email={$clean_email}&token={$token}&client=true'>https://kostacoffee.com.ua/reset.php?email={$clean_email}&token={$token}</a> 
                </body> 
            </html>";    
         $headers  = 'From: admin@kostacoffee.com.ua' . "\r\n" .
                    'MIME-Version: 1.0' . "\r\n" .
                    'Content-type: text/html; charset=utf-8';
         
         if( mail($to, $subject, $message, $headers )) {
            $success_massage = "Подальші інструкції для відновлення паролю відправлені на Вашу електронну пошту.";            
         } else {
            $email_error = "Помилка! Повідомлення не відправлене.";
         }      
                  
      } else {
         $bd_con_error = "Помилка з'єднання із базою даних!";
      }           
   
   // For admins
   } else if (admin_email_exists($clean_email)) {  
      $query = "UPDATE my_coffee_admins SET admin_token = '{$token}' WHERE admin_email = ?";    
      
      if ($stmt = mysqli_prepare($link, $query)) {
                  mysqli_stmt_bind_param($stmt, 's', $clean_email);
                  mysqli_stmt_execute($stmt);
                  mysqli_stmt_close($stmt);
                  
         $to = $clean_email;
         $subject = 'Відновлення паролю.';
       
         $message = " 
            <html> 
                <head> 
                    <title>Відновлення паролю</title> 
                </head> 
                <body> 
                    <h3>Будь ласка, перейдіть на сторінку відновлення паролю за посиланням нижче:</h3>
                    <a href='https://kostacoffee.com.ua/reset.php?email={$clean_email}&token={$token}&admin=true'>https://kostacoffee.com.ua/reset.php?email={$clean_email}&token={$token}</a> 
                </body> 
            </html>";    
         $headers  = 'From: admin@kostacoffee.com.ua' . "\r\n" .
                    'MIME-Version: 1.0' . "\r\n" .
                    'Content-type: text/html; charset=utf-8';
         
         if( mail($to, $subject, $message, $headers )) {
            $success_massage = "Подальші інструкції для відновлення паролю відправлені на Вашу електронну пошту.";            
         } else {
            $email_error = "Помилка! Повідомлення не відправлене.";
         }                  
                  
      } else {
         $bd_con_error = "Помилка з'єднання із базою даних!";
      }   
    
   } else {
      $email_error = "Вказана email адреса не зареєстрована в системі!";
   }
   

   
}
?>﻿
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>TEST</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>	
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
      <script src= "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
      <script src= "js/main.js"></script>
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
      <link rel="stylesheet" href="css/styles.css">    
      <style>
         body {
            background-image: url(../images/backgrounds/coffee_register_background.jpg); 
            background-position: center center;
            background-repeat:  no-repeat;
            background-attachment: fixed;
            background-size:  cover;
            height: 100%;
            overflow: auto;
         }      
      </style>
   </head>
   <body>      
      <div class="container container-main">
            <?php if(isset($bd_con_error)): ?>
               <h2 style="color:red"><?php $bd_con_error; ?></h2>
            <?php endif ?>
            <div class="row">
               <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 ">
                  <div class="panel panel-default login-box">
                     <div class="panel-body">
                        <div class="text-center">
                           <?php if(isset($success_massage)): ?>
                           <h4 style="color: #3e6a00;"><?php echo $success_massage; ?></h4>
                           <?php endif ?>
                           <h3 class="panel-h3"><i class="fa fa-lock fa-3x"></i></h3>
                           <h3 class="text-center h3-login">Забули пароль?</h3>
                           <p>Ви можете відновити його на цій сторінці.</p>
                           <div class="panel-body" style="padding-top:0;">

                              <form id="pass-reset-form" role="form" autocomplete="off" class="form" method="post" action="">

                                 <div class="form-group text-left">
                                    <?= '<br><span>' . ( ( isset($email_error) ) ? '<span class="error-span error-span-auth"><b>Помилка: </b>' . $email_error . '</span></span>' : '' ) ?>                                    
                                    <div class="input-group input-group-register">
                                       <span class="input-group-addon addon-login"><i class="glyphicon glyphicon-user color-blue addon-register"></i></span>
                                       <input name="email" type="email" class="form-control reg-page input-register" 
                                              value="" placeholder="Ваша email адреса">
                                    </div>
                                 </div>
                                 
                                 <div class="form-group">
                                    <input name="pass-reset-subm" class="register-submit" value="Готово!" type="submit">
                                 </div>
                                 
                                 <?php if(isset($success_massage)): ?>
                                 <a class= "btn btn-primary btn-xs" href="index.php">Повернутися на сайт</a>
                                 <?php endif ?>
                                 
                              </form>

                           </div><!-- Body-->
                        </div>
                     </div>
                  </div>
               </div>
            </div>         
      </div>
      <script src= "js/login_box_alignment.js"></script>
   </body>
</html>
