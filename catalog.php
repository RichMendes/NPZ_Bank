<?php //THIS SHOULD BE AT THE TOP OF EVERY PAGE
	$header = __DIR__ . '/header.php';
	include "$header"; ?>
	
	<div class="row text-center">
		<?php 
		
			if(isset($_POST['itemName'])) {
				if(isset($_COOKIE['cart'])) {
					$cart = $_COOKIE['cart'];
					$cart = stripslashes($cart);	//This is VERY important to have or else decode FAILS!!!
					$cart = json_decode($cart, true);
				}
				else
					$cart = array();
				$itemName = $_POST['itemName'];
				$cart[] = $itemName;
				$cart = json_encode($cart);
				setcookie("cart", $cart, time() + 1800); //lasts 1/2 hour
				echo("<div class=\"panel panel-success\">
						<div class=\"panel-heading\">
							Success!
						</div>
						<div class=\"panel-body\">
							The ");
							echo $itemName; 
							echo (" account has been added to the shopping cart. <br>
							Please <a href=\"");
							echo $root;
							echo("/shoppingCart.php\">click here</a> to go to the shopping cart.
						</div>
				</div>"); 
			}
			$fname = "data/itemRecord.txt";
			$fhandle = fopen("$fname", 'r') or die("Unable to open file!");
			$data = fread($fhandle, filesize($fname));
			fclose($fhandle);
			$items = json_decode($data, true);
			$items = $items['items']; ?>
			
			<?php foreach( $items as $item) { ?>
				<div class="col-md-6 col-md-offset-3">
					<form  role="form" action="catalog.php" method="post"> 
						<h3>The <?php echo $item['itemName'] ?> Account Package</h3>
						<h4>Starts you off with $<?php echo $item['startingFunds'] ?> in your account!</h4>
						<input type="hidden" name="itemName" value="<?php echo $item['itemName'] ?>">
						<button class="btn btn-default">Add to cart</button>
					</form>
				</div>
		<?php } ?>
	</div>
	
<?php include "$footer"; 	//THIS SHOULD BE AT THE BOTTOM OF EVERY PAGE ?>