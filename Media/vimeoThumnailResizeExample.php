<?php

/*This script doesn't execute because there is no where to save the image too. If this seems like
 it might be useful, let me know and I can create a less hardcodey way to sepcific destinations.
 
 The basic principles of this are in place at vineyardcolumbus (8933)
 
 */

echo "You'll have to modify where the resize code saves the image if you acutally want to execute the code.";
die();


include_once($_SERVER['DOCUMENT_ROOT'] . '/inc/vimeoThumbnails.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/inc/imageResize.php');


//vimeo embed code
$embed = '<iframe src="http://player.vimeo.com/video/48502577?byline=0&amp;portrait=0&amp;color=ebebeb" width="670" height="377" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>'; 


//get the vimeo thumbnail from the embed code
$thumb = vimeoThumbnails::embedToThumbnail($embed);


//avoid re-sizeing if we've done it before - the destination is hardcoded, you'll need to modify
//within the class (and here) for your personal use
$filename = array_pop(explode("/", $thumb));
if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/sermonthumbnails/".$filename)){ 
  $new = "/sermonthumbnails/".$filename;
}
else{

  //set mime type
  $mime = "image/jpeg"; //assume all vimeo (they use jpeg)

  //initialize new image
  $myImage = new imageResize($thumb, $mime);
  $new = $myImage->cropAndResize(230, 130); 

}

//wrap it
$new = "<img src='".$new."' alt='My Vimeo Thumbnail' />";  

//done

?>