<!-- Header -->
<?php include_once "includes_admin/admin_header.php"; ?>
<!-- End of header -->    
    <!-- Navigation -->
    <?php include_once "includes_admin/admin_navigation.php"; ?>
    <!-- End of navigation -->
    
    <!-- Main Content -->
    <section id="main-content">
        <div class="content">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="h2-panel">
                        <span>Кавоварки</span>
                    </h2>
                    <hr>
                    <!-- Add Category -->
                    <div class="col-xs-12 col-sm-4 ">
                        
                        <!-- Add Category PHP -->
                        <?php
                            if (!is_bool($result = add_coffee_machine())){
                                $error = $result;
                            }
                        ?>
                        <!-- End Of Add Category PHP -->
                        
                        <form action="" method="post" class="category-form">
                            <div class="form-group">
                                <label for="category_title" style="font-size: 14px;">Додати кавоварку <span class="question"
                                       data-descr="Обов'язкове поле! В даному полі необхідно ввести назву кавоварки,
                                                   яка буде відображатися в кабінеті Ваших клієнтів. Максимальна довжина 
                                                   назви - 50 символів">?</span>
                                </label>      
                                <?= '<br><span>' . ( ( isset($error ) ) ? '<span class="error-span"><b>Помилка: </b>' . $error . '</span></span>' : '' ) ?>
                                
                                <div class="input-group inp-cats">
                                    <span class="input-group-addon my-addon addon-register">
                                        <span class="icon-coffee_machine"></span>
                                    </span>
                                    <input type="text" name="machine_title" id="machine_title" class="form-control reg-page input-register"
                                           class="form-control" required="required" maxlength="50">
                                    <span class="input-group-btn">
                                        <button style="height: 45px;" name="add_machine_subm" class="btn btn-success" type="submit">
                                            <i class="fas fa-plus-circle fa-lg"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <hr> 
                        </form>                        
                        <!-- Edit category -->                       
                            <?php
                                if (isset($_GET['edit'])) {
                                    include_once 'includes_admin/edit_coffee_machine.php';
                                }
                            ?> 
                        <!-- End of edit category -->                        
                    </div>
                    
                    <div class="col-xs-12 col-sm-8 ">
                        <h4>Наявні кавоварки</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-admin">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Назва кавоварки</th>
                                        <th style="text-align: center;" colspan='2'>Правки</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php select_all_coffee_machines_table() ?>
                                        <?php delete_coffee_machine() ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </section>
    <!-- End of Main Content section -->
<!-- Footer -->
<?php include_once "includes_admin/admin_footer.php"; ?>
<!-- End of footer -->
