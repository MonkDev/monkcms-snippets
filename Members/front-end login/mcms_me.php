<h2>Your account</h2>

<?php

	// Order history link
	if ($_GET['view'] == 'orders') {
	  $backLink = '<a href="/me/">Order history</a> | ';
	}

	// Edit account + log out links
	getContent(
	  'member',
	  'display:summary',
	  'findid:' . $MCMS_MID,
	  'restrict:yes',
	  'show:<p>',
	  'show:' . $backLink,
	  'show:<a href="__editprofileurl__" title="edit profile" class="thickbox">Edit profile</a>',
	  'show: | <a href="/logout/">Log out</a>',
	  'show:</p>',
	  'nocache'
	);

	if ($_GET['thanks']) {
	  $thanksText = '<p><strong>Thank you for your order! Your order details are below.</strong></p>';
	}

?>

<hr />

<?php

	// Lists transactions that have been made on the account. If the user arrives
	// at the page from completing an order, it displays the order confirmation page.
	$orderHistory = getContent(
	  'member',
	  'display:orders',
	  'find:'.$_GET['orderid'],
	  'nocache',
	  'noecho'
	);

	if (strpos($orderHistory, 'orderHistoryNone') === false) {
	  echo "<h3>Order Details</h3>";
	  echo $thanksText;
	  echo $orderHistory;
	}

?>
