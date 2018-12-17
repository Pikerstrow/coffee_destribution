<!-- Header -->
<?php include_once "includes_client/client_header.php"; ?>
<!-- End of header -->

<?php
// Retrieving data from the DB about client for displaying in inputs like current data
$client = select_by_login('my_coffee_clients', $_SESSION['client']);

if(isset($_POST['client_profile_edit'])){

    // Receiving data from the form
    $data = mf_get_data(['name', 'establishment', 'person_name', 'phone_number', 'email', 'login', 'password', 'password_confirm'], 'post');

    // Validating received data
    $form_data = form_validate($data);

    // Receiving data after validation
    $a_data = (isset($form_data['data'])) ? $form_data['data'] : [];
    $a_error = (isset($form_data['errors'])) ? $form_data['errors'] : [];

    //Receiving an image
    $a_data['image'] = mf_get_file('image');


    /*IF CLIENT DOESN'T CHANGES OWN AVATAR*/
    if ('' === $a_data['image']['name'] or empty($a_data['image']['name'])) {
        $a_data['image'] = (isset($client['client_image']) and $client['client_image'] != null) ? $client['client_image'] : 'avatar.png' ;
    } else {
        /*IF CLIENT CHANGES OWN AVATAR*/
        if (isset($a_data['image']['name']) and ! empty($a_data['image']['name'])) {
            if (!is_bool(image($a_data['image']))) {
                $a_error['image'] = image($a_data['image']);
            }
        }
    }

    // Additional validation for matching password and password confirm fields
    if (isset($a_data['password']) and isset($a_data['password_confirm'])) {
        if (!is_bool(password_confirm($a_data['password'], $a_data['password_confirm']))) {
            $a_error['password_confirm'] = password_confirm($a_data['password'], $a_data['password_confirm']);
        }
    } else {
        $a_error['password_confirm'] = 'Попереднє поле - "пароль" не відповідає мінімальним вимогам! Необхідно виправити помилку там.';
    }


    // Extra validation for existing email, phone number and login in db
    if (isset($a_data['email']) and $a_data['email'] !== $client['client_email']) {
        if (client_email_exists($a_data['email']) or admin_email_exists($a_data['email'])) {
            $a_error['email'] = 'Введена email-адреса вже зареєстрована в системі!';
        }
    }
    if (isset($a_data['login']) and $a_data['login'] !== $client['client_login']) {
        if (client_login_exists($a_data['login']) or admin_login_exists($a_data['login'])) {
            $a_error['login'] = 'Введений логін вже зареєстрований в системі!';
        }
    }
    if (isset($a_data['phone_number']) and $a_data['phone_number'] !== $client['client_phone_number']) {
        if (phone_number_exists($a_data['phone_number'])) {
            $a_error['phone_number'] = 'Введений номер телефону вже зареєстрований в системі!';
        }
    }

    // Updating data in db if array with errors is empty
    if([] == $a_error){

        // deleting password_confirm because there is no such field in db
        unset($a_data['password_confirm']);

        //Hashing the user password
        $a_data['password'] = md5($a_data['password'] . SALT);

        /* IF CLIENT SELECTED NEW AVATAR */
        if (isset($a_data['image']['name']) and ! empty($a_data['image']['name'])) {
            /* Moving file to proper directory */
            $ext = mb_strtolower(pathinfo($a_data['image']['name'], PATHINFO_EXTENSION));
            $file_name = mb_substr(md5($a_data['image']['name'] . microtime()), 0, 10) . '.' . $ext;

            move_uploaded_file(
                $a_data['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/client_panel/images/' . $file_name
            );
            // Delete previous image
            if ($client['client_image'] != null and $client['client_image'] != 'avatar.png') {
                unlink('../client_panel/images/' . $client['client_image']);
            }

            /* Inserting data to db */
            //1. removing last elem from data, because it is array (image)
            array_pop($a_data);
            //2. push image name (instead of deleted array) for adding in db
            $a_data['image'] = $file_name;

            // updating data
            if (!is_bool(update_in_db($a_data, 'my_coffee_clients', 'client_id', $client['client_id'], 'client_'))) {
                $a_error['db_connection'] = update_in_db($a_data, 'my_coffee_clients', 'client_id', $client['client_id'], 'client_');
            } else {
                set_success('Профіль успішно відредаговано!');
                header("Location:client_profile.php");
            }
        } else {
            /* IF CLIENT DOESN'T CHANGE PHOTO */
            if (!is_bool(update_in_db($a_data, 'my_coffee_clients', 'client_id', $client['client_id'], 'client_'))) {
                $a_error['db_connection'] = update_in_db($a_data, 'my_coffee_clients', 'client_id', $client['client_id'], 'client_');
            } else {
                set_success('Профіль успішно відредаговано!');
                unset($a_data);
                header("Location:client_profile.php");
            }
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
                    <span>Особисті дані</span>
                </h2>
                <hr>
                <?php echo isset($a_error['db_connection']) ? $a_error['db_connection'] : ''; ?>
                <?php if (!isset($_GET['edit']) and !isset($_GET['client_login'])) { ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                            <!-- Begin user profile -->
                            <div class="box-info text-center user-profile-2">
                                <div class="header-cover">
                                </div>
                                <div class="user-profile-inner">
                                    <h4 class="white"><?php echo $client['client_login']; ?></h4>
                                    <img src="images/<?php echo (isset($client['client_image']) and $client['client_image'] != null) ? $client['client_image'] : 'avatar.png' ?>" class="img-circle profile-avatar" alt="User avatar">
                                    <hr>
                                    <table class="">
                                        <tr>
                                            <td class="profile-table"><b>Назва:</b></td>
                                            <td class="profile-table-td-two"><?php echo $client['client_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="profile-table"><b>Заклад:</b></td>
                                            <td class="profile-table-td-two"><?php echo $client['client_establishment'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="profile-table"><b>Контактна особа:</b></td>
                                            <td class="profile-table-td-two"><?php echo $client['client_person_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="profile-table"><b>Телефон:</b></td>
                                            <td class="profile-table-td-two"><?php echo $client['client_phone_number'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="profile-table"><b>Email:</b></td>
                                            <td class="profile-table-td-two"><?php echo $client['client_email'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="profile-table"><b>Логін:</b></td>
                                            <td class="profile-table-td-two"><?php echo $client['client_login'] ?></td>
                                        </tr>
                                    </table>
                                    <!-- User button -->
                                    <div class="user-button">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <a href="client_profile.php?edit=true&client_login=<?php echo $client['client_login']; ?>"
                                                   class="btn btn-sm btn-block btn-profile-edit"> Редагувати профіль</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else {
                // checking for correct get parameter
                if (!is_bool($_GET['edit']) and $_GET['edit'] !== 'true') {
                    exit(header('Location:client_profile.php'));
                } else {
                    $client = select_by_login('my_coffee_clients', $_SESSION['client']);
                }
                ?>
                    <div class="row">

                        <!-- edit user data form -->
                        <div class="col-sx-12 col-md-6">
                            <h3>Форма для редагування</h3>
                            <form action="" method="POST" role="form" name="registration_form" enctype="multipart/form-data">
                                <div class="form-group form-group-register">
                                    <?= '<span>' . ((isset($a_error['name'])) ? '<br><span class="error-span"><b>Помилка: </b>' . $a_error['name'] . '</span></span>' : '') ?>
                                    <?= '<span>' . ((isset($a_data['name'])) ? '<span class="ok-span"></span></span>' : '') ?>
                                    <div class="input-group input-group-register">
                                    <span class="input-group-addon addon-register">
                                         <span class="glyphicon glyphicon-briefcase"></span>
                                    </span>
                                        <input type="text" class="form-control reg-page input-register"
                                               id="client_name"
                                               name="name" placeholder="Назва юридичної особи"
                                               required='required' maxlength="50"
                                               value='<?= (isset($client['client_name']) ? $client['client_name'] : '') ?>'>
                                    </div>
                                </div>

                                <div class="form-group form-group-register">
                                    <?= '<br><span>' . ((isset($a_error['establishment'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['establishment'] . '</span></span>' : '') ?>
                                    <?= '<span>' . ((isset($a_data['establishment'])) ? '<span class="ok-span"></span></span>' : '') ?>
                                    <div class="input-group input-group-register">
                                         <span class="input-group-addon addon-register">
                                             <span class="glyphicon glyphicon-cutlery"></span>
                                         </span>
                                        <input type="text" class="form-control reg-page input-register"
                                               id="client_establishment" name="establishment"
                                               placeholder="Назва закладу" required='required' maxlength="50"
                                               value='<?= (isset($client['client_establishment']) ? $client['client_establishment'] : '') ?>'>
                                    </div>
                                </div>

                                <div class="form-group form-group-register">
                                    <?= '<br><span>' . ((isset($a_error['person_name'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['person_name'] . '</span></span>' : '') ?>
                                    <?= '<span>' . ((isset($a_data['person_name'])) ? '<span class="ok-span"></span></span>' : '') ?>
                                    <div class="input-group input-group-register">
                                    <span class="input-group-addon addon-register">
                                        <span class="glyphicon glyphicon-user"></span>
                                    </span>
                                        <input type="text"
                                               class="form-control form-for-name reg-page input-register"
                                               id="person_name" name="person_name"
                                               placeholder="Контактна особа"
                                               required='required' maxlength="100"
                                               value='<?= (isset($client['client_person_name']) ? $client['client_person_name'] : '') ?>'>
                                    </div>
                                </div>

                                <div class="form-group form-group-register">
                                    <?= '<br><span>' . ((isset($a_error['phone_number'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['phone_number'] . '</span></span>' : '') ?>
                                    <?= '<span>' . ((isset($a_data['phone_number'])) ? '<span class="ok-span"></span></span>' : '') ?>
                                    <div class="input-group input-group-register">
                                    <span class="input-group-addon addon-register">
                                        <span class="glyphicon glyphicon-phone"></span>
                                    </span>
                                        <input type="text"
                                               class="form-control form_for_phone reg-page input-register"
                                               id="phone_number" name="phone_number" placeholder="Мобільний телефон"
                                               required='required' maxlength="17"
                                               value='<?= (isset($client['client_phone_number']) ? $client['client_phone_number'] : '') ?>'>
                                    </div>
                                </div>

                                <div class="form-group form-group-register">
                                    <?= '<br><span>' . ((isset($a_error['email'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['email'] . '</span></span>' : '') ?>
                                    <?= '<span>' . ((isset($a_data['email'])) ? '<span class="ok-span"></span></span>' : '') ?>
                                    <div class="input-group input-group-register">
                                        <span class="input-group-addon addon-register">@</span>
                                        <input type="email" class="form-control reg-page input-register"
                                               name="email"
                                               id="client_email" placeholder="Email" maxlength="255"
                                               value='<?= (isset($client['client_email']) ? $client['client_email'] : '') ?>'>
                                    </div>
                                </div>

                                <div class="form-group form-group-register">
                                    <?= '<br><span>' . ((isset($a_error['login'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['login'] . '</span></span>' : '') ?>
                                    <?= '<span>' . ((isset($a_data['login'])) ? '<span class="ok-span"></span></span>' : '') ?>
                                    <div class="input-group input-group-register">
                                    <span class="input-group-addon addon-register">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </span>
                                        <input type="text" class="form-control reg-page input-register"
                                               id="client_login"
                                               name="login" placeholder="login" required='required' maxlength="20"
                                               value='<?= (isset($client['client_login']) ? $client['client_login'] : '') ?>'>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group form-group-register">
                                    <label for="image" style="font-size: 14px;">Замінити аватар <span class="question" data-descr="Ви можете вибрати одну фотографію на Вашому ПК, яка буде використовуватися як Ваш особистий аватар. Максимально допустимий розмір зображення: 512 на 512 px. Допустимі розширення: 'png', 'jpeg', 'jpg'. Максимальний розмір - 2 МБ ">?</span></label>
                                    <?= '<br><span>' . ( ( isset($a_error['image']) ) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['image'] . '</span></span>' : '' ) ?>
                                    <?= '<span>' . ( ( isset($a_data['image']) ) ? '<span class="ok-span"></span></span>' : '') ?>
                                    <div class="input-group input-group-register">
                                        <span class="input-group-addon addon-register">
                                            <i class="far fa-image"></i>
                                        </span>
                                        <input type="file" name="image" id="image" class="form-control reg-page input-register form-cont-moz">
                                    </div>
                                </div>

                                <br>
                                <div class="form-group form-group-register">
                                    <label for="password" class="label-register">Пароль</label>
                                    <?= '<br><span>' . ((isset($a_error['password'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['password'] . '</span></span>' : '') ?>
                                    <?= '<span>' . ((isset($a_data['password'])) ? '<span class="ok-span"></span></span>' : '') ?>
                                    <div class="input-group input-group-register">
                                    <span class="input-group-addon addon-register">
                                        <i class="fas fa-key"></i>
                                    </span>
                                        <input type="password" class="form-control reg-page input-register"
                                               id="password"
                                               name="password" required='required' maxlength="32">
                                    </div>
                                </div>

                                <div class="form-group form-group-register">
                                    <label for="password_confirm" class="label-register">Підтвердіть пароль</label>
                                    <?= '<br><span>' . ((isset($a_error['password_confirm'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['password_confirm'] . '</span></span>' : '') ?>
                                    <?= '<span>' . ((isset($a_data['password_confirm'])) ? '<span class="ok-span"></span></span>' : '') ?>
                                    <div class="input-group input-group-register">
                                            <span class="input-group-addon addon-register"><i
                                                        class="fas fa-key"></i></span>
                                        <input type="password" class="form-control reg-page input-register"
                                               id="password_confirm" name="password_confirm" required='required'
                                               maxlength="32">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-xs-12"
                                         style="margin-bottom: 10px;">
                                        <div class="form-group form-group-register">
                                            <input type="submit" class="register-submit client_edit_profile_submit" name='client_profile_edit'
                                                   value="ГОТОВО!">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                <!-- edit user data form -->

                <div class="col-xs-12 col-md-6">
                    <!-- Begin user profile -->
                    <h3>Поточні дані</h3>
                    <div class="box-info text-center user-profile-2">
                        <div class="header-cover">
                        </div>
                        <div class="user-profile-inner">
                            <h4 class="white"><?php echo $client['client_login']; ?></h4>
                            <img src="images/<?php echo (isset($client['client_image']) and $client['client_image'] != null) ? $client['client_image'] : 'avatar.png' ?>" class="img-circle profile-avatar" alt="User avatar">
                            <hr>
                            <table class="">
                                <tr>
                                    <td class="profile-table"><b>Назва:</b></td>
                                    <td class="profile-table-td-two"><?php echo $client['client_name'] ?></td>
                                </tr>
                                <tr>
                                    <td class="profile-table"><b>Заклад:</b></td>
                                    <td class="profile-table-td-two"><?php echo $client['client_establishment'] ?></td>
                                </tr>
                                <tr>
                                    <td class="profile-table"><b>Контактна особа:</b></td>
                                    <td class="profile-table-td-two"><?php echo $client['client_person_name'] ?></td>
                                </tr>
                                <tr>
                                    <td class="profile-table"><b>Телефон:</b></td>
                                    <td class="profile-table-td-two"><?php echo $client['client_phone_number'] ?></td>
                                </tr>
                                <tr>
                                    <td class="profile-table"><b>Email:</b></td>
                                    <td class="profile-table-td-two"><?php echo $client['client_email'] ?></td>
                                </tr>
                                <tr>
                                    <td class="profile-table"><b>Логін:</b></td>
                                    <td class="profile-table-td-two"><?php echo $client['client_login'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <?php } ?>
        </div>
    </div>

    </div>
</section>
<!-- End of Main Content section -->
<!-- Footer -->
<?php include_once "includes_client/client_footer.php"; ?>
<!-- End of footer -->
