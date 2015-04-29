<?php //THIS SHOULD BE AT THE TOP OF EVERY PAGE
	$header = __DIR__ . '/header.php';
	include "$header"; ?>
		
	<div class="starter-template">
		<h1>Welcome to the NPZ Bank Website.</h1>
		<p class="lead">Already a Customer? Please sign in <a href="<?php echo($root)?>registration.php">here</a></p>
		<p class="lead">If you are a new user please <a href="<?php echo($root)?>registration.php">click here</a> to sign up for a new account.</p>
	</div>

<?php include "$footer"; 	//THIS SHOULD BE AT THE BOTTOM OF EVERY PAGE ?>