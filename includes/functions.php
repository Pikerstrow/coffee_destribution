<?php
require_once 'db.php';
/*----------------------------------------------------------------------------*/
/*----------------------------------FORM VALIDATORS---------------------------*/
/*----------------------------------------------------------------------------*/

/*
 * EMAIL VALIDATOR * 
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed. 
 */
function email($email){    
    if ( '' === $email or empty($email) )
        return 'Поле не заповнене!';    
    if ( false === filter_var( $email, FILTER_VALIDATE_EMAIL ) ) 
        return 'Email адресу введено не вірно! Адреса повинна містити символи "@" та "." і повинна бути написана латинськими літерами!';
    if ( mb_strlen ( $email ) > 255 ) 
        return 'Email адреса надто довга! Максимально допустима кількість символів - 255!';
    
    return true;    
}
/*
 * CLIENT NAMES VALIDATOR * 
 * This function uses for validation of names of companies and names of their establishments.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed.
 * IMPORTANT: FOR CYRILLIC SYMBOLS ONLY!
 */
function name($name){    
    if ( '' === $name or empty($name) )
        return 'Поле не заповнене!';    
    if ( mb_strlen( $name ) > 50 ) 
        return 'Введене значення перевищує дозволених 50 символів';
    if ( ! preg_match( '#^[А-ЯІЇЄа-яєії0-9\-]+\s?[А-ЯІЄЇа-яєії0-9\-\s\."]+$#ui', $name ) ) 
        return 'Не вірний формат запису! Приймаються лише значеня, введені кирилицею!';
    if ( is_numeric( $name ) ) 
        return 'Не вірний формат запису! Назва не може скаладатися лише із цифр!';
    
    return true;    
}
/*
 * TITLES VALIDATOR 2 (WITHOU CYRRILIC CHECK)* 
 * This function uses for validation of categories titles, goods titles, etc.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed. * 
 */
function title($title){    
    if ( '' === $title or empty($title) )
        return 'Поле не заповнене!';    
    if ( mb_strlen( $title ) > 50 ) 
        return 'Введене значення перевищує дозволених 50 символів';
    if ( mb_strlen( $title ) < 3 ) 
        return 'Назва не може містити менше ніж 3 символи';    
    if ( is_numeric( $title ) ) 
        return 'Не вірний формат запису! Назва не може скаладатися лише із цифр!';
    
    return true;    
}
/*
 * ESTABLISHMENT NAME VAIDATOR (WITHOU CYRRILIC CHECK)* 
 * This function uses for validation of categories titles, goods titles, etc.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed. * 
 */
function establishment($title){    
    if ( '' === $title or empty($title) )
        return 'Поле не заповнене!';    
    if ( mb_strlen( $title ) > 50 ) 
        return 'Введене значення перевищує дозволених 50 символів';
    if ( mb_strlen( $title ) < 3 ) 
        return 'Назва не може містити менше ніж 3 символи';    
    if ( is_numeric( $title ) ) 
        return 'Не вірний формат запису! Назва не може скаладатися лише із цифр!';
    
    return true;    
}
/*
 * CATEGORIES VALIDATOR* 
 * This function uses for validation of category_id * 
 */
function category_id($category_id){  
    global $link;
    
    $query    = "SELECT category_id FROM my_coffee_prod_categories";

    $stmt     = mysqli_prepare($link, $query);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $cat_id);

    $avail_cats = []; // For available categories at this moment;

    while (mysqli_stmt_fetch($stmt)) {    
        $avail_cats[] = $cat_id;    
    }
    
    if (!in_array($category_id, $avail_cats)) 
        return 'Вибраної Вами категорії не існує!';    
    if ('' == $category_id or empty($category_id)) 
       return 'Вибір категорії є обов\'язковим!';
        
    return true;    
}
/*
 * CONTACT PERSON NAME VALIDATOR * 
 * This function uses for validation of contact person's name.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed.
 * IMPORTANT: FOR CYRILLIC SYMBOLS ONLY!
 */
function person_name($name){    
    if ( '' === $name or empty($name) )
        return 'Поле не заповнене!';    
    if ( mb_strlen( $name ) > 70 ) 
        return 'Введене значення перевищує дозволених 70 символів';
    if ( ! preg_match('#^[А-ЯІЄЇ]{1}[а-яієї]{2,27}\s[А-ЯЄІЇ]{1}[а-яієї]{2,20}\s[А-ЯІЇЄ]{1}[а-яієї]{2,20}$#u', $name ) ) 
        return 'Не вірний формат запису імені! Ім\'я повинне бути написане кирилицею,
                з пробілом між прізвищем, ім\'ям та по-батькові! Перші літери прізвища, 
                імені та по-батькові повинні бути великими!';
    
    return true;    
}
/*
 * MOBILE PHONE NUMBER VALIDATOR * 
 * This function uses for validation of contact person's mobile phone number.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed.
 * IMPORTANT: FOR UKRAINIAN MOBILE OPERATORS ONLY !
 */
function phone_number($ph_number){    
    if ( '' === $ph_number or empty($ph_number) )
        return 'Поле не заповнене!';    
    if ( mb_strlen( $ph_number ) > 17 ) 
        return 'Кількість введених символів перевищує максимально можливу довжину телефонного номеру в нашій країні!';
    if ( ! preg_match('#^\+38\(0(50|6(3|6|7|8)|73|9(3|7|8|9|6|5))\)[0-9]{3}-[0-9]{2}-[0-9]{2}$#', $ph_number ) )
        return 'Номер телефону повинен бути у форматі +38(ХХХ)ХХХ-ХХ-ХХ і не може містити літер або інших символів, 
                крім тих, які в наведеному зразку! Зверніть увагу, код оператора мобільного зв\'зку повинен також бути чинним.';
    
    return true;    
}
/*
 * MOBILE PHONE NUMBER VALIDATOR (contact us form)*
 * This function uses for validation of contact person's mobile phone number.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed.
 * IMPORTANT: FOR UKRAINIAN MOBILE OPERATORS ONLY !
 */
function ph_number($ph_number){
   if ( '' === $ph_number or empty($ph_number) )
      return 'Поле не заповнене!';
   if ( mb_strlen( $ph_number ) > 17 )
      return 'Кількість введених символів перевищує максимально можливу довжину телефонного номеру в нашій країні!';
   if ( !preg_match('#^(\+38|38)?\(?0(50|6(3|6|7)|73|9(3|7|8|9|6|5))\)?(\d{3}|\d{2})\-?\d{2}\-?(\d{3}|\d{2})$#', $ph_number ) )
      return 'Не вірний формат запису номеру телефону!';

   return true;
}
/*
 * LOGIN VALIDATOR * 
 * This function uses for validation of client's login.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed. * 
 */
function login($login){    
    if ( '' === $login or empty($login) )
        return 'Поле не заповнене!';    
    if ( mb_strlen($login) > 20 and mb_strlen($login) < 5 ) 
        return 'Довжина логіну повинна бути від 5 до 20 символів!';
    if ( ! preg_match( '#^\w{5,20}$#i', $login ) ) 
        return 'Логін містить заборонені символи! 
                Логін повинен складатися із літер латинського алфавіту "a-z" (як з великих, так і з малих)
                та може містити цифри.';
    
    return true;    
}
/*
 * PASSWORD VALIDATOR * 
 * This function uses for validation of client's password.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed. * 
 */
function password($password){    
    if ( '' === $password or empty($password) )
        return 'Поле не заповнене!';    
    if (mb_strlen( $password ) > 32 and mb_strlen( $password ) < 6 ) 
        return 'Довжина пароля повинна бути від 6 до 32 символів';
    if ( !preg_match( '#^[a-zA-Z0-9\-\*\_]{6,32}$#i', $password ) ) 
        return 'Пароль містить заборонені символи! 
                Пароль повинен складатися із літер латинського алфавіту "a-z" (як з великих, так і з малих)
                та може містити цифри.';
    if ( ! strpbrk( $password, '*-_+$&?!></:;' ) )     
        return 'Пароль повинен містити хоча б один спецсимвол типу *, -, $ тощо';
    if (is_numeric($password))
        return "Пароль не може скалдатися лише із цифр.";
    
    return true;    
}
/*
 * PASSWORD CONFIRM FILED VALIDATOR * 
 * This function uses for checking if the entered passwords match each other.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed. * 
 */
function password_confirm($password, $password_confirmed = ''){    
    if ( '' === $password_confirmed or empty($password_confirmed) )
        return 'Поле не заповнене!';    
    if (isset($password) and !empty($password) and $password != $password_confirmed ) 
        return 'Паролі не співпадають!';
    
    return true;    
}
/*
 * NUMBERS FILED VALIDATOR (FOR INTEGERS ONLY)* 
 * This function uses for checking if the retrieved from the form field is number.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed. * 
 */
function numbers($number, $max = 2147483647 ){    
    if ( '' === $number or empty($number) )
        return 'Поле не заповнене!';    
    if ($number < 0 and $number > $max ) 
        return 'Число не може бути меншим за 0 та більшим за ' . number_format( $max, 2, '.', ' ' ) . '!';
    if ( !ctype_digit($number) ) 
        return 'Введене значення не є числом!';
    
    return true;    
}
/*
 * PRICE VALIDATOR* 
 * This function uses for checking if the retrieved from the form data is a correct price.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed. * 
 */
function price ($price){    
    if ('' === $price)
        return 'Ціну товару не введно!';
    else if (mb_strlen($price) < 3 and mb_strlen($price) > 10) {
        return 'Введене значення не дуже схоже на ціну!';
    } else if (!preg_match('#^\d{1,7}\.?\d{0,2}?$#', $price)) {
        return 'Ціна з копійками вводится у форматі 125.25 (обов\'язково через крапку!), 
                                         де після крапки може бути одна або дві цифри. Без копійок - можна вводити
                                         лише число без використання крапки(наприклад, 125)';
    }

    return true;    
}
/*
 * TEXT VALIDATOR * 
 * This function uses for validation of texts, etc.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed.
 * IMPORTANT: FOR CYRILLIC SYMBOLS ONLY!
 */
function description($text){    
    if ( '' === $text or empty($text) or mb_strlen($text) < 15)
        return 'Опис надто короткий або взагалі відсутній!';
    if ( mb_strlen( $text ) > 1500 ) 
        return 'Введене значення перевищує дозволених 1500 символів'; 
    
    return true;    
}
/*
 * TEXT VALIDATOR (MESSAGES FROM VISITORS) *
 * This function uses for validation of texts, etc.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed.
 * IMPORTANT: FOR CYRILLIC SYMBOLS ONLY!
 */
function message($text){
   if ( '' === $text or empty($text) or mb_strlen($text) < 15)
      return 'Повідомлення надто коротке або взагалі відсутнє!';
   if ( mb_strlen( $text ) > 2500 )
      return 'Введене значення перевищує дозволених 2500 символів';

   return true;
}
/*
 * NUMBERS FILED VALIDATOR (FOR INTEGERS ONLY)* 
 * This function uses for checking if the retrieved from the form field is number.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed. * 
 */
function quantity($number, $max = 2147483647 ){    
    if ( '' === $number or empty($number) )
        return 'Поле не заповнене!';    
    if ($number < 0 and $number > $max ) 
        return 'Число не може бути меншим за 0 та більшим за ' . number_format( $max, 2, '.', ' ' ) . '!';
    if ( !ctype_digit($number) ) 
        return 'Введене значення не є числом!';
    
    return true;    
}
/*
 * IMAGES VALIDATOR * 
 * This function uses for checking images.
 * Returns bool 'true' if validation pass successfully and text of error in case of validation is failed. * 
 */
function image($data){    
        
    $a_ext = [ 'png', 'jpeg', 'jpg' ];
        
    $ext = mb_strtolower( pathinfo( $data['name'], PATHINFO_EXTENSION ));
                
    if ( $data == [] )
        return "Ви не вибрали файл!!!";
               
    if ( $data['error'] != 0 )
        switch ( $data['error'] ) {                
            case 1 : return 'Розмір файлу перевищує допустимі значення';
                break;
            case 2 : return 'Розмір файлу еревищує допустимі значення';
                break;
            case 3 : return 'Помилка передачі файлу. Файл передано лише частково';
                break;
            case 4 : return 'Файл не було завантажено';
                break;
            case 6 : return 'На сервері відсутня директорія для завантаження файлу. Повідомте про дану помилку розробника за посиланням в підвалі сайту';
                break;
            case 7 : return 'Не вдалося записати файл на диск. Повідомте про дану помилку розробника за посиланням в підвалі сайту';
                break;
            case 8 : return 'Помилка завантаження файлу. Повідомте про дану помилку розробника за посиланням в підвалі сайту';
                break;
        }
        
    if ( $data['size'] > 2*1024*1024 ) 
        return "Файл надто великий. Максимальний розмір 2 Мб";
                
    if ( ! in_array( $ext, $a_ext )) 
        return "Недопустиме розширення файлу. Можливі розширення: \'.png\', \'.jpeg\', \'.jpg\'. '";
                
    if (false === $image = getimagesize( $data['tmp_name'] ))     
        return 'Файл, який Ви намагаєтесь завантажите не являється зображенням!';
            
    if ( $image[0] > 1024 or $image[1] > 1024 )
        return 'Максимально допустимий розмір зображення: 1024 х 1024 px.'; 
        
    return true;
}
/*
 * FORM VALIDATION
 */
function form_validate(array $data) {

    $form_data = [];
    
    foreach ($data as $key => $value) {
        
        switch ($key) {
            case 'email' :
                if (is_bool($key($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;
            case 'name' :
                if (is_bool($key($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;
            case 'title' :
                if (is_bool($key($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;
            case 'description' :
                if (is_bool($key($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;
            case 'message' :
                if (is_bool($key($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;
            case 'phone_number' :
                if (is_bool($key($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;
           case 'ph_number' :
              if (is_bool($key($value))) {
                 $form_data['data'][$key] = $value;
              } else {
                 $form_data['errors'][$key] = $key($value);
              }
              break;
            case 'login' :
                if (is_bool($key($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;
            case 'password' :
                if (is_bool($key($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;            
            case 'password_confirm' :
                if (is_bool(password($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;            
            case 'quantity' :
                if (is_bool($key($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;
            case 'image' :
                if (is_bool($key($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;
            case 'person_name' :
                if (is_bool($key($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;
            case 'price' :
                if (is_bool($key($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;
            case 'category_id' :
                if (is_bool($key($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;
            case 'establishment' :
                if (is_bool($key($value))) {
                    $form_data['data'][$key] = $value;
                } else {
                    $form_data['errors'][$key] = $key($value);
                }
                break;
            default :
              $form_data['data'][$key] = $value;
        }        
    }
    return $form_data;
}


/*----------------------------------------------------------------------------*/
/*------------------FOR WORKING WITH DATABASE (UNIVERSAL)---------------------*/
/*----------------------------------------------------------------------------*/

/*
 * INSERT INTO DB
 * This function can be used for inserting data into DB, that was gathered from different forms
 * and was retrieved from the global $_POST as an assoc array;
 */
function insert_to_db(array $data, $table_name, $prefix = '') {
    
    global $link;
    
    $query = " INSERT INTO `{$table_name}` (";
    
    for ($i = 0; $i < count($data); $i++ ){
        if ($i == 0)
            $query .= mysqli_real_escape_string( $link, $prefix . array_keys($data)[$i]);
        else
            $query .= ", " . mysqli_real_escape_string( $link, $prefix . array_keys($data)[$i]);
    }
    
    $query .= ") VALUES (";
    
    for ($i = 0; $i < count($data); $i++ ){
        if ($i == 0)
            $query .= "'" . mysqli_real_escape_string( $link,  array_values($data)[$i]) . "'";
        else
            $query .= ", " . "'" . mysqli_real_escape_string( $link, array_values($data)[$i])  . "'";
    }
    
    $query .= ")";
    
    $insert_data = mysqli_query($link, $query);
        
    if (!$insert_data)
        return "Сталася помилка і дані не знесено до БД! У випадку якщо помилка повториться
                - надішліть, будь ласка повідомлення із 'скріншотом помилки' на адресу pikerstrow@gmail.com. Дякуємо!";
    else 
        return true;    
}

 
/*
 * DELETE FROM DB
 * This simple function can be used for deleting data from DB;
 */
function delete_from_db ($table_name, $db_param, $own_param ){
     
    global $link;
    
    $query = "DELETE FROM `{$table_name}` WHERE " . $db_param . " = " . "'" . $own_param . "'";

    $result = mysqli_query($link, $query);

    if (!$result) {
        return "Сталася помилка відправки даних до БД!";
    } else if (($rows = mysqli_affected_rows($link)) <= 0) {
        return "Помилка! В базі даних відсутній запис, який Ви намагаєтесь видалити!";
    } else {
        return true;
    }    
}
/*
 * UPDATE IN DB
 * This function can be used for updating data in DB;
 */
function update_in_db(array $data, $table_name, $db_param, $own_param, $prefix = '') {
    
    global $link;
    
    $query = " UPDATE `{$table_name}` SET ";
    
    for ($i = 0; $i < count($data); $i++ ){
        if ($i == (count($data) - 1))
            $query .= $prefix . array_keys($data)[$i] . " = " . "'" . mysqli_real_escape_string( $link, array_values($data)[$i])  . "'";
        else
            $query .= $prefix . array_keys($data)[$i] . " = " . "'" . mysqli_real_escape_string( $link, array_values($data)[$i])  . "', ";
    }
    
    $query .= " WHERE " . $db_param . " = " . "'" . $own_param . "'";
            
    $update_data = mysqli_query($link, $query);
        
    if (!$update_data)
        return "Сталася помилка! Дані не оновлено. У випадку якщо помилка повториться
                - надішліть, будь ласка повідомлення із 'скріншотом помилки' на адресу pikerstrow@gmail.com. Дякуємо!" . mysqli_error($link);
    else if (($rows = mysqli_affected_rows($link)) <= 0) {
        return "Помилка! В базі даних відсутні записи, які Ви намагаєтесь оновити!";
    } else {
        return true;
    }    
}

/*For selecting all rows from db*/
function select_all($db_table){
    global $link;
    $query = "SELECT * FROM {$db_table}";
    $result = mysqli_query($link, $query);
    check_the_query($result);
    $data = mysqli_fetch_array($result);
    return $data;
}

/*For selecting client*/
function select_by_login($db_table, $login){
    global $link;
    $query = "SELECT * FROM `{$db_table}` WHERE client_login = '{$login}'";
    $result = mysqli_query($link, $query);
    check_the_query($result);
    $data = mysqli_fetch_assoc($result);
    return $data;
}
/*For selecting admin*/
function select_admin_by_login($db_table, $login){
    global $link;
    $query = "SELECT * FROM `{$db_table}` WHERE admin_login = '{$login}'";
    $result = mysqli_query($link, $query);
    check_the_query($result);
    $data = mysqli_fetch_assoc($result);
    return $data;
}
/*For selecting client/admin from db by id*/
function select_by_id($db_table, $id_col_in_db, $id){
    global $link;
    $query = "SELECT * FROM `{$db_table}` WHERE `{$id_col_in_db}` = '{$id}'";
    $result = mysqli_query($link, $query);
    check_the_query($result);
    $data = mysqli_fetch_assoc($result);
    return $data;
}


/*----------------------------------------------------------------------------*/
/*---------------------------------MISCELLANEOUS------------------------------*/
/*----------------------------------------------------------------------------*/

/*CHECK IF IMAGE EXIST*/
function image_is_set($image = null){
    if (!$image) {
        return 'no_photo.png';
    } else {
        return $image;
    }
}

/*CHECK IF PRODUCT EXIST*/
function product_exists($product_id){
    global $link;
    $query = "SELECT product_id FROM my_coffee_products WHERE product_id = {$product_id}";
    $result = mysqli_query($link, $query);    
    return (mysqli_num_rows($result) >= 1) ? true : false;
}

/*CHECK IF COFFEE MACHINE EXIST*/
function coffee_machine_exists($machine_title = ''){
    global $link;
    $query = "SELECT machine_title FROM my_coffee_machines WHERE machine_title = '{$machine_title}'";
    $result = mysqli_query($link, $query);
    check_the_query($result);
    return (mysqli_num_rows($result) >= 1) ? true : false;
}

/*CHECK IF CLIENT LOGIN ALREADY EXISTS IN DB*/
function client_login_exists($login) {
    global $link;

    $query = "SELECT client_login FROM my_coffee_clients WHERE client_login = '{$login}'";

    $result = mysqli_query($link, $query);
    return mysqli_num_rows($result) >= 1 ? true : false;
}

/*CHECK IF CLIENT ALREADY EXISTS IN DB BY ID*/
function client_exists($id) {
    global $link;

    $query = "SELECT * FROM my_coffee_clients WHERE client_id = $id";

    $result = mysqli_query($link, $query);
    return mysqli_num_rows($result) >= 1 ? true : false;
}

/*CHECK IF ADMIN LOGIN ALREADY EXISTS IN DB*/
function admin_login_exists($login) {
  global $link;

  $query = "SELECT admin_login FROM my_coffee_admins WHERE admin_login = '{$login}'";
  
  $result = mysqli_query($link, $query);
  return mysqli_num_rows($result) >= 1 ? true : false;
}

/*CHECK IN SUCH CLIENT EMAIL ALREADY EXISTS IN DB*/
function client_email_exists($email) {
  global $link;

  $query = "SELECT client_email FROM my_coffee_clients WHERE client_email = '{$email}'";
  $result = mysqli_query($link, $query);
  return mysqli_num_rows($result) >= 1 ? true : false;
}

/*CHECK IN SUCH ADMIN EMAIL ALREADY EXISTS IN DB*/
function admin_email_exists($email) {
  global $link;

  $query = "SELECT admin_email FROM my_coffee_admins WHERE admin_email = '{$email}'";
  $result = mysqli_query($link, $query);
  return mysqli_num_rows($result) >= 1 ? true : false;
}

/*CHECK IN SUCH PHONE NUMBER ALREADY EXISTS IN DB*/
function phone_number_exists($ph_number) {
  global $link;

  $query = "SELECT client_phone_number FROM my_coffee_clients WHERE client_phone_number = '{$ph_number}'";
  $result = mysqli_query($link, $query);
  return mysqli_num_rows($result) >= 1 ? true : false;
}


/*CHECK THE QUERY TO DB*/
function check_the_query($result){
    global $link;            
    if (!$result) {
        die('Помилка запиту до БД');
    }
    
}

/*
 * GET DATA FROM THE FORMS
 * This function can be used for retrieving all form data from the global $_POST as an assoc array;
 */
function mf_get_data (array $field, $method) {
    $return = [];    
        foreach ($field as $row) {
            $return[$row] = mf_get_string( $row, $method );
        }        
    return $return;
}
/*
 * GET DATA FROM THE FORM FIELD AS A STRING. 
 */
function mf_get_string ($name, $method) {
    if ($method === 'post') {
        return ( isset( $_POST[$name] ) and is_string( $_POST[$name] ) ) ? trim( $_POST[$name] ) : '';
    } else if ($method === 'get') {
        return ( isset( $_GET[$name] ) and is_string( $_GET[$name] ) ) ? trim( $_GET[$name] ) : ''; 
    }
}
/*
 * GET DATA FROM THE FORM FIELD AS AN ARRAY. 
 */
function mf_get_array ($name, $method) {
    if ($method === 'post') {
        return ( isset( $_POST[$name] ) and is_array( $_POST[$name] ) ) ? $_POST[$name] : [];
    } else if ($method === 'get') {
        return ( isset( $_GET[$name] ) and is_array( $_GET[$name] ) ) ? $_GET[$name]  : []; 
    }
}
/*
 * GET DATA FROM THE FORM FIELD AS FILE. 
 */
function mf_get_file ($name) {
    return ( isset( $_FILES[$name] ) and is_array( $_FILES[$name] ) ) ? $_FILES[$name] : [];    
}

/*
 * FOR THE CHECKING OF AUTHORIZATION. 
 */
function mf_check_auth () {
    if ( ! isset( $_SESSION['auth'] ) ) {
        exit( header( 'Location: ../login.php' ) );
    }
    else {
        if ( $_SESSION['ua'] != $_SERVER['HTTP_USER_AGENT'] or $_SESSION['ip'] != $_SERVER['REMOTE_ADDR'] ) {
            unset( $_SESSION['auth'] );
            unset( $_SESSION['ua'] );
            unset( $_SESSION['ip'] );

            exit( header( 'Location:../login.php' ) );
        }
    }
}
/*CHECK IF USER IS LOGGED IN*/
function is_logged_in(){
    if (isset($_SESSION['auth']) and $_SESSION['ua'] == $_SERVER['HTTP_USER_AGENT'] and $_SESSION['ip'] == $_SERVER['REMOTE_ADDR']) {
        return true;
    }
    return false;
}

/*
 * INFO BLOCKS. 
 */
function set_info( $text ) {
    $_SESSION['infoblock']['info'][] = $text;
}

function set_error( $text ) {
    $_SESSION['infoblock']['error'][] = $text;
}

function set_success( $text ) {
    $_SESSION['infoblock']['success'][] = $text;
}

function set_warning( $text ) {
    $_SESSION['infoblock']['warning'][] = $text;
}

function get_info_block() {
    if ( ! isset( $_SESSION['infoblock'] ) )
        return true;
    
    foreach ( $_SESSION['infoblock'] as $type => $a_text )  {        
        switch ( $type ) {
            case 'info':    $info_div_style = 'alert alert-info info_block';    break;
            case 'error':   $info_div_style = 'alert alert-danger info_block';  break;
            case 'success': $info_div_style = 'alert alert-success info_block'; break;
            case 'warning': $info_div_style = 'alert alert-warning info_block'; break;
        } 
        echo '<div class="' . $info_div_style . '">' .  implode( '<br /><br />', $a_text ) . '</div>';
    }
    unset( $_SESSION['infoblock'] );
}

/*FIND CLIENT ID BY LOGIN*/
function find_client_by_login($login){   
   global $link;
        
   $query = "SELECT * FROM `my_coffee_clients` WHERE client_login = '{$login}'";    
   $result = mysqli_query($link, $query);    
   check_the_query($result);    
   $row = mysqli_fetch_array($result);    
   $client_id = $row['client_id'];
   
   return $client_id;
}

/*LOG IN FOR CLIENTS*/
function login_client($login, $password)
{
    global $link;

    $query = "SELECT * FROM `my_coffee_clients` WHERE client_login = '{$login}'";

    $result = mysqli_query($link, $query);

    check_the_query($result);

    $row = mysqli_fetch_array($result);

    $password_from_db = $row['client_password'];
    $login_from_db = $row['client_login'];
    $client_status = $row['client_status'];
    $client_deleted = $row['client_deleted'];

    if ($password_from_db === md5($password . SALT) and $client_status != 0 and $client_deleted != 'true') {
        $_SESSION['client'] = $login_from_db;
        $_SESSION['auth'] = true;
        $_SESSION['role'] = 'client';
        $_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];

        header('Location: ../client_panel');
        return true;
    } else if ($password_from_db === md5($password . SALT) and $client_status == 0) {
        return 'Вибачте, але Ваш обліковий запис поки не підтверджений адміністратором!';
    } else if($client_deleted == 'true'){
        return 'Вибачте, але Ваш обліковий запис було видалено!';
    } else {
        return 'Не вірно вказаний пароль!';
    }
}
/*LOG IN FOR ADMINS*/
function login_admin($login, $password){
   global $link;
        
   $query = "SELECT * FROM `my_coffee_admins` WHERE admin_login = '{$login}'";
    
   $result = mysqli_query($link, $query);
    
   check_the_query($result);
    
   $row = mysqli_fetch_array($result);
    
   $password_from_db = $row['admin_password'];
   $login_from_db = $row['admin_login'];
       
   if ($password_from_db === md5($password . SALT)) {
      $_SESSION['admin']   = $login_from_db;
      $_SESSION['auth']    = true;
      $_SESSION['role']    = 'admin';
      $_SESSION['ua']      = $_SERVER['HTTP_USER_AGENT'];
      $_SESSION['ip']      = $_SERVER['REMOTE_ADDR'];
      
      header('Location: ../admin_panel');
      return true;
   } else {
      return 'Не вірно вказаний пароль!';
   }
}
/*
 * FOR ORDER CHECKING - 1.
 * Checks if all products quantity comes from the custumer as a number. 
*/
function check_ordered_products( $arr ) { 
        foreach( $arr as $quantity) {       
            if ( ! ctype_digit($quantity) ) {           
                return false;
            }      
        }
        return true;
}
/*
 * FOR ORDER CHECKING - 2.
 * This function returns ID of product, the quantity of which was passed as a not number. 
*/
function ordered_products_bad_quantity( $arr ) {
    if ( $arr != [] ) {
        foreach( $arr as $key => $quantity) {       
            if ( ! ctype_digit($quantity) ) {           
                return $bad_key = $key;
            }      
        }
    } else {
        return false;
    }
}




/*----------------------------------------------------------------------------*/
/*------------------FOR WORKING WITH DATABASE (SITUATIONAL)-------------------*/
/*----------------------------------------------------------------------------*/

/*
 * SELECT CLIENTS ID.
 * This function selects and prints ID of all registred clients. 
*/
function all_clients_id() {
    global $link;
    $query = " SELECT client_id FROM my_coffee_clients ";
    $result = mysqli_query($link, $query);

        if (!$result) {
            die('Навдалий запит до бази даних! Спробуйте дещо пізніше.');
        }

    while ( $row = mysqli_fetch_assoc($result) ) {
        echo "<option value='{$row['client_id']}'>{$row['client_id']}</option>";
    }
}
/*
 * SELECT ALL PRODUCTS CATEGORIES.
 * This function selects and prints out all products categories.
*/
function select_all_categories() {
    
    global $link;    
    $token = session_id();
        
    $query  = "SELECT category_id, category_title FROM my_coffee_prod_categories";
    $stmt   = mysqli_prepare($link, $query);
              mysqli_stmt_execute($stmt);
              mysqli_stmt_bind_result($stmt, $cat_id, $cat_title);
              mysqli_stmt_store_result($stmt);
    $result = mysqli_stmt_num_rows($stmt);
    
    if (!$result) {        
        echo "<tr>";
            echo "<td align='center' colspan='4'><b>Ви поки що не додали жодної категорії</b></td>";
        echo "</tr>";
    } else {
        while (mysqli_stmt_fetch($stmt)) {
            echo "<tr>";
            echo "<td>{$cat_id}</td>";
            echo "<td>{$cat_title}</td>";
            echo "<td align='center'><a title=\"Редагувати\" style=\"padding: 5px 10px;\" class='btn btn-primary btn-xs' href='prod_categories.php?edit={$cat_id}&token={$token}'><i class=\"far fa-edit fa-lg\"></i></a></td>";
            echo "<td align='center'><a data-token='{$token}' rel='{$cat_id}' title=\"Видалити\" style=\"padding: 5px 10px;\" class='btn btn-danger btn-xs del-cat-link' href='prod_categories.php?delete={$cat_id}&token={$token}'><i class=\"far fa-trash-alt fa-lg\"></i></a></td>";
            echo "</tr>";
        }
    }
}
/*
 * SELECT ALL COFFEE MACHINES.
 * This function selects and prints out all coffee machines.
*/
function select_all_coffee_machines_table() {

    global $link;
    $token = session_id();

    $query  = "SELECT machine_id, machine_title FROM my_coffee_machines";
    $stmt   = mysqli_prepare($link, $query);
              mysqli_stmt_execute($stmt);
              mysqli_stmt_bind_result($stmt, $machine_id, $machine_title);
              mysqli_stmt_store_result($stmt);
    $result = mysqli_stmt_num_rows($stmt);

    if (!$result) {
        echo "<tr>";
            echo "<td align='center' colspan='4'><b>Ви поки що не додали жодної кавоварки</b></td>";
        echo "</tr>";
    } else {
        while (mysqli_stmt_fetch($stmt)) {
            echo "<tr>";
            echo "<td>{$machine_id}</td>";
            echo "<td>{$machine_title}</td>";
            echo "<td align='center'><a title=\"Редагувати\" style=\"padding: 5px 10px;\" class='btn btn-primary btn-xs' href='about_us.php?edit={$machine_id}&token={$token}'><i class=\"far fa-edit fa-lg\"></i></a></td>";
            echo "<td align='center'><a title=\"Видалити\" style=\"padding: 5px 10px;\" class='btn btn-danger btn-xs' href='about_us.php?delete={$machine_id}&token={$token}'><i class=\"far fa-trash-alt fa-lg\"></i></a></td>";
            echo "</tr>";
        }
    }
}
/*
 * SELECT ALL PRODUCTS CATEGORIES FOR SELECT OPTIONS.
 * This function selects and prints of all products categories. 
*/
function select_all_cats($cat='') {
    global $link;
    $query  = "SELECT category_id, category_title FROM my_coffee_prod_categories";
    $stmt   = mysqli_prepare($link, $query);
              mysqli_stmt_execute($stmt);
              mysqli_stmt_bind_result($stmt, $cat_id, $cat_title);
              mysqli_stmt_store_result($stmt);
    $result = mysqli_stmt_num_rows($stmt);
    
    if (!$result) { 
        echo "<option value='' selected>Досупні категорії відсутні</option>";
    } else {
        while (mysqli_stmt_fetch($stmt)) {
           if($cat == $cat_id){
              echo "<option value='{$cat_id}' selected>{$cat_title}</option>";
           } else {
              echo "<option value='{$cat_id}'>{$cat_title}</option>";
           }

        }
    }
}

/*FUNCTION FOR SELECTING ALL CLIENTS FROM DB*/
function select_all_clients($client='') {
    global $link;
    $query  = "SELECT client_name FROM my_coffee_clients";
    $stmt   = mysqli_prepare($link, $query);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $client_name);
    mysqli_stmt_store_result($stmt);
    $result = mysqli_stmt_num_rows($stmt);

    if (!$result) {
        echo "<option value='' selected>Зареєстровані в системі клієнти відсутні</option>";
    } else {
        while (mysqli_stmt_fetch($stmt)) {
            if($client == $client_name){
                echo "<option value='{$client_name}' selected>{$client_name}</option>";
            } else {
                echo "<option value='{$client_name}'>{$client_name}</option>";
            }
        }
    }
}

/*FUNCTION FOR COUNTING NUMBER OF ANYTHING IN DB*/
function get_quantity_in_db($table){
    global $link;
    $query = "SELECT * FROM `{$table}`";
    $result = mysqli_query($link, $query);
    check_the_query($result);
    return mysqli_num_rows($result);
}

/*FUNCTION FOR COUNTING NUMBER OF NEW ORDERS IN DB*/
function get_quantity_of_orders(){
    global $link;
    $query = "SELECT * FROM `my_coffee_orders` WHERE order_status = 0";
    $result = mysqli_query($link, $query);
    check_the_query($result);
    return mysqli_num_rows($result);
}

/*FUNCTION FOR COUNTING NUMBER OF NEW BREAKDOWN MASSAGES IN DB*/
function get_quantity_of_notifications(){
    global $link;

    $query = "SELECT COUNT(m.breakdown_id) AS amount, c.client_deleted FROM `my_coffee_breakdown_notifications` AS m ";
    $query .= "LEFT JOIN my_coffee_clients AS c ON m.coffee_client_id = c.client_id WHERE m.notification_status = 0 AND c.client_deleted != true";

    $result = mysqli_query($link, $query);
    check_the_query($result);
    $row = mysqli_fetch_assoc($result);
    return $row['amount'];
}
/*FUNCTION FOR COUNTING NUMBER OF NEW BREAKDOWN MASSAGES IN DB*/
function get_quantity_of_new_clients(){
    global $link;
    $query = "SELECT * FROM `my_coffee_clients` WHERE client_status = 0";
    $result = mysqli_query($link, $query);
    check_the_query($result);
    return mysqli_num_rows($result);
}

/*FUNCTION FOR COUNTING NUMBER OF NEW BREAKDOWN MASSAGES IN DB*/
function get_quantity_of_exists_clients(){
   global $link;
   $query = "SELECT * FROM `my_coffee_clients` WHERE client_status = 1";
   $result = mysqli_query($link, $query);
   check_the_query($result);
   return mysqli_num_rows($result);
}

/*FUNCTION FOR COUNTING NUMBER OF NEW BREAKDOWN MASSAGES IN DB*/
function get_quantity_of_messages_from_visitors(){
   global $link;
   $query = "SELECT * FROM `my_coffee_messages`";
   $result = mysqli_query($link, $query);
   check_the_query($result);
   return mysqli_num_rows($result);
}


/*FIND CLIENT PERSONAL DISCOUNT*/
function find_client_discount($login){
   
   global $link;
   $query  = "SELECT client_discont FROM my_coffee_clients WHERE client_login = '{$login}'";
   $result = mysqli_query($link, $query);
   check_the_query($result);
   if (mysqli_num_rows($result) < 1) {
      return $discount = 0;
   } else {
      $row = mysqli_fetch_array($result);
      if ($row['client_discont'] > 0) {
         return $discount = $row['client_discont'];
      } else {
         return $discount = 0;
      }
      
   }
}

/*TAKE ALL PRODUCTS AND PRICES*/
// This function returns array with all products and prices (with personal client discont).
// This allows to display orders in admin panel with correct prices even after admin changes prices or delete products
// Function returns an assoc array, where keys - products and values - prices

function take_all_products_and_prices($client_discount){
   global $link;
   
   $all_products_and_prices = []; // for storage data from db
   
   $query  = "SELECT product_title, product_price FROM my_coffee_products";
   $result = mysqli_query($link, $query);
   check_the_query($result);
   if (mysqli_num_rows($result) < 1) {
      return false;
   } else {
      while ($row = mysqli_fetch_assoc($result)) {
         $number = $row['product_price']*(1-($client_discount/100));
         $all_products_and_prices[$row['product_title']] = number_format($number, 2, '.', '');
      }
      return $all_products_and_prices;
   }
}




/*
 * SELECT ALL COFFEE MACHINES FOR SELECT OPTIONS.
 * This function selects and prints of all available coffee machines. 
*/
function select_all_coffee_machines() {
    global $link;
    $query  = "SELECT machine_id, machine_title FROM my_coffee_machines";
    $stmt   = mysqli_prepare($link, $query);
              mysqli_stmt_execute($stmt);
              mysqli_stmt_bind_result($stmt, $machine_id, $machine_title);
              mysqli_stmt_store_result($stmt);
    $result = mysqli_stmt_num_rows($stmt);
    
    if (!$result) { 
        echo "<option value='' selected>Кавоварки відсутні</option>";
    } else {
        while (mysqli_stmt_fetch($stmt)) {            
            echo "<option value='{$machine_title}'>{$machine_title}</option>";
        }
    }
}
/*
 * ADD PRODUCTS CATEGORIES TO DB.
 * This function inserts new categories to db. 
*/
function add_product_category() {
    
    if (isset($_POST['add_category_subm'])) {
        $a_data = mf_get_data(['category_title'],'post');
        $cat_title = $a_data['category_title'];
        
        if (!is_bool(name($cat_title))) {             
            return $error = name($cat_title);
        } else {
            if (!is_bool(insert_to_db($a_data, 'my_coffee_prod_categories'))) {
                return $error = insert_to_db($a_data, 'my_coffee_prod_categories');
            } else {
                return true;
            } 
        }
    }
}
/*
 * ADD COFFEE MACHINE TO DB.
 * This function inserts new about_us to db.
*/
function add_coffee_machine() {

    if (isset($_POST['add_machine_subm'])) {
        $a_data = mf_get_data(['machine_title'],'post');
        $machine_title = $a_data['machine_title'];

        if (!is_bool(title($machine_title))) {
            return $error = title($machine_title);
        } else {
            if (!is_bool(insert_to_db($a_data, 'my_coffee_machines'))) {
                return $error = insert_to_db($a_data, 'my_coffee_machines');
            } else {
                return true;
            }
        }
    }
}
/*
 * UPDATE PRODUCTS CATEGORIES IN DB.
 * This function updates categories to db.
*/
function update_product_category() {
    global $cat_id;
    if (isset($_POST['edit_category_subm'])) {
        $a_data = mf_get_data(['category_title'],'post');
        $cat_title = $a_data['category_title'];
        
        if (!is_bool(name($cat_title))) {             
            return $error = name($cat_title);
        } else {
            if (!is_bool(update_in_db($a_data, 'my_coffee_prod_categories', 'category_id', $cat_id))) {
                return $error = update_in_db($a_data, 'my_coffee_prod_categories', 'category_id', $cat_id);
            } else {
                return true;
            } 
        }
    }
}
/*
 * UPDATE COFFEE MACHINES IN DB.
 * This function updates coffee machines to db.
*/
function update_coffee_machine() {
    global $machine_id;
    if (isset($_POST['edit_machine_subm'])) {
        $a_data = mf_get_data(['machine_title'],'post');
        $machine_title = $a_data['machine_title'];

        if (!is_bool(title($machine_title))) {
            return $error = title($machine_title);
        } else {
            if (!is_bool(update_in_db($a_data, 'my_coffee_machines', 'machine_id', $machine_id))) {
                return $error = update_in_db($a_data, 'my_coffee_machines', 'machine_id', $machine_id);
            } else {
                return true;
            }
        }
    }
}
/*
 * CONFIRM NEW CLIENT REGISTARTION.
 * This function inserts new categories to db.
*/
function confirm_registration() {

    global $link;

    if (isset($_GET['approve']) and $_GET['approve'] == 'true') {
        $client_id = (isset($_GET['client_id']) and is_numeric($_GET['client_id'])) ? $_GET['client_id'] : '';
        $cl_email = isset($_GET['client_email']) ? $_GET['client_email'] : '';
        $query = "UPDATE `my_coffee_clients` SET client_status = 1 WHERE client_id = $client_id";

        if (!$result = mysqli_query($link, $query)){
            die('Помилка підтвердження реєстрації!');
        } else {
            $to = $cl_email;
            $subject = 'Вашу реєстрацію підтверджено!';

            $message = " 
            <html> 
                <head> 
                    <title>Реєстрацію підтверджено</title> 
                    <style>
                        .container {
                            border: 2px dashed grey;
                        }
                        .but,.but:hover{
                            width: auto;
                            background-color: darkgreen;
                            color: white;
                            text-decoration: none !important;
                            border: 1px solid #201E2F;                            
                        }
                        .my_p {
                            margin: 10px;
                            font-size: 18px;
                        }
                    </style>
                </head> 
                <body> 
                    <div class='container'>
                        <p class='my_p'>Вашу реєстрацію на сайті \"Краща кава у м. Львів\" підтверджено!</b></p>
                        <p class='my_p'>Тепер Ви можете вільно користуватися особистим кабінетом!</p>
                        <p class='my_p'><a class='but' href='coffee.web-ol-mi.pp.ua/client_panel'>Перейти в кабінет</a></p>
                    </div>                    
                </body> 
            </html>";
            $headers = 'From: admin@kostacoffee.com.ua' . "\r\n" .
                'MIME-Version: 1.0' . "\r\n" .
                'Content-type: text/html; charset=utf-8';

            mail($to, $subject, $message, $headers);

            set_success('Реєстрацію клієнта успішно підтверджено!');
            header('Location: clients.php?route=new_clients');
        }

    }
}
/*
 * DELETE CLIENT.
 * This function deletes clients from db. *
 */
function delete_clients() {


    if (isset($_GET['delete']) and $_GET['delete'] == 'true') {

        $client_id = (isset($_GET['client_id']) and is_numeric($_GET['client_id'])) ? $_GET['client_id'] : '';
        $token = $_GET['token'];

        if ($token != session_id()) {
            die('Переданий токен не вірний');
        }


        if (!is_bool($result = update_in_db(['client_deleted'=>'true'],'my_coffee_clients', 'client_id', $client_id))) {
            die(set_error('Видалення не відбулося.' . " " . $result));
        } else {
            set_success('Клієнта успішно видалено!');
            if (isset($_GET['deleteCurrent'])) {
                header('Location: clients.php');
            } else {
                header('Location: clients.php?route=new_clients');
            }
        }
    }

}
/*
 * DELETE MASSAGE.
 * This function deletes breakdown notifications from db. *
 */
function delete_massage() {

    if (isset($_GET['delete_massage']) and $_GET['delete_massage'] == 'true') {

        $massage_id = (isset($_GET['massage_id']) and is_numeric($_GET['massage_id'])) ? $_GET['massage_id'] : '';
        $token = $_GET['token'];

        if ($token != session_id()) {
            die('Переданий токен не вірний');
        }

        if (!is_bool($result = update_in_db(['notification_status'=>1],'my_coffee_breakdown_notifications', 'breakdown_id', $massage_id))) {
            die(set_error('Видалення не відбулося.' . " " . $result));
        } else {
            set_success('Повідомлення успішно видалено!');
            header('Location: breakdown_massages.php');
        }
    }
}

/*
 * DELETE MASSAGE FROM VISITOR.
 * This function deletes breakdown notifications from db. *
 */
function delete_massage_form_visitor() {

   if (isset($_GET['delete_massage']) and $_GET['delete_massage'] == 'true') {

      $massage_id = (isset($_GET['massage_id']) and is_numeric($_GET['massage_id'])) ? $_GET['massage_id'] : '';
      $token = $_GET['token'];

      if ($token != session_id()) {
         die('Переданий токен не вірний');
      }

      if (!is_bool($result = delete_from_db('my_coffee_messages','message_id', $massage_id))) {
         die(set_error('Видалення не відбулося.' . " " . $result));
      } else {
         set_success('Повідомлення успішно видалено!');
         header('Location: write_us_messages.php');
      }
   }
}


/*
 * DELETE PRODUCTS CATEGORIES.
 * This function deletes categories. * 
 */
function delete_categories() {    
    
    if (isset($_GET['delete']) and isset($_GET['token'])) {
        $token = $_GET['token'];
        $cat_for_del = $_GET['delete'];
        
        if ( $token != session_id() ) {
            set_warning('Токен не вірний. Захист від CSFR атак!');
            exit( header ('Location: ../login.php' ) );
        }
        
        if (!is_bool($result = delete_from_db('my_coffee_prod_categories', 'category_id', $cat_for_del))) {
            die(set_error('Видалення не відбулося.'));
        } else {
            set_success('Категорію успішно видалено!');
            header('Location: prod_categories.php');
        }
    }
}
/*
 * DELETE COFFEE MACHINES.
 * This function deletes coffee machines from db. *
 */
function delete_coffee_machine() {

    if (isset($_GET['delete']) and isset($_GET['token'])) {
        $token = $_GET['token'];
        $machine_for_del = $_GET['delete'];

        if ( $token != session_id() ) {
            set_warning('Токен не вірний. Захист від CSFR атак!');
            exit( header ('Location: ../login.php' ) );
        }

        if (!is_bool($result = delete_from_db('my_coffee_machines', 'machine_id', $machine_for_del))) {
            die(set_error('Видалення не відбулося.'));
        } else {
            set_success('Кавоварку успішно видалено!');
            header('Location: about_us.php');
        }
    }
}
/*
 * DELETE PRODUCTS.
 * This function deletes products. * 
 */
function delete_products() {    
    
    if (isset($_POST['delete'])) {
        global $link;
        
        $prod_id  = $_POST['product_id'];
        
        /*Select from DB image connected to the category*/
        $query    = "SELECT product_image FROM my_coffee_products WHERE product_id = ?";
        
        $stmt     = mysqli_prepare($link, $query);
                    mysqli_stmt_bind_param($stmt, 'i', $prod_id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $product_photo);
        
        while (mysqli_stmt_fetch($stmt)) {
            $image = $product_photo;
        }
                
        if (!is_bool($result = delete_from_db('my_coffee_products', 'product_id', $prod_id))) {
            die(set_error('Видалення не відбулося.'));
        } else {
            unlink('../images/products/' . $image);
            set_success('Товар успішно видалено!');
            header('Location: products.php');
        }
    } 
}
/*
 * DELETE ORDERS.
 * This function deletes orders. * 
 */
function delete_orders() {    
    
    if (isset($_POST['delete_order'])) {
        global $link;
        
        $order_id  = $_POST['order_id'];
            
        if (!is_bool($result = delete_from_db('my_coffee_orders', 'order_id', $order_id))) {
            die(set_error('Видалення не відбулося.'));
        } else {            
            set_success('Замовлення успішно видалено!');
            header('Location: orders.php');
        }
    } 
}

/*SELECT CATEGORIES FOR PRODUCT EDIT PAGE*/
function select_categories_for_prod_edit($prod_category_id) {
    global $link;    
    $query = "SELECT * FROM my_coffee_prod_categories";
    $result = mysqli_query($link, $query);
    check_the_query($result);

    while ($row = mysqli_fetch_assoc($result)) {
        $cat_id = $row['category_id'];
        $cat_title = $row['category_title'];

        if ($cat_id == $prod_category_id) {
            // selected attr allows to detect current category of the product
            echo "<option selected value='{$cat_id}'>{$cat_title}</option>";
        } else {
            echo "<option value='{$cat_id}'>{$cat_title}</option>";
        }
    }
}

/*For correct date format*/
function correct_date($date){
    $date_arr = explode('-', $date);
    return $date_arr[2] .'.'.$date_arr[1] .'.'.$date_arr[0];
}

/*Route checking*/
function checkRoute($availableRoutes){

   $path = $_SERVER['REQUEST_URI'];

   if($pos = strpos($path, "?") > 0){
      $route = substr($path, 0, $pos);
   } else {
      $route = $path;
   }

   if(in_array($route, $availableRoutes)){
      return true;
   }
   return false;
}