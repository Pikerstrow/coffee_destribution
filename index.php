<!-- Head -->
<?php include_once 'includes/head.php' ?>

<!-- End of head -->
<body>
<!-- PreLoader -->
<div class="preloader" id="preloader">
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
   <!-- Slider carousel -->
   <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-interval="7000" data-ride="carousel">
      <ol class="carousel-indicators">
         <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
         <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
         <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
         <!-- Slide One - Set the background image for this slide in the line below -->
         <div class="carousel-item active" style="background-image: url('images/slider/coffee_maker.jpg')">
            <div class="cover-black"></div>
            <div class="d-flex h-100 align-items-center justify-content-center">
               <div class="text-center col-11 col-sm-10 col-md-8" style="color:white;">
                  <p class="wow fadeInUp pb-23" data-wow-duration="1s" data-wow-iteration="1"><span
                        class="coffee-machine-icon icon-coffee_machine"></span></p>
                  <h2 class="h2-slider wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s" data-wow-iteration="1">
                     Оренда, продаж та обслуговування професійного кавового обладнання в м.Львів!</h2>
                  <p class="slider-p wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s" data-wow-iteration="1">
                     Лише вигідні пропозиції! Лише якісне обладнання! </p>
                  <button href="#equipment" class="equipment_link slider-button wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s"
                          data-wow-iteration="1">Більше
                  </button>
               </div>
            </div>
         </div>
         <!-- Slide Two - Set the background image for this slide in the line below -->
         <div class="carousel-item" style="background-image: url('images/slider/coffee_cup.jpg')">
            <div class="cover-black"></div>
            <div class="d-flex h-100 align-items-center justify-content-center">
               <div class="text-center col-11 col-sm-10 col-md-8" style="color:white;">
                  <p class="wow fadeInUp pb-2" data-wow-duration="1s" data-wow-iteration="1"><span
                        class="icon-coffee_beans coffee-beans-icon"></span></p>
                  <h2 class="h2-slider wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s" data-wow-iteration="1">
                     Натуральна кава в зернах оптом та в роздріб!</h2>
                  <p class="slider-p wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s" data-wow-iteration="1">
                     Кава з відмінними смаковими та ароматичними якостями з найкращих світових плантацій!</p>
                  <button href="#coffee" class="coffee_link slider-button wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s"
                          data-wow-iteration="1">Більше
                  </button>
               </div>
            </div>
         </div>
         <!-- Slide Three - Set the background image for this slide in the line below -->
         <div class="carousel-item" style="background-image: url('images/slider/coffee_emotion.jpg')">
            <div class="cover-black"></div>
            <div class="d-flex h-100 align-items-center justify-content-center">
               <div class="text-center col-11 col-sm-10 col-md-8" style="color:white;">
                  <p class="wow fadeInUp pb-2" data-wow-duration="1s" data-wow-iteration="1"><span
                        class="icon-glad_emotion icon-glad-emotion"></span></p>
                  <h2 class="h2-slider wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s" data-wow-iteration="1">
                     Кава - це не просто напій, це емоції!</h2>
                  <p class="slider-p wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s" data-wow-iteration="1">
                     Подаруйте їх своїм клієнтам із справжньою, відбірною кавою!</p>
                  <button href="#emotion" class="slider-button wow fadeInUp emotion_link" data-wow-duration="1s" data-wow-delay=".1s"
                          data-wow-iteration="1">Більше
                  </button>
               </div>
            </div>
         </div>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
         <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
         <span class="carousel-control-next-icon" aria-hidden="true"></span>
         <span class="sr-only">Next</span>
      </a>
   </div>
   <!-- Slider carousel -->
   <!-- Top nav -->
   <div class="top-menu d-none d-sm-none d-md-none d-lg-block">
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
   <nav class="main-menu">
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
                  <a class="equipment_link" href="#equipment">Кавове обладнання</a>
               </div>
               <div class="align-self-center menu-item-container">
                  <a class="coffee_link" href="#coffee">Кава</a>
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
                  <li><a class="main_link" href="index.php"><span class="oi" style="font-size:17px; padding-right:5px;" data-glyph="home"></span>
                        Головна</a></li>
                  <li><a class="equipment_link" href="index.php#equipment"><span style="font-size: 19px; font-weight: bold;  padding-right:5px;"
                                        class="icon-coffee_machine"></span> Кавове обладнання</a></li>
                  <li><a class="coffee_link" href="index.php#coffee"><span style="font-size: 19px;  padding-right:5px;" class="icon-coffee_beans"></span>
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
<section class="about-us-section">
   <div class="container">
      <div class="logo-container text-center">
         <img src="images/logos/logo_picture.svg" style="width:70px">
      </div>
      <h2 class="text-center h2-section h2-section-first">
         <span class="h2-divider-left"><img width="100" src="images/divider-left.png"></span>
         <span class="h2-span">Про нас</span>
         <span class="h2-divider-right"><img width="100" src="images/divider-right.png"></span>
      </h2>
      <div class="row justify-content-center wow fadeIn" data-wow-duration="1s" data-wow-iteration="1"
           style="padding-top: 30px;">
         <div class="col-12 col-lg-5 order-lg-2">
            <img src="../images/about_us/coffee_beans_about.jpg" class="img-fluid about-us-img">
         </div>
         <div class="col-12 col-lg-7 text-about-us wow fadeIn" data-wow-duration="1s" data-wow-iteration="1">
            <p>
               Усі найкращі рішення, найцікавіші розмови, найтепліші історії розказані за чашкою кави. Запашна і гірка,
               гаряча
               і солодка – джерело натхнення, енергії та настрою.
            </p>
            <p>
               Ми з радістю допоможемо Вам зробити Ваше життя кавовішим: магазин потішить Вас великим вибором якісної
               кави
               (моносорти, купажі та ароматизована кава) як для оптових покупців, так і просто любителів ароматного
               напою...
            </p>
            <p class="d-none d-xl-block">
               Ми можемо встановити та обслуговувати кавоварки у Вашому закладі і гарантуємо, що Ваші відвідувачі
               залишаться задоволеними...
            </p>
            <p class="read_more_button_container">
               <a class="button-about" href="about_us.php">Читати далі...</a>
            </p>
         </div>
      </div>
   </div>
</section>


<section class="first-section" id="emotion">
   <?php include("includes/section_one.php"); ?>
</section>

<section id="paralax_section" class="paralax_section" data-speed="8">
   <div id="coffee" class="cover-black cover-brown"></div>
   <div class="container paralax-section-container">
      <div class="row align-items-center">
         <div class="col text-center">
            <span class="icon-logo-white logo-paralax"></span>
            <h3 class="text-center h3-section h3-section-first ">
               <span class="h2-divider-left"><img width="100" src="images/divider-left-white.png"></span>
               <span class="h2-span shadow"
                     style="background:transparent">Широкий вибір натуральної кави в зернах</span>
               <span class="h2-divider-right"><img width="100" src="images/divider-right-white.png"></span>
            </h3>
         </div>
      </div>
   </div>

</section>
<section class="second-section">
   <?php include("includes/section_two.php"); ?>
</section>

<section class="third-section" id="equipment">
   <?php include("includes/section_three.php"); ?>
</section>

<section class="fourth-section">
   <?php include("includes/section_fourth.php"); ?>
</section>

<section class="five-section" id="write_us">
   <div class="cover-black-contact"></div>
   <div class="container container-five-section">
      <div class="row align-items-center">
         <div class="col text-center">
            <h3 class="text-center h3-section h3-section-first " style="padding-bottom: 7px;">
               <span class="h2-divider-left"><img width="100" src="images/divider-left-white.png"></span>
               <span class="h2-span shadow" style="background:transparent; color:white">KostaCoffee</span>
               <span class="h2-divider-right"><img width="100" src="images/divider-right-white.png"></span>
            </h3>
            <p class="moto-p" data-wow-iteration="1">
               Лише вигідні пропозиції! Лише якісне обладнання!
            </p>
         </div>
      </div>
      <div class="" id="create-error-message-problem"></div>
      <form id="write_us_form" method="post" action="">
         <div id="write-to-us-form-row" class="row justify-content-center" style="margin-top:25px">
            <div class="col-12 col-lg-5 text-center wow fadeInLeft" data-wow-duration="1s" data-wow-iteration="1">
               <div class="form-group mx-auto">
                  <input class="form-control contact-input" type="text" name="name" placeholder="Ваше ім'я" required>
                  <div class="name-help"></div>
                  <div class="valid-feedback"></div>
                  <div class="invalid-feedback text-left"></div>
               </div>
               <div class="form-group mx-auto">
                  <input class="form-control contact-input" type="text" name="email" placeholder="Ваш email" required>
                  <div class="email-help"></div>
                  <div class="valid-feedback"></div>
                  <div class="invalid-feedback text-left"></div>
               </div>
               <div class="form-group mx-auto">
                  <input class="form-control contact-input" type="text" name="ph_number" placeholder="Ваш телефон" required>
                  <div class="phone-help"></div>
                  <div class="valid-feedback"></div>
                  <div class="invalid-feedback text-left"></div>
               </div>
               <div class="form-group">
                  <input type="hidden" name="message_sent" class="form-control form-custom" value="true">
               </div>

            </div>
            <div class="col-12 col-lg-5 text-center wow fadeInRight" data-wow-duration="1s" data-wow-iteration="1">
               <div class="form-group mx-auto" style="height: 100%; padding-bottom:16px;">
                  <textarea class="form-control contact-textarea" name="message" aria-invalid="false" placeholder="Ваше повідомлення..." cols="40"></textarea>
                  <div class="message-help text-left"></div>
               </div>
            </div>
         </div>
         <div class="row justify-content-center">
            <div class="col-12 col-lg-3 wow fadeInUp" data-wow-duration="1s" data-wow-iteration="1">
               <button class="contact-submit" type="submit" id="send_message" name="send_contact_us">Відправити</button>
            </div>
         </div>
      </form>

   </div>
</section>
<!-- to up arrow -->

   <i id="up" class="fas fa-rocket toUp fa-3x"></i>

<!-- to up arrow -->
<footer class="coffee-footer">
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
            <a href="mailto:oleksandrmischuk@gmail.com" title="Send email to developer" class="copyright-link"><span class="copyright">Developed by Oleksandr Mishchuk</a>
         </div>
      </div>
   </div>
</footer>
<script src="js/preloader.js"></script>
</body>
</html>