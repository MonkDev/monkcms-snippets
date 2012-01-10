<?php
	// This is basic mcms_me.php (Me Page) code to facilitate the ability to edit a user password & to view ecommerce transaction receipts 

	// Title

	getContent(
	  'member',
	  'display:summary',
	  'findid:' . $MCMS_MID,
	  'restrict:yes',
	  'show:<h1>__fullname__&#8217;s Account</h1>',
	  'nocache'
	);

	// Main Content Code

	if ($_GET['view'] == 'orders') {
	  $backLink = '<a href="/me/">&#8249; Back to order list</a> | ';
	}

	if ($_GET['thanks']) {
	  $thanksText = '<p><strong>Thank you for your order! Your order details are below.</strong></p>';
	}

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

	$orderHistory = getContent(
	  'member',
	  'display:orders',
	  'find:'.$_GET['orderid'],
	  'nocache',
	  'noecho'
	);

	if (strpos($orderHistory, 'orderHistoryNone') === false) {
	  echo "<h2>Order History</h2>";
	  echo $thanksText;
	  echo $orderHistory;
	}	

	// Sidebar Code

	getContent(
	  'member',
	  'display:summary',
	  'findid:' . $MCMS_MID,
	  'show:<div id="sidebar_header_image"></div><img src="__pictureurl__" alt="__fullname__" width="210" class="headerImage" />',
	  'nocache'
	);
?>


