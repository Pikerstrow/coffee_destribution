<?php
   
if (isset($_GET['edit'])) {

    $machine_id = $_GET['edit'];

    $query = "SELECT * FROM my_coffee_machines WHERE machine_id = {$machine_id}";

    $selected_machines = mysqli_query($link, $query);
    
    if (!$selected_machines) {
        die(set_error('Редагування не відбулося. Помилка запиту до бази даних!'));
    } else {
        while ($row = mysqli_fetch_assoc($selected_machines)) {
            $machine_id = $row['machine_id'];
            $machine_title = $row['machine_title'];
        }
    }
}
?>


<form action="" method="post" class="category-form">
    <div class="form-group">
        <label for="machine_title" style="font-size: 14px;">Редагувати кавоварку
            <span class="question" data-descr="Обов'язкове поле! В даному полі необхідно ввести назву кавоварки,
                                               яка буде відображатися в кабінеті Ваших клієнтів. Максимальна довжина
                                               назви - 50 символів">?</span>
        </label> 
        <!-- Edit Category PHP --> 
        <?php
            if (!is_bool($result = update_coffee_machine())) {
                $error = $result;
            } else {
                header('Location: about_us.php');
            }
        ?>
        <!-- End of Edit Category PHP --> 
        <?= '<br><span>' . ( ( isset($error) ) ? '<span class="error-span"><b>Помилка: </b>' . $error . '</span></span>' : '' ) ?>

        <div class="input-group inp-cats">
            <span class="input-group-addon my-addon addon-register"><i class="fas fa-pencil-alt"></i></span>
            <input type="text" value="<?= (isset($machine_title)) ? $machine_title : '' ?>" name="machine_title"
                   id="machine_title" class="form-control reg-page input-register"
                   class="form-control" required="required" maxlength="50">
            <span class="input-group-btn">
                <button style="height: 45px;" name="edit_machine_subm" class="btn btn-success" type="submit">
                    <i class="fas fa-check-circle  fa-lg"></i>
                </button>
            </span>
        </div>
    </div>
</form>     
