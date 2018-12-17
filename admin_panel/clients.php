<!-- Header -->
<?php include_once "includes_admin/admin_header.php"; ?>
<!-- End of header -->    
    <!-- Navigation -->
<?php
confirm_registration();
delete_clients();
?>

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
                            case 'new_clients' :
                                include_once 'includes_admin/new_clients.php';
                                    break;
                            case 'edit_client' :
                                include_once 'includes_admin/edit_client.php';
                                    break;
                            case 'add_new_client' :
                                include_once 'includes_admin/add_new_client.php';
                                    break;
                            default: 
                                include_once 'includes_admin/view_all_clients.php';
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


