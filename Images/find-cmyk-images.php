<?php

	/*

	CHECK SITE FOR CMYK IMAGES

	Scans a given media directory for CMYK images.

	*/

	// Set media directory
	$media_dir = '/mediafiles/';

	$live_dir = 'http://' . $_SERVER['HTTP_HOST'] . $media_dir;
	if(substr($media_dir, -1) != "/") $media_dir .= "/";
	$image_path = $_SERVER['DOCUMENT_ROOT'] . $media_dir;
	$image_array = glob("$image_path*.{jpg,jpeg,gif,png}", GLOB_BRACE);
	$cmyk_list = '';
	$cmyk_counter = 0;
	foreach($image_array as $image){
		$image_info = getimagesize($image_dir . $image);
		if($image_info['channels']==4){
			$image_path_arr = explode('/',$image);
			$filename = $image_path_arr[(count($image_path_arr)-1)];
			$cmyk_counter++;
      	$cmyk_list .= '<div style="margin-bottom:25px;padding:10px;border-bottom:1px solid #ddd;">';
      	$cmyk_list .= '<p>' . $cmyk_counter . ' - ' . '<a href="'.$live_dir . $filename.'">' . $live_dir . $filename . '</a></p>';
      	$cmyk_list .= '<p>' . '<a href="'. $live_dir . $filename .'"><img src="'. $live_dir . $filename .'" height="75" style="display:block;"/></a></p>';
      	$cmyk_list .= '</div>';
      	$cmyk_list .= "\n";
      }
	}
	echo '<h2>' . $cmyk_counter . ' CMYK images found in your site\'s media directory.' . '</h2>';
	echo '<p>It is recommended to use only RGB images on the web.</p>';
	echo '<hr>';
	echo $cmyk_list;

?>