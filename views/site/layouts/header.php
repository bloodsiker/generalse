 <!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generalse</title>
  <link rel="shortcut icon" href="/template/site/img/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="/template/site/css/foundation.css">
  <link rel="stylesheet" href="/template/site/css/app.css">
  <link rel="stylesheet" href="/template/site/css/style.css">
  <link rel="stylesheet" href="/template/site/css/fonts.css">
  <link rel="stylesheet" href="/template/site/fonts/foundation-icons/foundation-icons.css">
  <link rel="stylesheet" href="https://daneden.github.io/animate.css/animate.min.css">
  <script src="http://mynameismatthieu.com/WOW/dist/wow.min.js"></script>
  <script>
    new WOW().init();
  </script>

</head>
<body>
<header>
  <div class="row align-middle">
    <div class="medium-2 small-3 text-center columns">
      <a href="/"><img src="/template/site/img/About/CalWhiteLogo.svg" class="logo" alt="Generalse"></a>
    </div>
    <div class="medium-8 medium-offset-2 small-9 text-center columns" >
      <span data-responsive-toggle="responsive-menu" class="button-mobile-menu">
        <button class="menu-icon white" type="button" data-toggle></button>
      </span>
      <nav id="responsive-menu">
       <ul class="menu align-right">
         <li class="home-menu-button"><a href="/">Home</a></li>
         <li><a href="/for_business">For business</a></li>
         <li><a href="/directions">Directions</a></li>
         <li><a href="/career">Career</a></li>
         <li><a href="/contact">Contact</a></li>
         <?php if(isset($_SESSION['user'])):?>
         <li><a href="/adm/crm/orders"><i class="fi-unlock"></i>Cabinet</a>
         <?php else:?>
         <li><a id="open-auth"><i class="fi-torso"></i>&nbsp;Log&nbsp;in</a>
         <?php endif;?>

            <div class="auth contact-us">
              <h4 class="form_title">Login to Umbrella Project</h4>
              <form class="form auth_umbrella" action="" method="post">
                <input type="text" name="login" class="login_umbrella" placeholder="Username" autocomplete="off">
                <input type="password" name="pass" class="password_umbrella" placeholder="Password" >
                <button id="login_umbrella" type="submit">Login</button>
              </form>
            </div>
         </li>
       </ul>
     </nav>
   </div>
   <!-- <div class="medium-3 small-12 text-center columns">
    <form class="search">
      <input id="search" type="text">
      <button><img src="/img/About/search.svg" alt="Search"></button>
    </form>
  </div> -->
</div>
</header>