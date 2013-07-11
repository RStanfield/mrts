<?php 

require_once("../libs/common.php");

$mysqli = getDB();

$stmt = $mysqli->prepare("SELECT id, name, description, image, price FROM items WHERE id = ?");

$nItemId = 1;

if(array_key_exists("itemid", $_GET))
{
	$nItemId = $_GET["itemid"];
}

$stmt->bind_param("d", $nItemId);

$stmt->execute();

$itemset = $stmt->get_result();

$aItem = $itemset->fetch_assoc();

?>
<!DOCTYPE html>
<html>
<head>
<title>Item Details for <?php echo $aItem["name"] ?>
</title>
</head>
<body>
	<h1>
		Item Details for
		<?php echo $aItem["name"] ?>
		(
		<?php echo $aItem["price"]?>
		)
	</h1>
	<p>
		<?php echo $aItem["description"]?>
	</p>
	<img alt="<?php echo $aItem["name"] ?>"
		src="images/<?php echo $aItem["image"]?>" />
</body>
</html>
