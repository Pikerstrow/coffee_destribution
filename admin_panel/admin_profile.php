<!-- Header -->
<?php include_once "includes_admin/admin_header.php"; ?>
<!-- End of header -->
<!-- Navigation -->
<?php include_once "includes_admin/admin_navigation.php"; ?>
<!-- End of navigation -->

<?php
// Retrieving data from the DB about client for displaying in inputs like current data
$admin_login = isset($_SESSION['admin']) ? $_SESSION['admin'] : '';
$admin = select_admin_by_login('my_coffee_admins', $admin_login);

if(isset($_POST['admin_profile_edit'])){

    // Receiving data from the form
    $data = mf_get_data(['email', 'login', 'password', 'password_confirm'], 'post');

    // Validating received data
    $form_data = form_validate($data);

    // Receiving data after validation
    $a_data = (isset($form_data['data'])) ? $form_data['data'] : [];
    $a_error = (isset($form_data['errors'])) ? $form_data['errors'] : [];

    //Receiving an image
    $a_data['image'] = mf_get_file('image');


    /*IF CLIENT DOESN'T CHANGES OWN AVATAR*/
    if ('' === $a_data['image']['name'] or empty($a_data['image']['name'])) {
        $a_data['image'] = (isset($admin['admin_image']) and $admin['admin_image'] != null) ? $admin['admin_image'] : 'avatar.png' ;
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
    if (isset($a_data['email']) and $a_data['email'] !== $admin['admin_email']) {
        if (client_email_exists($a_data['email']) or admin_email_exists($a_data['email'])) {
            $a_error['email'] = 'Введена email-адреса вже зареєстрована в системі!';
        }
    }
    if (isset($a_data['login']) and $a_data['login'] !== $admin['admin_login']) {
        if (client_login_exists($a_data['login']) or admin_login_exists($a_data['login'])) {
            $a_error['login'] = 'Введений логін вже зареєстрований в системі!';
        }
    }


    // Updating data in db if array with errors is empty
    if([] == $a_error){

        // deleting password_confirm because there is no such field in db
        unset($a_data['password_confirm']);

        //Hashing the user password
        $a_data['password'] = md5($a_data['password'] . SALT);

        /* IF ADMIN SELECTED NEW AVATAR */
        if (isset($a_data['image']['name']) and ! empty($a_data['image']['name'])) {
            /* Moving file to proper directory */
            $ext = mb_strtolower(pathinfo($a_data['image']['name'], PATHINFO_EXTENSION));
            $file_name = mb_substr(md5($a_data['image']['name'] . microtime()), 0, 10) . '.' . $ext;

            move_uploaded_file(
                $a_data['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/admin_panel/images/' . $file_name
            );
            // Delete previous image
            if ($admin['admin_image'] != null and $admin['admin_image'] != 'avatar.png') {
                unlink('../admin_panel/images/' . $admin['admin_image']);
            }

            /* Inserting data to db */
            //1. removing last elem from data, because it is array (image)
            array_pop($a_data);
            //2. push image name (instead of deleted array) for adding in db
            $a_data['image'] = $file_name;

            // updating data
            if (!is_bool(update_in_db($a_data, 'my_coffee_admins', 'admin_id', $admin['admin_id'], 'admin_'))) {
                $a_error['db_connection'] = update_in_db($a_data, 'my_coffee_admins', 'admin_id', $admin['admin_id'], 'admin_');
            } else {
                set_success('Профіль успішно відредаговано!');
                header("Location:admin_profile.php");
            }
        } else {
            /* IF ADMIN DOESN'T CHANGE PHOTO */
            if (!is_bool(update_in_db($a_data, 'my_coffee_admins', 'admin_id', $admin['admin_id'], 'admin_'))) {
                $a_error['db_connection'] = update_in_db($a_data, 'my_coffee_admins', 'admin_id', $admin['admin_id'], 'admin_');
            } else {
                set_success('Профіль успішно відредаговано!');
                unset($a_data);
                header("Location:admin_profile.php");
            }
        }

    }
}

?>
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
                <?php if (!isset($_GET['edit']) and !isset($_GET['admin_login'])) { ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                            <!-- Begin user profile -->
                            <div class="box-info text-center user-profile-2">
                                <div class="header-cover">
                                </div>
                                <div class="user-profile-inner">
                                    <h4 class="white"><?php echo $admin['admin_login']; ?></h4>
                                    <img src="images/<?php echo (isset($admin['admin_image']) and $admin['admin_image'] != null) ? $admin['admin_image'] : 'avatar.png' ?>" class="img-circle profile-avatar" alt="User avatar">
                                    <hr>
                                    <table class="">
                                        <tr>
                                            <td class="profile-table"><b>Логін:</b></td>
                                            <td class="profile-table-td-two"><?php echo $admin['admin_login'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="profile-table"><b>Email:</b></td>
                                            <td class="profile-table-td-two"><?php echo $admin['admin_email'] ?></td>
                                        </tr>
                                    </table>
                                    <!-- User button -->
                                    <div class="user-button">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <a href="admin_profile.php?edit=true&admin_login=<?php echo $admin['admin_login']; ?>"
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
                        exit(header('Location:admin_profile.php'));
                    } else {
                        $admin_login = isset($_SESSION['admin']) ? $_SESSION['admin'] : '';
                        $admin = select_admin_by_login('my_coffee_admins', $admin_login);
                    }
                    ?>
                    <div class="row">

                        <!-- edit user data form -->
                        <div class="col-sx-12 col-md-6">
                            <h3>Форма для редагування</h3>
                            <form action="" method="POST" role="form" name="registration_form" enctype="multipart/form-data">

                                <div class="form-group form-group-register">
                                    <?= '<span>' . ((isset($a_error['email'])) ? '<span class="error-span"><b>Помилка: </b>' . $a_error['email'] . '</span></span>' : '') ?>
                                    <?= '<span>' . ((isset($a_data['email'])) ? '<span class="ok-span"></span></span>' : '') ?>
                                    <div class="input-group input-group-register">
                                        <span class="input-group-addon addon-register">@</span>
                                        <input type="email" class="form-control reg-page input-register"
                                               name="email"
                                               id="client_email" placeholder="Email" maxlength="255"
                                               value='<?= (isset($admin['admin_email']) ? $admin['admin_email'] : '') ?>'>
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
                                               value='<?= (isset($admin['admin_login']) ? $admin['admin_login'] : '') ?>'>
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
                                            <input type="submit" class="register-submit client_edit_profile_submit" name='admin_profile_edit'
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
                                    <h4 class="white"><?php echo $admin['admin_login']; ?></h4>
                                    <img src="images/<?php echo (isset($admin['admin_image']) and $admin['admin_image'] != null) ? $admin['admin_image'] : 'avatar.png' ?>" class="img-circle profile-avatar" alt="User avatar">
                                    <hr>
                                    <table class="">
                                        <tr>
                                            <td class="profile-table"><b>Email:</b></td>
                                            <td class="profile-table-td-two"><?php echo $admin['admin_email'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="profile-table"><b>Логін:</b></td>
                                            <td class="profile-table-td-two"><?php echo $admin['admin_login'] ?></td>
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
<?php include_once "includes_admin/admin_footer.php"; ?>
<!-- End of footer -->
