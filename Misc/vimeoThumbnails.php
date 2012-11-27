<?php
/*
 * This class helps you get a thumbnail from vimeo videos.
 * This is more of a library than a real class. All the functions are static so you can do what
 * you want when you need to. 
 */
class vimeoThumbnails{


  /* get the thumbnail of a vimeo video linked to in an embed code */
  public static function embedToThumbnail($embed){
    $id = vimeoThumbnails::getEmbedId($embed);
    if(!$id){
      return;
    }
    $imageUrl = vimeoThumbnails::getVimeoThumb($id);
    if(!$imageUrl){
      return;
    }
    
    return $imageUrl;
  }


  /* get the thumbnail from vimeo */
  public static function getVimeoThumb($id){
    @$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$id.php"));  
    if($hash[0]['thumbnail_large']){
      return $hash[0]['thumbnail_large']; //aslo thumbnail_medium and thumbnail_small
    }
    return "";
  }
 
  /* Pull the video id out of the embed code */
  public static function getEmbedId($embedCode){

    $embedId = "";

    $start = strpos($embedCode, "src=");
    if(is_numeric($start)){
      $start += 5; //to cover src="
      $end = strpos($embedCode, '"', $start+1);
      if(is_numeric($end)){
        $embedUrl = substr($embedCode, $start, $end - $start);
        $embedMainUrl = array_shift(explode("?", $embedUrl));
        $embedId = array_pop(explode("/", $embedMainUrl));
      }
    }
    
    return $embedId;
    
  }

}


?>