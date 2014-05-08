<?php

	/*
	
		TIMED ROTATOR SLIDES
		by Chris Ullyott

		Using Custom Fields assigned to Rotator Slides, slides can individually
		be set to begin, expire, or display between times.

		Users must enter date and time in custom field in this format:

		YYYY-MM-DD 24:00

		http://www.php.net/manual/en/function.strtotime.php
		
		You may add the following function which is a modification of strtotime() to 
		protect against instances where the user might enter a European date format
		by mistake:
		
		function strtotime_US($date){
			// Change to US date pattern if European pattern found
			$date = trim($date);
			$pattern = '/^([0-9]{1,2})(\.|-)([0-9]{1,2})(\.|-)([0-9]{4})/';
			if(preg_match($pattern,$date)){
				$date = preg_replace($pattern,'$5-$1-$3',$date);
			}
			return strtotime($date);
		}
		
		
		-------------------------------------------------------------------------------------------------

		Here are some client instructions:

		-------------------------------------------------------------------------------------------------
		
		
		You can now set times on rotator slides in Ekklesia 360.
		
		In Media > Rotators, there is a Start Time and End Time. The position of the slides will remain as you have set them, however the times can be used to add or remove the slide from the rotation at a later date.
		
		Use the following format:
		
		YYYY-MM-DD 24:00
		
		If you use the "Start time", the slide will not show until that time. If you use the "End Time", the slide will show up until that time. If you use both fields, a window of time will be used. In short, you can use these options independently.
		
		You can also choose to go without a time (YYYY-MM-DD). If you do this, the slide will switch over at midnight.
		
		Your site's time zone is also used for this, which is set in the Admin tab in the CMS.
		
		Let us know if you have any questions about using that. Thanks again!


		-------------------------------------------------------------------------------------------------
		
	*/

?>

	<div class='rotator'>

<?php

	// Set to client timezone (is set to UTC otherwise)
	$timezone = getContent('site','display:detail','show:__timezone__','noecho');
	if($timezone){date_default_timezone_set($timezone);}
	$now = strtotime('now');

	$get_rotator =
	getContent(
	'rotator',
	'display:slides',
	'find:' . $_GET['nav'],
	//'find:homepage-rotator',
	'order:position',
	
	'before_show:<ol class="cycle-slideshow" data-id="__slug__"',
	'before_show: data-cycle-slides=".slide"',
	'before_show: data-cycle-fx="fadeout"',
	'before_show: data-cycle-timeout="__transitionms__"',
	'before_show: data-cycle-speed="500"',
	'before_show: data-cycle-swipe="true"',
	'before_show: data-cycle-auto-height="calc"',
	'before_show: data-cycle-log="false"',
	'before_show:>' . "\n",
	
	'before_show:~SLIDEBODY~',
	
	"image_slide_show:__customstarttime__",
	"image_slide_show:~SLIDEDATA~",
	"image_slide_show:__customendtime__",
	"image_slide_show:~SLIDEDATA~",
	"image_slide_show:<li class='slide' data-position='__position__'>",
	"image_slide_show:<a class='image-slide'",
	"image_slide_show: href='__url__'",
	"image_slide_show:__ifurlnewwindow__target='_blank'",
	"image_slide_show:>",
	"image_slide_show:<img src='__imageurl width='880' height='350'__' width='880' height='350' alt=\"__title nokill='yes'__\"/>",
	"image_slide_show:</a>",
	"image_slide_show:</li>" . "\n",
	"image_slide_show:~SLIDE~",
	
	"video_slide_show:__customstarttime__",
	"video_slide_show:~SLIDEDATA~",
	"video_slide_show:__customendtime__",
	"video_slide_show:~SLIDEDATA~",
	"video_slide_show:<li class='slide' data-position='__position__'>",
	"video_slide_show:<a class='video-slide'",
	"video_slide_show:__ifvideoembed__data-video-type='embed'",
	"video_slide_show:__ifvideohosted__data-video-type='file'",
	"video_slide_show: data-embed-src='__videoembedurl__'",
	"video_slide_show: data-file-url='__videourl__'",
	"video_slide_show: data-image='__videopreviewimageurl__'",
	"video_slide_show: data-title=\"__title nokill='yes'__\"",
	"video_slide_show: href='#'",
	"video_slide_show:>",
	"video_slide_show:<img src='__videopreviewimageurl width='880' height='350'__'  width='880' height='350' alt=\"__title nokill='yes'__\"/>",
	"video_slide_show:</a>",
	"video_slide_show:</li>" . "\n",
	"video_slide_show:~SLIDE~",

	"after_show:~SLIDEBODY~",
	
	"after_show:<div class='cycle-pager'></div>",
	"after_show:</ol><!-- .cycle-slideshow -->",
	"noecho"
	);
		
	$rotator_arr = explode('~SLIDEBODY~',$get_rotator);

	$rotator_before = $rotator_arr[0];
	$rotator_items_arr = explode('~SLIDE~',preg_replace('/(~SLIDE~)*$/','',$rotator_arr[1]));
	$rotator_after = $rotator_arr[2];

	foreach($rotator_items_arr as $slide_item){

		$slide_item_arr = explode('~SLIDEDATA~',$slide_item);
		$start_time = strtotime(trim($slide_item_arr[0]));
		$end_time = strtotime(trim($slide_item_arr[1]));
		$slide = $slide_item_arr[2];

		// start + end time
		if($start_time!='' && $end_time!=''){
			if(($start_time < $now) && ($now < $end_time)){
				$rotator_slides .= $slide;
			}
		}
		// start time only
		elseif($start_time!='' && $end_time==''){
			if($start_time < $now){
				$rotator_slides .= $slide;
			}
		}
		// end time only
		elseif($start_time=='' && $end_time!=''){
			if($now < $end_time){
				$rotator_slides .= $slide;
			}
		}
		// no times given
		elseif($start_time=='' && $end_time=='') {
			$rotator_slides .= $slide;
		}

	}

	echo $rotator_before;
	echo $rotator_slides;
	echo $rotator_after;

?>

	</div><!-- .rotator -->