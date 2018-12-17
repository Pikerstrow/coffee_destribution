<!-- Header -->
<?php include_once "includes_client/client_header.php"; ?>
<!-- End of header -->

<!-- Navigation -->
<?php include_once "includes_client/client_navigation.php"; ?>
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
                    case 'make_order' :
                        include_once 'includes_client/make_order.php';
                        break;
                    default:
                        include_once 'includes_client/orders_history_ajax.php';
                        break;
                }
                ?>
            </div>
        </div>

    </div>
</section>
<!-- End of Main Content section -->
<!-- Footer -->
<?php include_once "includes_client/client_footer.php"; ?>
<!-- End of footer -->
