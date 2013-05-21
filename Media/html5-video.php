<?php

	/*
		H.264 HTML5 VIDEO SNIPPET
		-Produces a simple video player box from a single video item in a Link List.
		-Browsers that do not support HTML5 or the H.264 codec will gracefully degrade
		 to simply providing a link to download the video.
		-Video embed codes also supported; the dimensions will be set as well.
	*/

	// video dimensions
	$video_width = 304;
	$video_height = 222;

	// get video
	$get_video =
	explode("|||",
	getContent(
	"linklist",
	"display:links",
	"find:welcome-video",
	"show:__name__",
	"show:|||",
	"show:__url__",
	"show:__embed__",
	"show:|||",
	"show:__imageurl width='$video_width' height='$video_height'__",
	"show:|||",
	"show:__description__",
	"noecho"
	));

	$video_name = $get_video[0];
	$video_src = $get_video[1];
	$video_img = $get_video[2];
	$video_desc = $get_video[3];

	// determine video type and h.264 support
	if($video_src){
		$video_type = 'video_h264';
		if(strpos($video_src,'<iframe')!==false || strpos($video_src,'<object')!==false) {
			$video_type = 'video_embed';
			$video_src = preg_replace('/(width)=[\"\'][0-9](.*?)[\"\']/i','width="'.$video_width.'"',$video_src);
			$video_src = preg_replace('/(height)=[\"\'][0-9](.*?)[\"\']/i','height="'.$video_height.'"',$video_src);
		} else {
			$user = $_SERVER['HTTP_USER_AGENT'];
			if(preg_match('/(?i)msie/',$user)){
				preg_match('/(?i)msie(.*?);/',$user,$ie_user_matches);
				$ie_ver = trim($ie_user_matches[1]);
				if($ie_ver < 9){$video_type = 'video_link';}
			}
			if(preg_match('/(?i)firefox/',$user)){$video_type = 'video_link';}
		}
	} else {
		$video_type = 'image_only';
	}

	// video switch
	switch ($video_type) { case 'video_h264':

?>

	<div id="video-wrapper" style="background:#000;" style="width:<?=$video_width?>px;height:<?=$video_height?>px;">
		<video src="<?=$video_src?>" poster="<?=$video_img?>" width="<?=$video_width?>" height="<?=$video_height?>" controls="controls" preload="none">
			<source src="<?=$video_src?>" type="video/mp4"/>
			<p>The video "<?=$video_name?>" cannot be played in this browser. <a href="<?=$video_src?>">Click here to download.</a></p>
		</video>
	</div>

<?php break; case 'video_embed': ?>

	<?=$video_src?>

<?php break; case 'video_link': ?>

	<a href="<?=$video_src?>" title="<?=$video_desc?>">
		<img src="<?=$video_img?>" alt="<?=$video_name?>" width="<?=$video_width?>" height="<?=$video_height?>"/>
	</a>

<?php break; case 'image_only': ?>

	<?php if($video_img){ ?><img src="<?=$video_img?>" alt="<?=$video_name?>" width="<?=$video_width?>" height="<?=$video_height?>"/><?php } ?>

<?php break; default: ?>

	<?php if($video_img){ ?><img src="<?=$video_img?>" alt="<?=$video_name?>" width="<?=$video_width?>" height="<?=$video_height?>"/><?php } ?>

<?php } ?>

