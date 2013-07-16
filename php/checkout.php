<?php 

session_start();

require_once("../libs/common.php");

$mysqli = getDB();

// actually insert order into database

$stmt = $mysqli->prepare("SELECT id, name, description, image, price FROM items WHERE id = ?");

$aOrderItems = array();

$nTotalCost = 0; // the cost that will go at the bottom of the shopping cart
foreach($_SESSION["shoppingcart"] as $item)
{
	$stmt->bind_param("d",$item->id);
	$stmt->execute();
	$itemset = $stmt->get_result();
	$aItem = $itemset->fetch_assoc();

	$nLineCost = $aItem["price"] * $item->qty;

	$nTotalCost += $nLineCost;

	$orderItem = new stdClass();

	$orderItem->id = $item->id;

	$orderItem->price = $aItem["price"];

	$orderItem->qty = $item->qty;

	if(key_exists("color", $item))
	{
		$orderItem->color = $item->color;
	}

	if(key_exists("size", $item))
	{
		$orderItem->size = $item->size;
	}

	$orderItem->name = $aItem["name"];

	array_push($aOrderItems, $orderItem);
}

// begin a database transaction

$mysqli->autocommit(false);

// compile statement

$sSQL = "INSERT INTO orders(customername, address1, address2, city, stateprovince, postcode, " .
		"specialinstructions, totalprice) VALUES (?,?,?,?,?,?,?,?)";

$stmtInsert = $mysqli->prepare($sSQL);

$stmtInsert->bind_param("sssssssd", $_POST["customername"], $_POST["address1"], $_POST["address2"], $_POST["city"],
		$_POST["stateprovince"], $_POST["postcode"], $_POST["specialinstructions"], $nTotalCost);

$stmtInsert->execute();

if($stmtInsert->affected_rows != 1)
{
	// we should have inserted 1 row here
	print_r($stmtInsert);
	$mysqli->rollback();
	return;
}

$nOrderId = $mysqli->insert_id;

// Now we have inserted the order, let's do the order items

$stmtItem = $mysqli->prepare("INSERT INTO order_items (id, order_id, color, size, qty, price) VALUES (?,?,?,?,?,?)");

foreach ($aOrderItems as $orderItem)
{
	$stmtItem->bind_param("ddssdd", $orderItem->id, $nOrderId, $orderItem->color, $orderItem->size, $orderItem->qty,
			$orderItem->price);
	$stmtItem->execute();
	if($stmtItem->affected_rows != 1)
	{
		// we should have inserted 1 row here
		print_r($stmtItem);
		$mysqli->rollback();
		return;
	}
}

// if we get here, then we have both order and order_items
$mysqli->commit();

// clear out shopping cart

$_SESSION["shoppingcart"] = array();

?>
<!DOCTYPE html>
<html>
<head>
<title>Thank You for your Order</title>
<link rel="stylesheet" href="style/mrts.css" />
</head>
<body>
	<h1>
		Thank You for your Order -
		<?php echo $nOrderId ?>
	</h1>
	<table>

		<thead>
			<td>Name</td>
			<td>Size</td>
			<td>Color</td>
			<td>Price</td>
			<td>Quantity</td>
			<td>Total Price</td>
		</thead>
		<?php 

		foreach($aOrderItems as $orderItem)
		{

			?>

		<tr>
			<td><?php echo $orderItem->name ?></td>
			<td><?php 
			if(key_exists("size", $orderItem))
			{
				echo $orderItem->size;
			}
			?></td>

			<td><?php 
			if(key_exists("color", $orderItem))
			{
				echo $orderItem->color;
			}
			?></td>

			<td><?php echo $orderItem->price?></td>

			<td><?php echo $orderItem->qty ?></td>

			<td><?php echo $orderItem->price * $orderItem->qty  ?></td>

		</tr>

		<?php
		}
		?>
		<tr>
			<td colspan="5"></td>
			<td><?php echo $nTotalCost?></td>
		</tr>

	</table>
	<form action="index.php" method="get">
		<input type="submit" value="Continue Shopping" />
	</form>
</body>
</html>
