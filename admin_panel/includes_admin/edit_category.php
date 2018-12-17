<?php
   
if (isset($_GET['edit'])) {

    $cat_id = $_GET['edit'];

    $query = "SELECT * FROM my_coffee_prod_categories WHERE category_id = $cat_id";

    $selected_categories = mysqli_query($link, $query);
    
    if (!$selected_categories) {
        die(set_error('Редагування не відбулося. Помилка запиту до бази даних!'));
    } else {
        while ($row = mysqli_fetch_assoc($selected_categories)) {
            $cat_id = $row['category_id'];
            $cat_title = $row['category_title'];
        }
    }
}
?>


<form action="" method="post" class="category-form">
    <div class="form-group">
        <label for="category_title" style="font-size: 14px;">Редагувати категорію <span class="question" 
                                                                                    data-descr="Обов'язкове поле! В даному полі необхідно ввести назву категорії, 
                                                                                    яка буде відображатися в кабінеті Ваших клієнтів. Максимальна довжина 
                                                                                    назви - 50 символів">?</span>
        </label> 
        <!-- Edit Category PHP --> 
        <?php
            if (!is_bool($result = update_product_category())) {
                $error = $result;
            } else {
                header('Location: prod_categories.php');
            }
        ?>
        <!-- End of Edit Category PHP --> 
        <?= '<br><span>' . ( ( isset($error) ) ? '<span class="error-span"><b>Помилка: </b>' . $error . '</span></span>' : '' ) ?>

        <div class="input-group inp-cats">
            <span class="input-group-addon my-addon addon-register"><i class="fas fa-pencil-alt"></i></span>
            <input type="text" value="<?= (isset($cat_title)) ? $cat_title : '' ?>" name="category_title" 
                   id="category_title" class="form-control reg-page input-register"
                   class="form-control" required="required" maxlength="50">
            <span class="input-group-btn">
                <button style="height: 45px;" name="edit_category_subm" class="btn btn-success" type="submit">
                    <i class="fas fa-check-circle  fa-lg"></i>
                </button>
            </span>
        </div>
    </div>
</form>     
