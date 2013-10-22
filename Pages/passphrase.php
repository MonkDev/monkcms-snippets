<?php
	
	/* 
	
		PASSPHRASE-PROTECTED PAGE
		
		Use to create a cookie containing a password for a particular page ID, 
		in order to protect the page with a low-security restriction.
		
	*/
	
?>



<?php
	
	/* PASSPHRASE-PROTECTION SETTINGS */
	
	// Default
	$access = true;
	$access_message = '';
	
	// Protect the page
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
			$access_message = trim(getContent("section","display:detail","find:p-440479","show:__text__","noecho"));
		}
	}
	
	// Provide access
	if(isset($_COOKIE[$cookie_id]) && $_COOKIE[$cookie_id]==$passphrase){
		$access = true;
	}

?>


<?php /* PASSPHRASE FORM HTML */ ?>

<form id="passphrase-form" method="post">
<input type="text" id="passphrase_try" name="passphrase_try" value="Enter password..." class="clickClear" />
<input type="submit" id="passphrase-submit" class="button" value="Submit" />
</form>


<?php

	/* PASSPHRASE-PROTECTED CONTENT */

	getContent(
		'page',
		'find:' . $_GET['nav'],
		'show:<h1>__custompagetitle__</h1>'
	);
	
	
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






