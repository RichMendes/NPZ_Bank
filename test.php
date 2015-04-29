<?php //THIS SHOULD BE AT THE TOP OF EVERY PAGE
	$header = __DIR__ . '/header.php';
	include "$header";?>
	
<?php
/* To update the items list:
	$fname = "itemRecord.txt";
	$fhandle = fopen("$fname", 'r') or die("Unable to open file!");
	$data = fread($fhandle, filesize($fname));
	fclose($fhandle);
	$data = json_decode($data, true);
	echo "the current data is: ", $data;
	$items = array( "items" => array( array(
								"itemID" => "071001",
								"itemName" => "Basic",
								"startingFunds" => 500),
								array(
								"itemID" => "071002",
								"itemName" => "Business",
								"startingFunds" => 2500),
								array(
								"itemID" => "071003",
								"itemName" => "Silver",
								"startingFunds" => 5000),
								array(
								"itemID" => "071004",
								"itemName" => "Gold",
								"startingFunds" => 7500),
								array(
								"itemID" => "071005",
								"itemName" => "Platinum",
								"startingFunds" => 10000)));
	echo "the items array looks like:";
	print_r($items);
	$items = json_encode($items);
	print_r($items);
	$fhandle = fopen("$fname", 'w+') or die("Unable to open file!");
	fwrite($fhandle, $items) or die("Unable to write to file!"); */
	
	/*echo "doing some stuff in the background... ";
	$count = 0;
	while($count<5){
		echo "doing some stuff in the background... ";
		sleep(1);
		$count++;
	}*/ ?>
	<!--<div id="orderSubmitInfo"></div>
	<button onclick="orderSubmit()">Make a change</button>-->
	
	<?php
			/*$fname = "orderRecord.txt";		//add to orderRecord
			$fhandle = fopen("$fname", 'r') or die("Unable to open file!");
			$data = fread($fhandle, filesize($fname));
			fclose($fhandle);
			$orders = json_decode($data, true);
			//foreach( $cart as $item) { //create an account number and order form
				//sleep(1);	//ensures that each account gets a different number
				$accountNumber = time();
				$orderItem = array( "accounts" => array( 
						array(
						"customerID" => "071001",
						"accountNumber" => "Basic",
						"itemID" => 500,
						"availableFunds" => 500,
						"shipMethod" => "basic",
						"shipAddress" => "whatever"),
						array(
						"customerID" => "071001",
						"accountNumber" => "Basic",
						"itemID" => 500,
						"availableFunds" => 500,
						"shipMethod" => "basic",
						"shipAddress" => "whatever")));
				//$orders[] = $orderItem;
			//}
			$orders = json_encode($orderItem);
			$fhandle = fopen("$fname", 'w+') or die("Unable to open file!");
			fwrite($fhandle, $orders) or die("Unable to write to file!");
			$fclose($fhandle);*/	
?>

<div class="panel panel-warning">
						<div class="panel-heading">
							Sorry for the long wait.
						</div>
						<div class="panel-body">
							Your order has been created and we are now waiting for the shipper and bank. Please wait just a bit longer for a confirmation.
						</div>
					</div>
					<?php //printf("<script>location.href='bank.php'</script>");?>
					
					<?php 
						chdir('FakeShip');
						$shipResponse = exec('java ShipClient');
						printf($response);
					?>
					
<?php include "$footer"; 	//THIS SHOULD BE AT THE BOTTOM OF EVERY PAGE ?>