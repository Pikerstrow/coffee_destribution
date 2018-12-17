<?php

$admin_login = isset($_SESSION['admin']) ? $_SESSION['admin'] : '';

$admin = select_admin_by_login("my_coffee_admins", $admin_login);

?>
<!-- Header Nav -->
<header class="header">
    <nav>      
        <div id="hamburger_menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="admin_panel_title">
            <span>Панель </span><span> Адміністратора</span>
        </div>
        <div class="admin_profile">
            <ul class="nav navbar-nav admin-nav" role="menu">
                <li class="dropdown">                        
                    <a class="dropdown-toggle" role="button" data-toggle="dropdown"><span class="user_avatar"><img alt="avatar" src="./images/<?php echo (isset($admin['admin_image']) and $admin['admin_image'] != null) ? $admin['admin_image'] : 'avatar.png' ?>"></span> <span class="user_name">&nbsp; <?php echo $admin_login; ?></span> <span class="caret" /></a>
                    <ul class="dropdown-menu">
                        <li><a href="admin_profile.php" class="user-a"><i class="fas fa-user"></i>&nbsp; Профіль</a></li>
                        <li><a href="index.php?logout=true" class="user-a"><i class="fas fa-key"></i>&nbsp; Вийти</a></li>
                    </ul>
                </li>
                <li class="to-site"><a href="../index.php"><i class="fas fa-home fa-2x"></i> </a></li>
            </ul>
        </div>
    </nav>    
</header>
<!-- End of header -->
<!-- Sidebar Nav -->
<aside>
    <div id="sidebar-nav">
        <ul class="sidebar-menu">
            <li><a href="./index.php"><span class="i-wrap"><i class="fas fa-tachometer-alt"></i></span>&nbsp;<span class="nav-a-text"> Головна</span></a></li>


            <li class="dropdown">
                <a href="javascript:;" click="event.preventDefault();" class="drop-toggle">
                   <span class="i-wrap">
                      <i class="fas fa-donate"></i>
                   </span>
                   <span class="nav-a-text">&nbsp; Замовлення
                      <?php
                      $orders = get_quantity_of_orders();
                      echo $orders ? "<span class='label label-danger badge-pill'>$orders</span>" : '';
                      ?>
                   </span><i class="fas fa-angle-right"></i>
                </a>
                <ul class="sidebar_submenu">
                    <li><a href="./orders.php"><span class="i-wrap"><i class="fas fa-hand-holding-usd"></i></span><span class="nav-a-text"></span> Поточні </a></li>
                    <li><a href="./orders.php?route=view_history_orders"><span class="i-wrap"><i class="fas fa-trash-alt"></i></span><span class="nav-a-text"></span> Історія </a></li>
                </ul>
            </li>



            <li><a href="./prod_categories.php"><span class="i-wrap"><i class="fas fa-file-alt"></i></span>&nbsp;<span class="nav-a-text"> Категорії товарів</span></a></li>
            <li class="dropdown">                        
                <a href="javascript:;" click="event.preventDefault();" class="drop-toggle"><span class="i-wrap"><i class="fas fa-boxes"></i></span><span class="nav-a-text">&nbsp; Товари &nbsp;&nbsp;</span><i class="fas fa-angle-right"></i></a>
                <ul class="sidebar_submenu">
                    <li><a href="products.php"><span class="i-wrap"><i class="fas fa-eye"></i></span><span class="nav-a-text"></span> Переглянути </a></li>                        
                    <li><a href="products.php?route=add_product"><span class="i-wrap"><i class="fas fa-plus"></i></span><span class="nav-a-text"></span> Додати товар </a></li>                    
                </ul>
            </li>
            <li class="dropdown">                        
                <a href="javascript:;" click="event.preventDefault();" class="drop-toggle" >
                   <span class="i-wrap">
                      <i class="fas fa-users"></i>
                   </span>
                   <span class="nav-a-text">&nbsp; Клієнти
                      <?php
                      $clients = get_quantity_of_new_clients();
                      echo $clients ? "<span class='label label-danger badge-pill'>$clients</span>" : '';
                      ?>
                   </span><i class="fas fa-angle-right"></i>
                </a>
                <ul class="sidebar_submenu">
                    <li><a href="clients.php?route=new_clients"><span class="i-wrap"><i class="fas fa-user-secret"></i></span><span class="nav-a-text"> Нові </span></a></li>
                    <li><a href="clients.php"><span class="i-wrap"><i class="fas fa-user-friends"></i></span><span class="nav-a-text"> Поточні </span></a></li>
                    <li><a href="clients.php?route=add_new_client"><span class="i-wrap"><i class="fas fa-user-plus"></i></span><span class="nav-a-text"> Додати </span></a></li>
                </ul>
            </li>
            <li>
               <a href="breakdown_massages.php">
                  <span class="i-wrap">
                     <i class="fas fa-bullhorn"></i>
                  </span>
                  <span class="nav-a-text">&nbsp; Несправності
                     <?php
                     $notifications = get_quantity_of_notifications();
                     echo $notifications ? "<span class='label label-danger badge-pill'>$notifications</span>" : '';
                     ?>
                  </span>
               </a>
            </li>
            <li><a href="coffee_machines.php"><span class="i-wrap"><span style="font-size:18px;" class="icon-coffee_machine"></span></span><span class="nav-a-text">&nbsp; Кавоварки</span></a></li>
            <li><a href="write_us_messages.php"><span class="i-wrap"><i class="fas fa-comment"></i></span><span class="nav-a-text">&nbsp; Запитання
                  <?php
                  $messages = get_quantity_of_messages_from_visitors();
                  echo $messages ? "<span class='label label-danger badge-pill'>$messages</span>" : '';
                  ?>
                  </span></a></li>
        </ul>
    </div>
</aside>
<!-- End of sidebar Nav -->

