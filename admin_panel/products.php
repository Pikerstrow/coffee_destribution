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

                    <?php
                        if (isset($_GET['route'])){                            
                            $route = $_GET['route'];
                        } else {
                            $route = '';
                        }
                        
                        switch($route) {                                
                            case 'add_product' : 
                                include_once 'includes_admin/add_product.php';
                                    break;                                
                            case 'edit_product' : 
                                include_once 'includes_admin/edit_product.php';
                                    break;                            
                            default: 
                                include_once 'includes_admin/view_all_products.php';
                                    break;                                
                        }             
                    ?>                             
                </div>
            </div>
            
        </div>
    </section>
    <!-- End of Main Content section -->
    <!-- Footer -->
    <?php include_once "includes_admin/admin_footer.php"; ?>
<!-- End of footer -->


