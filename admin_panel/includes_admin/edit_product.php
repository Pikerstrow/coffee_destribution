<?php

/*CATCHING GET PARAMETER*/
if (isset($_GET['prod_id_edit']) and isset($_GET['token'])) {

   /*SELECT NECESSARY CATEGORY FROM DB FOR DISPLAYING IN INPUTS AS VALUE*/
   $prod_id = $_GET['prod_id_edit'];
   $token = $_GET['token'];

   /*PROTECTION*/
   if ($token != session_id()) {
      die('Переданий токен не вірний');
   }
   if (!product_exists($prod_id)) {
      die('Вибраного товару не існує');
   }

   /*SELECTING EDITED PRODUCT DATA FROM DB*/
   $query = "SELECT * FROM my_coffee_products WHERE product_id = ${prod_id}";
   $sel_prods = mysqli_query($link, $query);

   if (!$sel_prods) {
      die('Упс. Не вдалося вибрати дані із БД. Помилка запиту!');
   } else {
      while ($row = mysqli_fetch_assoc($sel_prods)) {
         $prod_id = $row['product_id'];
         $prod_title = $row['product_title'];
         $prod_image = $row['product_image'];
         $prod_descr = $row['product_description'];
         $prod_price = $row['product_price'];
         $prod_category_id = $row['product_category_id'];
      }
   }
}

/*EDITING PRODUCT*/
if (isset($_POST['edit_product_subm'])) {

   // Reciving data from the form
   $data = mf_get_data(['title', 'category_id', 'description', 'price'], 'post');

   // Validating received data
   $form_data = form_validate($data);

   $a_data = (isset($form_data['data'])) ? $form_data['data'] : [];
   $a_error = (isset($form_data['errors'])) ? $form_data['errors'] : [];

   // Receiving image from the form
   $a_data['image'] = mf_get_file('image');

   /*IF ADMIN DOESN"T CHANGES PHOTO OF THE PRODUCT*/
   if ('' === $a_data['image']['name'] or empty($a_data['image']['name'])) {
      $a_data['image'] = $prod_image;
   } else {
      /*IF ADMIN CHANGES PHOTO OF THE PRIDUCT*/
      if (isset($a_data['image']['name']) and !empty($a_data['image']['name'])) {
         if (!is_bool(image($a_data['image']))) {
            $a_error['image'] = image($a_data['image']);
         }
      }
   }

   if ([] === $a_error) {
      /* IF ADMIN SELECTED NEW PHOTO */
      if (isset($a_data['image']['name']) and !empty($a_data['image']['name'])) {
         /* Moving file to proper directory */
         $ext = mb_strtolower(pathinfo($a_data['image']['name'], PATHINFO_EXTENSION));
         $file_name = mb_substr(md5($a_data['image']['name'] . microtime()), 0, 10) . '.' . $ext;

         move_uploaded_file(
            $a_data['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/images/products/' . $file_name
         );
         // Delete previous image
         if ($prod_image != null and $prod_image != 'no_photo.png') {
            unlink('../images/products/' . $prod_image);
         }
         /* Inserting data to db */
         //1. removing last elem from data, because it is array (image)
         array_pop($a_data);
         //2. push image name (instead of deleted array) for adding in db
         $a_data['image'] = $file_name;
         // . inserting data
         if (!is_bool(update_in_db($a_data, 'my_coffee_products', 'product_id', $prod_id, 'product_'))) {
            $a_error['db_connection'] = update_in_db($a_data, 'my_coffee_products', 'product_id', $prod_id, 'product_');
         } else {
            set_success('Товар успішно відредаговано!');
            unset($a_data);
            header('Location:products.php');
         }
      } else {
         /* IF ADMIN DOESN'T CHANGE PHOTO */
         if (!is_bool(update_in_db($a_data, 'my_coffee_products', 'product_id', $prod_id, 'product_'))) {
            $a_error['db_connection'] = update_in_db($a_data, 'my_coffee_products', 'product_id', $prod_id, 'product_');
         } else {
            set_success('Товар успішно відредаговано!');
            unset($a_data);
            header('Location:products.php');
         }
      }
   }
}
?>

<h4 align="center" style="font-size:18px"> Редагувати товар</h4>
<hr>
<!-- Add Products -->
<div class='col-xs-12 col-md-6 col-md-offset-3'>
   <form action="" id="add_product_form" method="POST" class="form-horizontal" role="form" enctype="multipart/form-data">
      <div class="form-group">
         <label style="font-size: 14px;">Назва товару <span class="question" data-descr="Обов'язкове поле! В даному полі необхідно ввести назву товару,
                                                               яка буде відображатися в кабінеті Ваших клієнтів. Максимальна довжина назви 
                                                               товару - 50 символів">?</span>
         </label>
         <?= '<br><span>' . ((isset($a_error['title'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['title'] . '</span></span>' : '') ?>
         <?= '<span>' . ((isset($a_data['title'])) ? '<span class="ok-span"></span></span>' : '') ?>
         <div class="input-group input-group-register">
            <span class="input-group-addon my-addon addon-register"><i class="fab fa-gulp fa-lg"></i></span>
            <input type="text" name="title" id="product_name" class="form-control reg-page input-register"
                   value="<?= (isset($prod_title) ? htmlspecialchars($prod_title) : '') ?>"
                   required="required" maxlength="50">
         </div>
      </div>

      <div class="form-group">
         <label for="prod_category_id" style="font-size: 14px;">Категорія товару <span class="question"
                                                                                       data-descr="Обов'язкове поле! Виберіть категорію, до якої буде додано товар із випадаючого списку! Якщо Ви бачите, що якоїсь категорії не вистачає - тоді спочатку додайте її на відповідній сторінці.">?</span></label>
         <?= '<br><span>' . ((isset($a_error['category_id'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['category_id'] . '</span></span>' : '') ?>
         <?= '<span>' . ((isset($a_data['category_id'])) ? '<span class="ok-span"></span></span>' : '') ?>
         <div class="input-group input-group-register">
            <span class="input-group-addon my-addon addon-register"><i class="far fa-file-alt"></i></span>
            <select class="form-control reg-page input-register" name="category_id" id="category_id" required>
               <?php select_categories_for_prod_edit($prod_category_id); ?>
            </select>
         </div>
      </div>

      <div class="form-group">
         <label style="font-size: 14px;">Ціна товару <span class="question" data-descr="Обов'язкове поле! В даному полі необхідно ввести ціну товару. До вводу допускаються лише цифри.
                                                              Ціна з копійками вводится у форматі 125.25 (обов'язково через крапку!), 
                                                              де після крапки може бути одна або дві цифри. Без копійок - можна вводити
                                                              лише число без використання крапки(наприклад - 125)">?</span>
         </label>
         <?= '<br><span>' . ((isset($a_error['price'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['price'] . '</span></span>' : '') ?>
         <?= '<span>' . ((isset($a_data['price'])) ? '<span class="ok-span"></span></span>' : '') ?>
         <div class="input-group input-group-register">
            <span class="input-group-addon my-addon addon-register"><i class="fas fa-dollar-sign"></i></span>
            <input type="text" name="price" id="product_price" class="form-control reg-page input-register"
                   value="<?= (isset($prod_price) ? htmlspecialchars($prod_price) : '') ?>"
                   required="required">
         </div>
      </div>
      <div class="form-group">
         <h4>Поточне фото товару</h4>
         <img class='prod-img' width='200' style='margin-bottom:20px;'
              src='../images/products/<?php echo image_is_set($prod_image); ?>'>
         <br>
         <label style="font-size: 14px;">Фото товару <span class="question" data-descr="Обов'язкове поле! Виберіть одну фотографію на Вашому ПК,
                                                              яка відображає Ваш товар та буде відображене в кабінеті Ваших клієнтів. 
                                                              Максимально допустимий розмір зображення: 512 на 512 px. Допустимі розширення: 'png', 'jpeg', 'jpg'. 
                                                              Максимальний розмір - 2 МБ ">?</span>
         </label>
         <?= '<br><span>' . ((isset($a_error['image'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['image'] . '</span></span>' : '') ?>
         <?= '<span>' . ((isset($a_data['image'])) ? '<span class="ok-span"></span></span>' : '') ?>
         <div class="input-group input-group-register">
            <span class="input-group-addon my-addon addon-register"><i class="far fa-image"></i></span>
            <input type="file" name="image" id="product_photo"
                   class="form-control reg-page form-cont-moz input-register">
         </div>
      </div>

      <div class="form-group">
         <label style="font-size: 14px;">Опис товару <span class="question" data-descr="Обов'язкове поле! В даному полі необхідно коротко описати товар.
                                                              Опис товару також буде відображатися в кабінеті Ваших клієнтів. Максимальна довжина опису
                                                              товару - 1500 символів">?</span>
         </label>
         <input name="description" type="hidden" class="form-control">
         <?= '<br><span>' . ((isset($a_error['description'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['description'] . '</span></span>' : '') ?>
         <?= '<span>' . ((isset($a_data['description'])) ? '<span class="ok-span"></span></span>' : '') ?>
         <div id="q-container"><div id="quill-prod-descr"
              class=""><? echo(isset($prod_descr) ? $prod_descr : ''); ?>
         </div></div>
      </div>
      <hr>
      <div class="form-group">
         <button type="submit" class="register-submit" name="edit_product_subm">Редагувати товар</button>
      </div>
   </form>
</div>
