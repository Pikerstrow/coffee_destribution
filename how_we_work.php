<?php
ob_start();
include_once 'includes/init.php';

?>
<!DOCTYPE html>
<html lang="uk">
<head>
   <meta charset="utf-8">
   <title>KostaCoffee - кава та кавоварки в м. Львів та області!</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/png" href="../images/favicon.png" />
   <link href="https://fonts.googleapis.com/css?family=Alegreya+Sans|Bad+Script|Oswald:300,400|Play|Roboto+Mono|Roboto+Condensed|Caveat" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css?family=Dancing+Script" rel="stylesheet">
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <link rel="stylesheet" href="css/styles.css">
   <link rel="stylesheet" href="css/animate.css">
   <link rel="stylesheet" href="css/all.min.css">
   <link rel="stylesheet" href="css/open-iconic.css">
   <script src="js/jquery.js"></script>
   <script src="js/bootstrap.min.js"></script>
   <script src="js/wow.js"></script>
   <script src="js/main.js"></script>
</head>

<!-- End of head -->
<body style="position: relative">

<!-- PreLoader -->
<div class="preloader">
   <div class="container">
      <div class="row">
         <div class="loader">
            <img width="70" src="images/logos/Logo_cup_white.png">
            KostaCoffee
            <span></span>
            <span></span>
            <span></span>
            <span></span>
         </div>
      </div>
   </div>
</div>
<!-- Navigation -->

<!-- Navigation -->
<header>
   <!-- Top nav -->
   <div class="top-menu d-none d-sm-none d-md-none d-lg-block" style="background-color:rgba(36, 21, 8, .9)">
      <div class="inner-top-menu">
         <div class="align-self-center phone-num"><i class="fas fa-phone fa-sm" style="transform: rotate(105deg)"></i>&nbsp;
            (067) 371-28-06
         </div>
         <div class="align-self-center">
            <?php if (!isset($_SESSION['auth'])): ?>
               <a href="login.php"><span class="oi" style="font-size:10px; padding-right:3px;"
                                         data-glyph="account-login"></span> Вхід</a> <span
                  style="padding: 0px 9px"> | </span>
            <?php else: ?>
               <a href="<?php echo(isset($_SESSION['admin']) ? 'admin_panel/index.php?logout=true' : 'client_panel/index.php?logout=true'); ?>">
                  <span class="oi" style="font-size:10px; padding-right:3px;" data-glyph="account-logout"></span> Вихід
               </a>
               <span style="padding: 0px 9px"> | </span>
               <a href="<?php echo(isset($_SESSION['admin']) ? 'admin_panel/index.php' : 'client_panel/index.php'); ?>">
                  <span class="oi" style="font-size:10px; padding-right:3px;" data-glyph="account-login"></span> В кабінет
               </a>
               <span style="padding: 0px 9px"> | </span>
            <?php endif; ?>
            <a href="register.php">
               <i class="fas fa-user-plus fa-xs" style="padding-right: 2px;"></i> Реєстрація
            </a>
         </div>
      </div>
   </div>
   <!-- Top nav -->
   <!-- Main nav desktop -->
   <nav class="main-menu" style="background-color:rgba(36, 21, 8, .9)">
      <!-- desktop version -->
      <div class="d-none d-sm-none d-md-none d-lg-block">
         <div class="inner-main-menu d-flex">
            <div class="logo align-self-center">
               <a class="logo-link" href="index.php">
                  <img width="35" src="images/logos/Logo_cup_white.png" style="position:relative; top:-3px;"> <span style="font-size:25px;"> KostaCoffee</span>
               </a>
            </div>
            <div class="main-menu-a-block d-flex">
               <div class="align-self-center menu-item-container">
                  <a class="main_link" href="index.php">Головна</a>
               </div>
               <div class="align-self-center menu-item-container">
                  <a class="equipment_link" href="index.php#equipment">Кавове обладнання</a>
               </div>
               <div class="align-self-center menu-item-container">
                  <a class="coffee_link" href="index.php#coffee">Кава</a>
               </div>
               <div class="align-self-center menu-item-container">
                  <a href="how_we_work.php">Як ми працюємо</a>
               </div>
            </div>
         </div>
      </div>
      <!-- desktop version -->

      <!-- mobile devices version -->
      <div id="mobile-menu_container" class="d-block d-lg-none d-flex">
         <div class="logo">
            <a class="logo-link" href="index.php">
               <img width="27" src="images/logos/Logo_cup_white.png" style="position:relative; top:-3px;"> <span> KostaCoffee</span>
            </a>
         </div>
         <div id="hamburger_menu">
            <span></span>
            <span></span>
            <span></span>
            <div id="menu_name" class="text-center" style="width:100%">меню</div>
         </div>
         <div id="main_menu_ul">
            <div id="main_menu_inner">
               <div class="border_left">
               </div>
               <div class="border_top">
               </div>
               <div class="border_right">
               </div>
               <div class="border_bottom">
               </div>
               <ul id="main_menu">
                  <li><a class="main_link" href="index.php"><span class="oi"
                                                                      style="font-size:17px; padding-right:5px;"
                                                                      data-glyph="home"></span>
                        Головна</a></li>
                  <li><a class="equipment_link" href="index.php#equipment"><span
                           style="font-size: 19px; font-weight: bold;  padding-right:5px;"
                           class="icon-coffee_machine"></span> Кавове обладнання</a></li>
                  <li><a class="coffee_link" href="index.php#coffee"><span style="font-size: 19px;  padding-right:5px;"
                                                                           class="icon-coffee_beans"></span>
                        Кава</a></li>
                  <li><a href="how_we_work.php"><i class="far fa-question-circle fa-sm" style="padding-right: 5px;"></i> Як ми
                        працюємо</a></li>
                  <li>
                     <a href="register.php">
                        <i class="fas fa-user-plus fa-sm" style="padding-right: 5px;"></i> Реєстрація
                     </a>
                  </li>
                  <li>
                     <?php if (!isset($_SESSION['auth'])): ?>
                        <a href="login.php"><span class="oi" style="font-size:17px; padding-right:6px;"
                                                  data-glyph="account-login"></span> Вхід</a>
                     <?php else: ?>
                        <a href="<?php echo(isset($_SESSION['admin']) ? 'admin_panel/index.php?logout=true' : 'client_panel/index.php?logout=true'); ?>">
                           <span class="oi" style="font-size:17px; padding-right:6px;"
                                 data-glyph="account-logout"></span> Вихід
                        </a>
                     <?php endif; ?>
                  </li>
                  <li>
                     <?php if (isset($_SESSION['auth'])): ?>
                        <a href="<?php echo(isset($_SESSION['admin']) ? 'admin_panel/index.php' : 'client_panel/index.php'); ?>">
                           <span class="oi" style="font-size:17px; padding-right:6px;"
                                 data-glyph="account-login"></span> В кабінет
                        </a>
                     <?php endif; ?>
                  </li>
                  <ul>
            </div>
         </div>
      </div>
      <!-- mobile devices version -->
   </nav>
</header>
<!-- Page Content -->
<section class="about-us-section" style="padding-top:160px; padding-bottom: 140px; ">
   <div class="container">
      <div class="logo-container text-center" style="margin-bottom: 0;">
         <img src="images/logos/Logo_cup.png" style="width:70px">
      </div>
      <h2 class="text-center h2-section h2-section-first">
         <span class="h2-divider-left"><img width="100" src="images/divider-left.png"></span>
         <span class="h2-span">Як ми працюємо</span>
         <span class="h2-divider-right"><img width="100" src="images/divider-right.png"></span>
      </h2>
      <div class="row justify-content-center wow fadeIn" data-wow-duration="1s" data-wow-iteration="1"
           style="padding-top: 30px;">
         <div class="col-12 col-md-10 col-lg-8 text-justify how-we-work-text">
            <p>
               З моменту заснування, KostaCoffee дотримується стратегії індивідуального підходу до кожного окремого клієнта! Проте,
               основні етапи налагодження та підтримки співпраці, є спільними, і саме на цій сторінці Ви зможете із ними ознайомитися.
            </p>
            <hr>
            <h3 class="text-center">Налагодження співпраці</h3>
            <p>
               Якщо Ваш заклад територіально розміщений в м. Львів або в Львівській області та Ви виявили бажання встановити
               наше кавове обладнання у Вашому закладі - Вам необхідно зателефонувати нам на наш контактний номер телефону, або надіслати лист,
               скориставшись відповідною <a href="index.php#write_us">формою на нашому сайті</a> (в такому випадку ми самі Вам зателефонуємо). Після цього
               Ми домовимося про зустріч, на якій наш менеджер визначить можливі прогнозні об'єми реалізації кави у Вашому закладі
               та підбере відповідне обладнання, яке в найкоротші терміни буде доставлене до Вашого закладу.
            </p>
            <h3 class="text-center">Підтримка співпраці</h3>
            <p>
               Дозамовлення кави та/або аксесуарів (стаканчики, цукор тощо) Ви можете здійснювати любим зручним для Вас способом, попередньо узгодивши
               його із нашим менеджером. Як правило, ми практикуємо два способи:
            <ul class="list-group" style="margin-bottom: 20px;">
               <li class="list-group-item">1. За допомогою телефонного дзвінку</li>
               <li class="list-group-item">2. Через власний кабінет на нашому сайті</li>
            </ul>
               Проте також можливі інші варіанти (обговорюється індивідуально).
            </p>
            <h3 class="text-center">Онлайн замовлення / доставка продукції</h3>
            <p>
               Для того, щоб мати змогу здійснювати дозамовлення продукції та/або аксесуарів через власний кабінет, необхідно пройти
               реєстрацію на нашому сайті. Після чого, для Вас буде створено особистий кабінет, через який Ви зможете дозамовити всю необхідну продукцію,
               надсилати повідомлення про несправності (якщо такі виникнуть) кавоварок, переглядати власну історію замовлень тощо.
            </p>
            <p>
               Як правило, доставка замовленої продукції здійснюється на наступний день після замовлення.
            </p>
            <h3 class="text-center">Осблуговування кавоварок</h3>
            <p>
               У випадку, якщо трапилася якась поломка, Вам необхідно повідомити нашого менеджера (телефоном або через власний кабінет),  і ми
               самостійно усунемо насправність, або замінемо несправну кавоварку на справну.
            </p>
            <p>
               <i>P.S. У випадку, якщо у Вас виникли додаткові запитання - дзвоніть, або <a href="index.php#write_us">пишіть</a> :)</i>
            </p>

         </div>
      </div>
   </div>
</section>

<footer class="coffee-footer" >
   <div class="container">
      <div class="row">
         <div class="col-12 col-md-4 text-copyright-left">
            <p>@ <?php
               $date = new DateTime();
               echo $date->format('Y');
               ?>
               KostaCoffee - all rights reserved!
            </p>
         </div>
         <div class="col-12 col-md-4 text-copyright-center">
            <p>
               Телефон: (067) 371-28-06;
            </p>
            <p>
               Email: kostacoffee@mail.com
            </p>
         </div>
         <div class="col-12 col-md-4 text-copyright-right">
            <a href="mailto:oleksandrmischuk@gmail.com" title="Send email to developer" class="copyright-link"><span
                  class="copyright">Developed by Oleksandr Mishchuk</span></a>
         </div>
      </div>
   </div>
</footer>
<script src="js/preloader.js"></script>
</body>
</html>