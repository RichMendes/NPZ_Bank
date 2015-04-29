<?php
 	//$root = "http://nyasa.org/";              //THIS NEEDS TO BE CHANGED
	$root = "http://ebz.newpaltz.edu/~n02004019/NPZ_Bank/";
	$home = "$root" . "index.php"; 
	$images = $root . "images/";
	$documents = $root . "documents/";
	$header = __DIR__ . '/header.php';
	$footer = __DIR__ . '/footer.php';
?>
<!DOCTYPE html>
<!-- saved from url=(0051)http://getbootstrap.com/examples/starter-template/# -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <link rel="icon" href="">

    <title>NPZ Bank</title>

    <!-- Bootstrap core CSS -->
    <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="http://getbootstrap.com/examples/starter-template/starter-template.css" rel="stylesheet">
    
    <?php if(isset($_COOKIE['user'])) {
    	$user = unserialize($_COOKIE['user']);
    	$name = $user['username'];
		$customerID = $user['customerID'];
		$address = $user['address']; } 

		if(isset($_GET['signout'])) {
			 unset($_COOKIE['user']);
 			 setcookie('user', '', time() - 3600);
		}
		if(isset($_GET['emptycart'])) {
			 unset($_COOKIE['cart']);
 			 setcookie('cart', '', time() - 3600);
		}?>
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo($home)?>">NPZ Bank</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="<?php echo($home)?>Home"></a></li>
            <li><a href="<?php echo($root)?>registration.php">Registration</a></li>
            <li><a href="<?php echo($root)?>catalog.php">Catalog</a></li>
            <li><a href="<?php echo($root)?>transfers.php">Transfers</a></li>
            <?php if(isset($_COOKIE['cart'])) { ?>
            	<li><a href="<?php echo($root)?>shoppingCart.php">Shopping Cart</a></li>
            <?php } ?>
            <li><a href="<?php echo($root)?>checkout.php">Checkout</a></li>
          </ul>
          <?php if(isset($_COOKIE['user'])) echo("<ul class=\"nav navbar-nav navbar-right\"><li><a href=\"customerInfo.php\">Signed in as: $name </a></li><li><a href=\"index.php?signout=true\">Sign Out</a></li></ul>")?>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
   		</br>
   		</br>
   		</br>
