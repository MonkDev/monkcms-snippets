<?php

	/* -------------------- HTML5 VIDEO -------------------- */

	$video_linklist = 'welcome-video';
	$video_width = 304;
	$video_height = 228;
	$video_zindex = 10;
	$video_preload = 'none'; // (auto, preload, or none)

	/* ----------------------------------------------------- */

	$get_video =
	explode("|||",
	getContent(
	"linklist",
	"display:links",
	"find:$video_linklist",
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

	<div id="video-wrapper" style="background:#000;width:<?=$video_width?>px;height:<?=$video_height?>px;">
		<video src="<?=$video_src?>" poster="<?=$video_img?>" width="<?=$video_width?>" height="<?=$video_height?>" controls="controls" preload="<?=$video_preload?>">
			<source src="<?=$video_src?>" type="video/mp4"/>
			<p>The video "<?=$video_name?>" cannot be played in this browser. <a href="<?=$video_src?>">Click here to download.</a></p>
		</video>
	</div>

<?php break; case 'video_link': ?>

	<a href="<?=$video_src?>" title="<?=$video_desc?>" style="position:relative;display:block;width:<?=$video_width?>px;height:<?=$video_height?>px;" target="_blank">
		<img src="<?=$video_img?>" alt="<?=$video_name?>" width="<?=$video_width?>" height="<?=$video_height?>" style="position:absolute;top:0;left:0;z-index:<?=$video_zindex?>;"/>
		<span style="position:absolute;top:0;left:0;display:block;width:100%;height:100%;background:url(/_img/btn_play-html5.png) no-repeat center center;z-index:<?=$video_zindex+5?>;"></span>
	</a>

<?php break; case 'video_embed': ?>

	<?=$video_src?>

<?php break; case 'image_only': ?>

	<?php if($video_img){ ?><img src="<?=$video_img?>" alt="<?=$video_name?>" width="<?=$video_width?>" height="<?=$video_height?>"/><?php } ?>

<?php } ?>

