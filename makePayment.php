<?php if(isset($_GET['action']) && isset($_GET['payer']) && isset($_GET['payee']) && isset($_GET['ammount'])) {
	//makePayment.php?action=payment&payer=123456789&payee=123456789&ammount=25
	$payer = $_GET['payer'];
	$payee = $_GET['payee'];
	$ammount = $_GET['ammount'];
	$fname = "data/accountRecords.txt";
	$fhandle = fopen("$fname", 'r') or die("Unable to open file!");
	$data = fread($fhandle, filesize($fname));
	fclose($fhandle);
	$accounts = json_decode($data, true);
	//find the one to subtract from
	$passed = false;
	$found = false;
	foreach ($accounts['accounts'] as &$account) {
			if($account['accountNumber'] == $payer) {
				if($account['funds'] >= $ammount) {//if there is enough money. Remove it!
					foreach ($accounts['accounts'] as &$payeeAccount) {
						if($payeeAccount['accountNumber'] == $payee) {
								$found = true;
								$payeeAccount['funds'] = $payeeAccount['funds'] + $ammount;
								break 1;
						}
					}
					if($found) {
						$account['funds'] = $account['funds'] - $ammount;
						echo("success");
						//update the accounts record
						$accounts = json_encode($accounts);
						$fname = "data/accountRecords.txt";
						$fhandle = fopen("$fname", 'w+') or die("Unable to open file!");
						fwrite($fhandle, $accounts) or die("Unable to write to file!");
						fclose($fhandle) or die("Unable to close file!");
					} else {//handle innsufficent funds
						$found = true;
						echo("failure Incorrect Payee Account: $payee");
					}
				} else {
					//handle innsufficent funds
					$found = true;
					echo("failure Insufficent Funds");
				}
			}
		}
	if($found == false) {
		//handle no such payer account
		echo("failure Incorrect Payer Account: $payer");
	}
	/*if($found) {
		//find the one to add to
		$found = false;
		if($passed) {
			foreach ($accounts['accounts'] as &$account) {
				if($account['accountNumber'] == $payee) {
						$found = true;
						$account['funds'] = $account['funds'] + $ammount;
						break;
				}
			}
		}
		if($found) {
			echo("success");
		} else {//handle innsufficent funds
			echo("failure Incorrect Payee Account: $payee");
		}
		//update the accounts record
		$accounts = json_encode($accounts);
		$fname = "accountRecords.txt";
		$fhandle = fopen("$fname", 'w+') or die("Unable to open file!");
		fwrite($fhandle, $accounts) or die("Unable to write to file!");
		fclose($fhandle) or die("Unable to close file!");
	} else {
		//handle no such payer account
		echo("failure Incorrect Payer Account: $payer");
	}*/
} else { ?>
	<div class="text-center"><h3>You seem to have run into a page you shouldn't have...</h3>
	<h4>Please <a href="index.php">click here</a> to return to the main page.</h4></div>
<?php } ?>