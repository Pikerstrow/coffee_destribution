<?php
ob_start();

require_once 'init.php';
require_once 'db.php';

if (isset($_POST['message_sent'])){

   // Reciving data from the form
   $data = mf_get_data(['name', 'ph_number', 'email', 'message'], 'post');

   // Validating received data
   $form_data = form_validate($data);

   // Reciving data after validation
   $a_data = (isset($form_data['data'])) ? $form_data['data'] : [];
   $a_errors = (isset($form_data['errors'])) ? $form_data['errors'] : [];

   if($a_errors != []){
      echo json_encode(["errors" => $a_errors], JSON_UNESCAPED_UNICODE);
   } else {
      if (!is_bool(insert_to_db($a_data, 'my_coffee_messages', 'message_'))) {
         echo json_encode(["create_error" => "Упс... виникла помилка! Спробуйте будь ласка ще раз пізніше."], JSON_UNESCAPED_UNICODE);
      } else {
         $to = ADMIN_EMAIL;
         $subject = 'Нове повідомлення від відвідувача сайту';

         $message = "
            <html>
                <head>
                    <title>Нове повідомлення</title>
                </head>
                <body>
                    <div class='container'>
                        <p>У вас нове повідомлення від відвідувача сайту!</b></p>
                        <p>Переглянути його можна в особистому кабінеті перейшовши за посиланням:</p>
                        <a href='coffee.web-ol-mi.pp.ua/admin_panel' class='btn btn-primary'>Перейти в кабінет</a>
                    </div>
                </body>
            </html>";
         $headers = 'From: admin@kostacoffee.com.ua' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=utf-8';

         mail($to, $subject, $message, $headers);

         $successDiv = <<<SUCCESS
<div class="success-message pt-2 pb-2 text-center">
   <h2>Дякуємо!!!</h2>
   <h4>Ваше повідомелння було успішно відправлене!</h4>
   <i class="far fa-smile fa-7x mt-3"></i>
   <div class="col-12 mt-3">
      Ми зв'яжемося з Вами найближчим часом!
   </div>
</div>
SUCCESS;
         echo $successDiv;
      }
   }


}

