<?php
if (isset($_POST['add_product_subm'])) {


   // Reciving data from the form
   $data = mf_get_data(['title', 'category_id', 'description', 'price'], 'post');

   // Validating received data
   $form_data = form_validate($data);

   $a_data = (isset($form_data['data'])) ? $form_data['data'] : [];
   $a_error = (isset($form_data['errors'])) ? $form_data['errors'] : [];

   // Receiving image from the form
   $a_data['image'] = mf_get_file('image');

   // Image isn't neccessary, so we need to check if it was uploaded
   if (isset($a_data['image']['name']) and !empty($a_data['image']['name'])) {
      if (!is_bool(image($a_data['image']))) {
         $a_error['image'] = image($a_data['image']);
      }
   }

   if ([] == $a_error) {

      /* IF ADMIN SELECT PHOTO */
      if (isset($a_data['image']['name']) and !empty($a_data['image']['name'])) {
         /* Moving file to proper directory */
         $ext = mb_strtolower(pathinfo($a_data['image']['name'], PATHINFO_EXTENSION));
         $file_name = mb_substr(md5($a_data['image']['name'] . microtime()), 0, 10) . '.' . $ext;

         move_uploaded_file(
            $a_data['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/images/products/' . $file_name
         );
         /* Inserting data to db */
         //1. removing last elem from data, because it is array (image)
         array_pop($a_data);
         //2. push image name (instead of deleted array) for adding in db
         $a_data['image'] = $file_name;
         // . inserting data
         if (!is_bool(insert_to_db($a_data, 'my_coffee_products', 'product_'))) {
            $a_error['db_connection'] = insert_to_db($a_data, 'my_coffee_products', 'product_');
         } else {
            set_success('Товар успішно додано!');
            unset($a_data);
            header('Location:products.php?route=add_product');
         }
         /* IF ADMIN DOESN'T SELECT PHOTO */
      } else {
         // remove last value from the a_data array because it relates to image
         array_pop($a_data);
         // insert data to db
         if (!is_bool(insert_to_db($a_data, 'my_coffee_products', 'product_'))) {
            $a_error['db_connection'] = insert_to_db($a_data, 'my_coffee_products', 'product_');
         } else {
            set_success('Товар успішно додано!');
            unset($a_data);
            header('Location:products.php?route=add_product');
         }
      }
   }
}
?>
<h2 class="h2-panel">
   <span>Додати товар</span>
</h2>
<hr>
<!-- Add Products -->

<?php
$counter = 1;
$query = "SELECT * FROM my_coffee_prod_categories";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $cat_id, $cat_title);
mysqli_stmt_store_result($stmt);
$result = mysqli_stmt_num_rows($stmt);


if (!$result) {
   ?>

      <div class="row">
         <div class="col-xs-12">
            <div class="panel panel-default login-box">
               <div class="panel-body">
                  <div class="text-center">

                     <h3 class="panel-h3">
                        <i class="far fa-frown fa-10x"></i>
                     </h3>
                     <h3>Перед тим як додавати товари, Вам необхідно створити хоча б одну категорію!</h3>
                     <br>
                     <p class="text-center">
                        <a class="btn btn-sm btn-primary" href="prod_categories.php">Додати категорію?</a>
                     </p>
                     <div class="panel-body">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>


   <?php
} else {
   ?>
   <div class='col-xs-12 col-sm-12 col-md-4'>
   <form action="" method="POST" class="form-horizontal" role="form" enctype="multipart/form-data"
         id="add_product_form">
      <div class="form-group">
         <label style="font-size: 14px;">Назва товару <span class="question" data-descr="Обов'язкове поле! В даному полі необхідно ввести назву товару,
                                                               яка буде відображатися в кабінеті Ваших клієнтів. Максимальна довжина назви 
                                                               товару - 50 символів">?</span>
         </label>
         <?= '<br><span>' . ((isset($a_error['title'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['title'] . '</span></span>' : '') ?>
         <?= '<span>' . ((isset($a_data['title'])) ? '<span class="ok-span"></span></span>' : '') ?>
         <div class="input-group input-group-register">
            <span class="input-group-addon my-addon addon-register"><i class="fab fa-gulp fa-lg"></i></span>
            <input type="text" name="title" id="product_name" class="form-control input-register reg-page"
                   value="<?= (isset($a_data['title']) ? htmlspecialchars($a_data['title']) : '') ?>"
                   required="required" maxlength="50">
         </div>
      </div>

      <div class="form-group ">
         <label for="prod_category_id" style="font-size: 14px;">Категорія товару <span class="question"
                                                                                       data-descr="Обов'язкове поле! Виберіть категорію, до якої буде додано товар із випадаючого списку! Якщо Ви бачите, що якоїсь категорії не вистачає - тоді спочатку додайте її на відповідній сторінці.">?</span></label>
         <?= '<br><span>' . ((isset($a_error['category_id'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['category_id'] . '</span></span>' : '') ?>
         <?= '<span>' . ((isset($a_data['category_id'])) ? '<span class="ok-span"></span></span>' : '') ?>
         <div class="input-group input-group-register">
            <span class="input-group-addon my-addon addon-register"><i class="far fa-file-alt"></i></span>
            <select class="form-control reg-page input-register" name="category_id" id="category_id" required>
               <option value=''>Виберіть категорію</option>
               <?php
               if (isset($a_data['category_id'])) {
                  select_all_cats($a_data['category_id']);
               } else {
                  select_all_cats();
               }
               ?>
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
                   value="<?= (isset($a_data['price']) ? htmlspecialchars($a_data['price']) : '') ?>"
                   required="required">
         </div>
      </div>
      <div class="form-group">
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
         <div id="q-container">
            <div id="quill-prod-descr"
                 class=""><? echo(isset($a_data['description']) ? $a_data['description'] : ''); ?></div>
         </div>
      </div>

      <hr>
      <div class="form-group">
         <button type="submit" class="register-submit" name="add_product_subm">Додати товар</button>
      </div>
   </form>
</div>
<div class='col-xs-12 col-sm-12 col-md-8'>
   <h4 class="text-center" style="margin: 0; margin-bottom:9px;">Вже додані товари</h4>
   <?php
   if(get_quantity_in_db('my_coffee_products')){
      include_once "includes_admin/current_products_list.php";
   } else {
      ?>
      <div class="panel panel-default login-box">
         <div class="panel-body">
            <div class="text-center">

               <h3 class="panel-h3">
                  <i class="far fa-frown fa-10x"></i>
               </h3>
               <h3>Ви ще поки не додали жодного товару!</h3>
               <br>
               <div class="panel-body">
               </div>
            </div>
         </div>
      </div>
   <?php
   }
   ?>

</div>
<?php
}

?>





