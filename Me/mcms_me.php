<?php
	// This is basic mcms_me.php (Me Page) code to facilitate the ability to edit a user password & to view ecommerce transaction receipts 

	getContent(
	  'member',
	  'display:summary',
	  'findid:' . $MCMS_MID,
	  'restrict:yes',
	  'show:<h1>__fullname__&#8217;s Account</h1>',
	  'nocache'
	);


	// display a relevant message depending on whether the user is viewing a transaction receipt 
	// or if they are viewing the confirmation page after completing an order.
	if ($_GET['view'] == 'orders') {
	  $backLink = '<a href="/me/">&#8249; Back to order list</a> | ';
	}

	if ($_GET['thanks']) {
	  $thanksText = '<p><strong>Thank you for your order! Your order details are below.</strong></p>';
	}

	// displays a links to edit the user's account or logo out 
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

	// grabs a list of links to transactions that have been made on the account. If the user arrives
	// at the page from completing an order, it displays the order confirmation page.
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
	
	
	// optionally display the user's profile page on the sidebar
	getContent(
	  'member',
	  'display:summary',
	  'findid:' . $MCMS_MID,
	  'show:<div id="sidebar_header_image"></div><img src="__pictureurl__" alt="__fullname__" width="210" class="headerImage" />',
	  'nocache'
	);
?>


