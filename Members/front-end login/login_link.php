<?php 
		
	$get_login = getContent(
   "login",
   "display:popup",
   "noecho",
   "createlink:no",
   "noedit"
   );
  preg_match('/<a(.*?)href\s?=\s?["\'](.*?)["\']/si',$get_login,$login_matches);
  $login_url = trim($login_matches[2]);
  $login_text = 'Log in';
	
	if(!$login_url){
		$login_url = '/me';
		$login_text = 'Account';
	}
	
 	/*	<li><a href="<?=$login_url?>" class="thickbox"><?=$login_text?></a></li>  */
		   
?>
