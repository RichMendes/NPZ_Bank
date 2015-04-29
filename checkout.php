<?php //THIS SHOULD BE AT THE TOP OF EVERY PAGE
	$header = __DIR__ . '/header.php';
	include "$header"; ?>
	
	<div class="row text-center">
		<div id="orderSubmitInfo"></div>
		<?php 
			if(isset($_COOKIE['cart'])) {
				if(isset($_COOKIE['user'])) {
					$cart = json_decode($_COOKIE['cart'], true); ?>
					<div class="col-md-6">
						<h3>Please choose Shipping Method and enter your Shipping Address.</h3>
						<form role="form" id="orderForm" action="order.php" method="post">
							<input type="hidden" name="orderPlaced">
							<div class="form-group">
								<label >Shipping Method:</label>
							    <select name="shipMethod"class="form-control">
							        <option value="regular">Regular</option>
							        <option value="express">Express</option>
							        <option value="fake">Fake</option>
							    </select>
							</div>
						    <div class="form-group">
						      <label for="username">Shipping Address:</label>
						      <input type="text" class="form-control" name="shipAddress" placeholder="Enter Shipping Address">
						    </div>
						    <button class="btn btn-default" onclick="orderSubmit()">Submit</button>
						</form>
					</div>
					
					<div class="col-md-6">
						<h3>Items in you shopping cart:</h3>
						<p>
							<ul class="list-group col-md-4 col-md-offset-4">
							<?php foreach ( $cart as $item ) { ?>
									<li class="list-group-item"><h4>The <?php echo $item ?> Account Package</h4></li>
							<?php } ?>
									<li class="list-group-item"<button><a href="checkout.php?emptycart=true">Empty Cart</a></button>
							</ul>
						</p>
					</div>
				<?php } else { ?>
					<h3>You are not signed in.</h3>
					<h4>Please <a href="registration.php">click here</a> to sign in or make an account.</h4>
				<?php }
			}
			else  { ?>
				<h3>Your shopping cart is empty.</h3>
				<h4>Please <a href="catalog.php">click here</a> to visit our selection of accounts that we have available.</h4>
			<?php } ?>
	</div>
	
<?php include "$footer"; 	//THIS SHOULD BE AT THE BOTTOM OF EVERY PAGE ?>