<?php //THIS SHOULD BE AT THE TOP OF EVERY PAGE
	$header = __DIR__ . '/header.php';
	include "$header"; ?>
	
	<?php 
		if(isset($_POST['signIn'])) {
			
			//validate
			$fname = "data/customerRecord.txt";
			$fhandle = fopen("$fname", 'r') or die("Unable to open file!");
			$data = fread($fhandle, filesize($fname));
			$data = json_decode($data, true);
			$users = $data['users'];
			$found = false;
			foreach ($users as $user) {
				if($user['username'] == $_POST['name'] && $user['password'] == $_POST['pwd']) {
					$found = true;
					setcookie("user", serialize($user), time() + 3600); //lasts 1 hour
					$name = $user['username'];
					$customerID = $user['customerID'];
					echo("<div class=\"panel panel-success\">
						<div class=\"panel-heading\">
							Success!
						</div>
						<div class=\"panel-body\">
							Welcome $name, Your CustomerID is: $customerID
						</div>
					</div>"); 
				}
			}
			if(!$found) {
				echo("<div class=\"panel panel-danger\">
						<div class=\"panel-heading\">
							Failure
						</div>
						<div class=\"panel-body\">
							The information you entered is incorrect. Please try again.
						</div>
					</div>"); 
			}
		}
		
		//if($_COOKIE['username'] == "test0")
		
		if(isset($_POST['signUp'])) {
			$fname = "data/customerRecord.txt";
			$fhandle = fopen("$fname", 'r') or die("Unable to open file!");
			$data = fread($fhandle, filesize($fname));
			fclose($fhandle);
			$users = json_decode($data, true);
			$customerID = count($users['users']) + 70001;
			$customerID = sprintf("%06d", $customerID);
			$user = array( "username" =>$_POST['name'],
						   "address" =>$_POST['adr'],
						   "password" =>$_POST['pwd'],
						   "customerID" =>$customerID);
			$users['users'][] = $user;
			$fhandle = fopen("$fname", 'w+') or die("Unable to open file!");
			if($users = json_encode($users)) {
				fwrite($fhandle, $users) or die("Unable to write to file!");
				echo("<div class=\"panel panel-success\">
						<div class=\"panel-heading\">
							Success!
						</div>
  						<div class=\"panel-body\">
    						Congratulations, Your account has been created!
    						Your CustomerID is: $customerID
  						</div>
					</div>");				
			}
			else die("Unable to create JSON object!");
			
			fclose($fhandle);
		}	
	?>
	
	<div class="row">
		<div class="news col-xs-5 col-md-5 text-center">
			<h3>Already have an account? Please sign in below.</h3>
			<form role="form" action="registration.php" method="post">
				<input type="hidden" name="signIn">
			    <div class="form-group">
			      <label for="username">Username:</label>
			      <input type="text" class="form-control" name="name" placeholder="Enter username">
			    </div>
			    <div class="form-group">
			      <label for="pwd">Password:</label>
			      <input type="password" class="form-control" name="pwd" placeholder="Enter password">
			    </div>
			    <button class="btn btn-default">Submit</button>
			</form>
		</div>
		
		<!-- Puts some space betweent he two forms when on larger screens -->
		<div class="news col-md-2"></div>
		
		<div class="news col-xs-5 col-md-5 text-center">
			<h3>New Users please fill in the form to create a new account.</h3>
			<form role="form" action="registration.php" method="post">
				<div class="form-group">
					<input type="hidden" name="signUp" value="true">
				</div>
			    <div class="form-group">
			      <label for="username">Name:</label>
			      <input type="text" class="form-control" name="name" placeholder="Enter username">
			    </div>
			    <div class="form-group">
			      <label for="address">Address:</label>
			      <input type="text" class="form-control" name="adr" placeholder="Enter Address">
			    </div>
			    <div class="form-group">
			      <label for="pwd">Password:</label>
			      <input type="password" class="form-control" name="pwd" placeholder="Enter password">
			    </div>
			    <button class="btn btn-default">Submit</button>
			</form>
		</div>
	</div>

	<div id="regSuccess" class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Modal title</h4>
	      </div>
	      <div class="modal-body">
	        <p>One fine body&hellip;</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

<?php include "$footer"; 	//THIS SHOULD BE AT THE BOTTOM OF EVERY PAGE ?>