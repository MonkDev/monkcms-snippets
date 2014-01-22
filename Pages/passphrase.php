<?php
	
	/* 
	
	PASSPHRASE-PROTECTED PAGE
	
	Creates a cookie containing a password associated with a particular page ID, 
	in order to protect the page in a low-security fashion.
	
	*/
	
?>



<?php
	
	/* PASSPHRASE SETTINGS */
	
	// Default
	$access = true;
	$access_message = '';
	
	// Protect the page (with custom field: "custompassphrase")
	$passphrase = trim(getContent('page','display:detail','find:'.$_GET['nav'],'show:__custompassphrase__','noecho'));
	$cookie_id = 'passphrase_' . $_GET['nav'];
	if($passphrase){
		$access = false;
		$access_message = '<h4 style="margin-bottom:28px">This page is protected.</h4>';
	}
	
	// Receive form input
	@$passphrase_try = $_POST['passphrase_try'];
	if($passphrase_try){
		if($passphrase_try == $passphrase){
			$access = true;
			setcookie($cookie_id, $passphrase, time()+3600, '/');
		} else {
			$access_message = "<p>Sorry, that's not the correct password.</p>";
		}
	}
	
	// Provide access
	if(isset($_COOKIE[$cookie_id]) && $_COOKIE[$cookie_id]==$passphrase){
		$access = true;
	}

?>



<?php

	// Allows the public to see the page title. 

	getContent(
		'page',
		'find:' . $_GET['nav'],
		'show:<h1>__title__</h1>'
	);

?>



<?php

	/* PROTECTED CONTENT */
	
	if($access){
	
		getContent(
			'page',
			'find:' . $_GET['nav'],
			'show:__text__'
		);
	
	} else {
	
		echo $access_message;
	
		include($_SERVER['DOCUMENT_ROOT'] . '/_inc/passphrase-form.php');
	
	}

?>



<?php 

	/* 
	
	PASSPHRASE FORM HTML 
	Add in: /_inc/passphrase-form.php, or in the page.
		
	*/

?>

<form id="passphrase_form" method="post">
	<input type="text" id="passphrase_try" name="passphrase_try" value="Enter password..." class="clickClear" />
	<input type="submit" id="passphrase_submit" class="button" value="Submit" />
</form>



