<?php 

session_start();

if(count($_POST) > 0)
{
	if(!array_key_exists("shoppingcart", $_SESSION))
	{
		$_SESSION["shoppingcart"] = array();
	}
	$item = new stdClass();
	$item->id = $_POST["itemid"];
	$item->qty = $_POST["qty"];
	if(array_key_exists("color", $_POST))
	{
		$item->color = $_POST["color"];
	}
	if(array_key_exists("size", $_POST))
	{
		$item->size = $_POST["size"];
	}
	array_push($_SESSION["shoppingcart"], $item);
}

require_once("../libs/common.php");

$mysqli = getDB();

$stmt = $mysqli->prepare("SELECT id, name, description, image, price FROM items WHERE id = ?");
?>
<!DOCTYPE html>
<html>
<head>
<title>Your Shopping Cart</title>
<link rel="stylesheet" href="style/mrts.css" />
</head>
<body>
	<h1>Your Shopping Cart</h1>
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

		$nTotalCost = 0; // the cost that will go at the bottom of the shopping cart

		foreach($_SESSION["shoppingcart"] as $item)
		{
			$stmt->bind_param("d",$item->id);
			$stmt->execute();
			$itemset = $stmt->get_result();
			$aItem = $itemset->fetch_assoc();

			$nLineCost = $aItem["price"] * $item->qty;

			$nTotalCost += $nLineCost;

			?>

		<tr>
			<td><?php echo $aItem["name"] ?></td>
			<td><?php 
			if(key_exists("size", $item))
			{
				echo $item->size;
			}
			?></td>

			<td><?php 
			if(key_exists("color", $item))
			{
				echo $item->color;
			}
			?></td>

			<td><?php echo $aItem["price"]?></td>

			<td><?php echo $item->qty ?></td>

			<td><?php echo $nLineCost ?></td>

			<td><button id="<?php echo $item->id?>">Remove Item</button>
		
		</tr>

		<?php
		}
		?>
		<tr>
			<td colspan="5"></td>
			<td><?php echo $nTotalCost?></td>
		</tr>

	</table>
	<form action="shipping.php" method="post">
		<input type="submit" value="Check Out" />
	</form>
</body>
</html>
