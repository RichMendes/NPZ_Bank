<?php //THIS SHOULD BE AT THE TOP OF EVERY PAGE
	$header = __DIR__ . '/header.php';
	include "$header"; ?>
	<?php if(isset($_COOKIE['user'])) {?>
		<div class="row text-center">
			<h4>Please scroll down to download the client.</h4>
			<ul class="list-group col-md-4 col-md-offset-4">
				<li class="list-group-item"><h3><b>User Info:</b></h3></li>
				<li class="list-group-item">Your Username is: <?php echo $name; ?></li>
				<li class="list-group-item">Your Address is: <?php echo $address; ?></li>
				<li class="list-group-item">Your CustomerID is: <?php echo $customerID; ?></li>
			</ul>
		</div>
		
		<?php 
		//get the user specific account information
		//put it into an array?
		//then loop through and print it
		$userAccounts = array();
		$fname = "data/accountRecords.txt";
		$fhandle = fopen("$fname", 'r') or die("Unable to open file!");
		$data = fread($fhandle, filesize($fname));
		fclose($fhandle);
		$accounts = json_decode($data, true);
		$found = false;
		foreach($accounts['accounts'] as $account) {
			if($account['customerID'] == $customerID) {
				$found = true;
				if($account['itemID'] == "071001") {$itemID = "Basic";}
				else if($account['itemID'] == "071002") {$itemID = "Business";}
				else if($account['itemID'] == "071003") {$itemID = "Silver";}
				else if($account['itemID'] == "071004") {$itemID = "Gold";}
				else if($account['itemID'] == "071005") {$itemID = "Platinum";}
				$acct = array(
						"accountNumber" => $account['accountNumber'],
						"itemID" => $itemID,
						"funds" => $account['funds']
				);
				$userAccounts[] = $acct;
			}
		}
		foreach($userAccounts as $acct) { //All the accounts the user has. Display some info.{ ?>
		<div class="row text-center">
			<ul class="list-group col-md-4 col-md-offset-4">
				<li class="list-group-item"><h3><b>Account Info:</b></h3></li>
				<li class="list-group-item">Account Number is: <?php echo $acct['accountNumber']; ?></li>
				<li class="list-group-item">Account Type is: <?php echo $acct['itemID']; ?></li>
				<li class="list-group-item">Your available funds are: <?php echo $acct['funds']; ?></li>
			</ul>
		</div>
		<?php } //end foreach ?>
		
		<?php if($found) {  //if there is any accounts then they can download the client ?>
			<div class="row text-center">
				<h4>Please refrence the README.txt for setup information.</h4>
				<h4>To use the client please write a request to bankRequest.dat and then run the Client java program.</h4>
				<button class="btn btn-primary" onclick="window.location.href='Client.zip'">
				  Download Client
				</button>
				</br>
				</br>
				</br>	
			</div>
		<?php } ?>
		
	<?php } else { ?>
	<p class="text-center">
		<h3>You are currently not signed in.</h3>
		<h4>Please <a href="registration.php">click here</a> to sign in or create an account.</h4>
	</p>	
	<?php }?>
	
<?php include "$footer"; 	//THIS SHOULD BE AT THE BOTTOM OF EVERY PAGE ?>