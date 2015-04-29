<?php //THIS SHOULD BE AT THE TOP OF EVERY PAGE
	$header = __DIR__ . '/header.php';
	include "$header"; ?>
	
	<div class="row text-center">
		<h3>Please <a href="checkout.php">click here</a> to check out.</h3>
		<br>
		<h3>Items in you shopping cart:</h3>
			<p>
				<ul class="list-group col-md-4 col-md-offset-4">
				<?php 
					$cart = json_decode($_COOKIE['cart'], true);
					foreach ( $cart as $item ) { ?>
						<li class="list-group-item"><h4>The <?php echo $item ?> Account Package</h4></li>
				<?php } ?>
						<li class="list-group-item"<button><a href="checkout.php?emptycart=true">Empty Cart</a></button>
				</ul>
			</p>
	</div>
						
<?php include "$footer"; 	//THIS SHOULD BE AT THE BOTTOM OF EVERY PAGE ?>