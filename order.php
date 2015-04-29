<?php //THIS SHOULD BE AT THE TOP OF EVERY PAGE
	$header = __DIR__ . '/header.php';
	include "$header"; ?>
	
	<div class="row text-center">
		<?php
			if(isset($_POST['orderPlaced'])) {
				if(isset($_COOKIE['cart'])) {
					$cart = json_decode($_COOKIE['cart'], true);
					//create an order
					$fname = "data/orderRecord.txt";
					$fhandle = fopen("$fname", 'r') or die("Unable to open file!");
					$data = fread($fhandle, filesize($fname));
					fclose($fhandle);
					$orders = json_decode($data, true);
					$fullOrder = array();
					//create an account order
					$fname = "data/accountRecords.txt";
					$fhandle = fopen("$fname", 'r') or die("Unable to open file!");
					$data = fread($fhandle, filesize($fname));
					fclose($fhandle);
					$accounts = json_decode($data, true);
					foreach( $cart as $item) { //create an account number and order form
						sleep(1);	//ensures that each account gets a different number
						$accountNumber = time();
						if(strcmp($item, "Basic") == 0) {
							$itemID = sprintf("%06d", 71001);
							$availableFunds = 50000;
						}
						else if(strcmp($item, "Business") == 0) {
							$itemID = sprintf("%06d", 71002);
							$availableFunds = 250000;
						}
						else if(strcmp($item, "Silver") == 0) {
							$itemID = sprintf("%06d", 71003);
							$availableFunds = 500000;
						}
						else if(strcmp($item, "Gold") == 0) {
							$itemID = sprintf("%06d", 71004);
							$availableFunds = 750000;
						}
						else if(strcmp($item, "Platinum") == 0) {
							$itemID = sprintf("%06d", 71005);
							$availableFunds = 1000000;
						}
						$orderItem = array( 
								"customerID" => $customerID,
								"accountNumber" => $accountNumber,
								"itemID" => $itemID,
								"availableFunds" => $availableFunds,
								"shipMethod" => $_POST['shipMethod'],
								"shipAddress" => $_POST['shipAddress']);
						$fullOrder[] = $orderItem;
						$newAccount = array(
									"accountNumber" => $accountNumber,
									"customerID" => $customerID,
									"itemID" => $itemID,
									"funds" => $availableFunds);
						$accounts['accounts'][] = $newAccount;
						//create shipping request
						$fnameShip = "data/shippingRequest.dat";
						$shipRequestID = sprintf("%06d", 73000); //Not Sure what the format is for this.
						if(count($orders['orders']) < 10) {
							$shipRequestID = $shipRequestID . "-0" . count($orders['orders']);
						} else {
							$shipRequestID = $shipRequestID . "-" . count($orders['orders']);
						}
						//customerID(whoever initiated it):shipRequestID:itemID:shipMethod:shipAddress:PaymentAccount
						$shipRequest = $customerID . ":" . $shipRequestID . ":" . $itemId . ":" . $_POST['shipMethod'] . ":" . $_POST['shipAddress'] .":0000000000";
						$fhandleShip = fopen("$fnameShip", 'w+') or die("Unable to open file!");
						fwrite($fhandleShip, $shipRequest) or die("Unable to write to file!");
						fclose($fhandleShip) or die("Unable to close file!");
						//shipping request created
						//call the correct ship client
						if($_POST['shipMethod'] == "fake") {
							chdir('FakeShip');
							exec('java ShipClient 2>&1', $response);
							//print_r($response);
							chdir('../');
						} else if($_POST['shipMethod'] == "regular") {
							//I need to get these
						} else if($_POST['shipMethod'] == "express") {
							//I need to get these
						}
					}
					$orders['orders'][] = $fullOrder;
					$orders = json_encode($orders);
					$fname = "data/orderRecord.txt";
					$fhandle = fopen("$fname", 'w+') or die("Unable to open file!");
					fwrite($fhandle, $orders) or die("Unable to write to file!");
					fclose($fhandle) or die("Unable to close file!");
					//order is now created
					//update the accouts page
					$accounts = json_encode($accounts);
					$fname = "data/accountRecords.txt";
					$fhandle = fopen("$fname", 'w+') or die("Unable to open file!");
					fwrite($fhandle, $accounts) or die("Unable to write to file!");
					fclose($fhandle) or die("Unable to close file!");
					//create bank request
					$fname = "data/bankRequest.txt";
					$bankRequestID = sprintf("%06d", 71000); //071000-01    9 digits
					if(count($orders['orders']) < 10) {
						$bankRequestID = $bankRequestID . "-0" . count($orders['orders']);
					} else {
						$bankRequestID = $bankRequestID . "-" . count($orders['orders']);
					}
					$fee = 10;
					//customerID:password:bankRequestID:PayerAccount:PayeeAccount:ammount
					$bankRequest = "070000:1234:" . $bankRequestID . ":0000000000:0000000000" . $fee;
					$fhandle = fopen("$fname", 'w+') or die("Unable to open file!");
					fwrite($fhandle, $bankRequest) or die("Unable to write to file!");
					fclose($fhandle) or die("Unable to close file!");
					//bank request created
					
					//clear cart cookie
					unset($_COOKIE['cart']);
 			 		setcookie('cart', '', time() - 3600);
				?>
					<div class="panel panel-warning">
						<div class="panel-heading">
							Sorry for the long wait.
						</div>
						<div class="panel-body">
							Your order has been created and we are now waiting for the shipper and bank. Please wait just a bit longer for a confirmation.
						</div>
					</div>
					
					<?php printf("<script>location.href='bank.php'</script>");
				 }
			} else { ?>
				<h3>Something went horribly wrong.</h3>
				<h4>Please try to submit another order in a few minutes.</h4>
			<?php } ?>
	</div>
	
<?php include "$footer"; 	//THIS SHOULD BE AT THE BOTTOM OF EVERY PAGE ?>