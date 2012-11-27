<?php
/*
 * This class handles the cropping and resizing of an image. It was designed to handle the resizing
 * of a vimeo video and has not been thoroughly tested.
 *
 * TODO - the saving destination is hardcoded
 *      - add more features if you like
 *
 * It has one public function, cropAndResize and needs to be initialized first like so...  
 * $myImage = new imageResize($thumb, $mime);
 * $new = $myImage->cropAndResize(230, 130); 
 * 
 */
class imageResize{

  private $file = "";  //fullpath + name
  private $filename = ""; //just the name, no path
  private $type = ""; //mime type
  private $og = ""; //the working image
  
  
  /*
   * Constructor
   * @param $file_with_path - the full path to the file
   * @param $type - the mime type of the image
   */
  function __construct($file_with_path, $type = "image/jpeg"){
    $this->file = $file_with_path;
    $this->filename = array_pop(explode("/", $file_with_path));
    $this->type = $type;
    $this->og = $this->getImage();    
  }
  
  /*
   * @param $x - the desired x dimension of the new image
   * @param $y - the desired y dimension of the new image
   *
   * @return - the destination (including filename) of the new image
   */
  public function cropAndResize($x, $y){
  
    $destination = $_SERVER['DOCUMENT_ROOT'] . "/sermonthumbnails/".$this->filename;
    
    //update our base image with an image in the desired ratio
    $this->og = $this->crop($x, $y);
    
    //resize the image
    $this->og = $this->resize($x, $y);
    
    //save the image
    $this->saveImage($destination, $this->og);
    
    //return the destination
    return "/sermonthumbnails/".$this->filename;
    
  }
  
  /*
   * Crops an image from the center
   * @param $x - number of pixels on the x access to cut
   * @param $y - number of pixels on the y access to cut
   * 
   * @return - the cropped image (resource) the largest possible x by y ratio (not to exceed the size of the 
               original image)
   */
  private function crop($x, $y){
    
    $ogx = imagesx($this->og);
    $ogy = imagesy($this->og);
    
    //if the image matches don't bother
    if($ogx == $x && $ogy == $y){
      return $this->og;
    }

    //check if we need to lower our target dimensions
    if($ogx < $x || $ogy < $y){
      //our desired image is larger than our original image
      
      //lower our targets but keep the desired ratio
      if($x > $y){
        //portrait 
        $y = floor(($y * $ogx)/$x);
        $x = $ogx;
        
      }
      else{
        //landscape
        $x = floor(($x * $ogy)/$y);
        $y = $ogy;
      }
    }
    
    //get the largest cropping demensions (in the correct ratio)
    
    //shoot to get the most of x
      $targetX = $ogx;
      $targetY = floor(($ogx * $y) / $x);
      
    //is there room for y?
    if($targetY > $y){
      //oops too much x, let's see how much y we can get and go from there
      $targetY = $ogy;
      $targetX = floor(($ogy * $x) / $y);
    }
    
    //get margins so we can crop from the center
    $xMargin = floor(($ogx - $targetX) / 2);
    $yMargin = floor(($ogy - $targetY) / 2);
    
    //crop and save
    $cropped = imagecreatetruecolor($targetX, $targetY);
    imagecopy($cropped, $this->og, 0, 0, $xMargin, $yMargin, $targetX, $targetY);
    
    return $cropped;

  }

  /*
   * Resize - resizes the image to the smaller of these maximum dimensions. The image ratio is not
              changed.
   * @param $maxw - the maximum allowed width of the new image.
   * @param $maxy - the maximum allowed height of the new image.
   *
   * @return - the resized image (resource) in the same ratio as before
   */
  private function resize($maxw = 230, $maxh = 130){
    
    $ogx = imagesx($this->og);
    $ogy = imagesy($this->og);
    
    if($ogx > $maxw || $ogy > $maxh){
    
      if($ogx < $ogy){
        $newy = $maxh;
        $newx = floor($ogx * ($newy / $ogy));
      }
      else{
        $newx = $maxw;
        $newy = floor($ogy * ($newx / $ogx));    
      }
      $resized = imagecreatetruecolor($newx, $newy);
      imagecopyresized($resized, $this->og, 0, 0, 0, 0, $newx, $newy, $ogx, $ogy);
      
      return $resized;

    }
    
    return $this->og;
    
  }
  
  /*
   * saveImage - saves an image resource
   *
   * @param $destination - where the file should be saved
   * @param $new - the image (resource) to use to save the image
   *
   */
  private function saveImage($destination = null, $new = null){
  
    if(!$destination){
      return false;
    }
   
    if($this->type == "image/jpeg"){
      imagejpeg($new, $destination, 100);
    }
    elseif($this->type == "image/png"){
      imagepng($new, $destination, 100);
    }
    
  }
  
  /*
   * getImage - creates an image (resource) from the file used during initailization
   *
   * @return - the image (resource) created from the image file.
   */
  private function getImage(){
    
    if($this->type == "image/jpeg"){
      $og = imagecreatefromjpeg($this->file);
    }
    elseif($this->type == "image/png"){
      $og = imagecreatefrompng($this->file);
    }
    else{
      return false;
    }

    return $og;
  }

}
