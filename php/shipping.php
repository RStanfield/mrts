<!DOCTYPE html>
<html>
<head>
<title>Shipping Details</title>
<link rel="stylesheet" href="style/mrts.css" />
</head>
<body>
	<h1>Shipping Details</h1>
	<form action="checkout.php" method="post">
		<p>
			<label for="customername">Name:</label><br /> <input type="text"
				name="customername" />
		</p>
		<p>
			<label for="address1">Address 1:</label><br /> <input type="text"
				name="address1" />
		</p>
		<p>
			<label for="address2">Address 2:</label><br /> <input type="text"
				name="address2" />
		</p>
		<p>
			<label for="city">City:</label><br /> <input type="text" name="city" />
		</p>

		<p>
			<label for="stateprovince">State/Province:</label><br /> <input
				type="text" name="stateprovince" />
		</p>
		<p>
			<label for="postcode">Postal/Zip Code:</label><br /> <input
				type="text" name="postcodes" />
		</p>
		<p>
			<label for="specialinstructions">Special Instructions:</label><br />
			<input type="text" name="specialinstructionss" />
		</p>
		<input type="submit" value="Submit Order" />
	</form>
</body>
</html>
