<?php //THIS SHOULD BE AT THE TOP OF EVERY PAGE
	$header = __DIR__ . '/header.php';
	include "$header"; ?>
	
	<?php if(isset($_POST['transferPlaced'])) {
		$bank = $_POST['bank'];
		if($bank == "npz") {
			//create bank request
			$fname = "data/bankRequest.txt";
			$bankRequestID = sprintf("%06d", 71000); //071000-01    9 digits
			if(count($orders['orders']) < 10) {
				$bankRequestID = $bankRequestID . "-0" . count($orders['orders']);
			} else {
				$bankRequestID = $bankRequestID . "-" . count($orders['orders']);
			}
			//customerID:password:bankRequestID:PayerAccount:PayeeAccount:ammount
			$bankRequest = $customerID . ":" . "PaSsWoRd" . ":" . $bankRequestID . ":" . $_POST['userAccount'] . ":" . $_POST['payeeAccount'] . ":". $_POST['ammount'];
			$fhandle = fopen("$fname", 'w+') or die("Unable to open file!");
			fwrite($fhandle, $bankRequest) or die("Unable to write to file!");
			fclose($fhandle) or die("Unable to close file!");
			//bank request created
			//call bank client
			//handle response
			//makePayment.php?action=payment&payer=123456789&payee=123456789&ammount=25
			$request = "http://ebz.newpaltz.edu/~n02004019/NPZ_Bank/makePayment.php?action=payment&payer=" . $_POST['userAccount'] . "&payee=" . $_POST['payeeAccount'] . "&ammount=" . $_POST['ammount'];
			$response = file_get_contents($request);
			
			if($response == "success") {
				echo("<div class=\"panel panel-success\">
							<div class=\"panel-heading\">
								Success!
							</div>
	  						<div class=\"panel-body\">
	    						Congratulations, Your transfer is complete!
	  						</div>
						</div>");
			} else {
				echo("<div class=\"panel panel-danger\">
							<div class=\"panel-heading\">
								Failure!
							</div>
	  						<div class=\"panel-body\">");
	    		echo($response);				
	  					echo("</div>
						</div>");
			}
		} else if($bank == "other") {
			
			//INCOMPLETE!!!
			//handle the other bank situation
			
		} else if($bank == "fake") {
			$fname = "FakeBank/Bank-Qc.dat";
			$bankRequestID = sprintf("%06d", 71000); //071000-01    9 digits
			if(count($orders['orders']) < 10) {
				$bankRequestID = $bankRequestID . "-0" . count($orders['orders']);
			} else {
				$bankRequestID = $bankRequestID . "-" . count($orders['orders']);
			}
			//customerID:password:bankRequestID:PayerAccount:PayeeAccount:ammount
			$bankRequest = $customerID . ":" . "PaSsWoRd" . ":" . $bankRequestID . ":" . $_POST['userAccount'] . ":" . $_POST['payeeAccount'] . ":". $_POST['ammount'];
			$fhandle = fopen("$fname", 'w+') or die("Unable to open file!");
			fwrite($fhandle, $bankRequest) or die("Unable to write to file!");
			fclose($fhandle) or die("Unable to close file!");
			chdir('FakeBank');
			exec('java BankClient 2>&1', $response);
			//print_r($response);
			chdir('../');
			echo("<div class=\"panel panel-success\">
							<div class=\"panel-heading\">
								Success!
							</div>
	  						<div class=\"panel-body\">
	    						Congratulations, Your transfer is complete!
	  						</div>
						</div>");
		}
	}?>
	
	<div class="row text-center">
		<?php 
			if(isset($_COOKIE['user'])) { 
				$userAccounts = array();
				$fname = "data/accountRecords.txt";
				$fhandle = fopen("$fname", 'r') or die("Unable to open file!");
				$data = fread($fhandle, filesize($fname));
				fclose($fhandle);
				$accounts = json_decode($data, true);
				foreach($accounts['accounts'] as $account) {
					if($account['customerID'] == $customerID) {
						if($account['itemID'] == "071001") {$itemName = "Basic";}
						else if($account['itemID'] == "071002") {$itemName = "Business";}
						else if($account['itemID'] == "071003") {$itemName = "Silver";}
						else if($account['itemID'] == "071004") {$itemName = "Gold";}
						else if($account['itemID'] == "071005") {$itemName = "Platinum";}
						$acct = array(
								"accountNumber" => $account['accountNumber'],
								"itemID" => $account['itemID'],
								"itemName" => $itemName,
								"funds" => $account['funds']
						);
						$userAccounts[] = $acct;
					}
				}?>
				<div class="col-md-6 col-md-offset-3">
				<h3>Please fill out the form to transfer funds from one account to another</h3>
				<form role="form" id="transferForm" action="transfers.php" method="post">
							<input type="hidden" name="transferPlaced">
							<div class="form-group">
								<label >Your Account to send money from:</label>
							    <select name="userAccount"class="form-control">
							        <?php foreach($userAccounts as $account) { ?>
							        	<option value="<?php echo($account['accountNumber']) ?>"><?php print $account['accountNumber'] . " - " . $account['itemName'] . " - " . $account['funds']?></option>
							        <?php } ?> 
							    </select>
							</div>
							<div class="form-group">
								<label >The bank the other account is at:</label>
							    <select name="bank"class="form-control">
							    	<option value="npz">NPZ Bank</option>
							    	<option value="other">Other Bank</option>
							    	<option value="fake">Fake Bank</option>
							    </select>
							</div>
						    <div class="form-group">
						      <label>Account Number to send money to:</label>
						      <input type="text" class="form-control" name="payeeAccount" placeholder="Enter Account Number to send money to">
						    </div>
						    <div class="form-group">
						      <label>Ammount to send:</label>
						      <input type="number" class="form-control" name="ammount" placeholder="Ammount to send">
						    </div>
						    <button class="btn btn-default" onclick="orderSubmit()">Submit</button>
						</form> 
			<?php } //end if(isset($_COOKIE['user'])) 
				else { ?>
					<h3>You need to be signed in to place a transfer.</h3>
					<h3>Please <a href="<?php echo($root)?>/registration.php">click here</a> to sign in.</h3>
				<?php } //end else?>
			</div>
<?php include "$footer"; 	//THIS SHOULD BE AT THE BOTTOM OF EVERY PAGE ?>			