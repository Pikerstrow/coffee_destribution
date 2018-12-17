<!-- Header -->
<?php include_once "includes_admin/admin_header.php"; ?>
<!-- End of header -->    
    <!-- Navigation -->

<?php
if(isset($_GET['to_archive']) and $_GET['to_archive'] == 'true' and isset($_GET['order_id'])) {

   $order_id = $_GET['order_id'];

   $query = "UPDATE `my_coffee_orders` SET order_status = 1 WHERE order_id = {$order_id}";

   $result = mysqli_query($link, $query);

   check_the_query($result);

   if(mysqli_affected_rows($link) <= 0){
      $error_update_message = "Замовлення, яке Ви відправляєте в архів не існує!";
   }
}
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
                            case 'view_history_orders' :
                                include_once 'includes_admin/view_history_orders.php';
                                    break;
                            default :
                                include_once 'includes_admin/view_current_orders.php';
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


