<?php $client = select_by_login('my_coffee_clients', $_SESSION['client']); ?>
<!-- Header Nav -->
<header class="header">
    <nav>      
        <div id="hamburger_menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="admin_panel_title">
            <span>Кабінет </span><span>клієнта</span>
        </div>
        <div class="admin_profile">
            <ul class="nav navbar-nav admin-nav" role="menu">
                <li class="dropdown">                        
                    <a class="dropdown-toggle" role="button" data-toggle="dropdown"><span class="user_avatar"><img alt="avatar" src="./images/<?php echo (isset($client['client_image']) and $client['client_image'] != null) ? $client['client_image'] : 'avatar.png' ?>"></span> <span class="user_name">&nbsp; <?php echo $_SESSION['client']; ?></span> <span class="caret" /></a>
                    <ul class="dropdown-menu">
                        <li><a href="client_profile.php" class="user-a"><i class="fas fa-user"></i>&nbsp; Профіль</a></li>
                        <li><a href="index.php?logout=true" class="user-a"><i class="fas fa-power-off"></i>&nbsp; Вийти</a></li>
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
                <a href="javascript:;" click="event.preventDefault();" class="drop-toggle"><span class="i-wrap"><i class="fas fa-shopping-cart"></i></span><span class="nav-a-text">&nbsp; Замовлення &nbsp;&nbsp;</span><i class="fas fa-angle-right"></i></a>
                <ul class="sidebar_submenu">
                    <li><a href="orders.php?route=make_order"><span class="i-wrap"><i class="fas fa-plus"></i></span><span class="nav-a-text"></span> Нове замовлення</a></li>
                    <li><a href="orders.php"><span class="i-wrap"><i class="fas fa-eye"></i></span><span class="nav-a-text"></span> Історія</a></li>
                </ul>
            </li>
            <li><a href="./breakdown_message.php"><span class="i-wrap"><i class="fas fas fa-bullhorn"></i></span>&nbsp;<span class="nav-a-text"> Повідомлення</span></a></li>         
        </ul>
    </div>
</aside>
<!-- End of sidebar Nav -->

