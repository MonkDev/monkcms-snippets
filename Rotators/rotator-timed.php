<?php

	/*
		TIMED ROTATOR SLIDES

		Using Custom Fields assigned to Rotator Slides, slides can individually
		be set to begin, expire, or display between times.

		User must enter date and time in custom field in a proper format, like:
		2013/3/1 18:00
	*/

?>

  <div class='rotator'>

<?php

	$get_rotator =
	getContent(
	'rotator',
	'display:slides',
	//'find:' . $_GET['nav'],
	'find:demo-rotator',
	'order:position',
	'before_show:<ol class="cycle-slideshow" data-id="__slug__"',
	'before_show: data-cycle-slides=".slide"',
	'before_show: data-cycle-fx="fadeout"',
	'before_show: data-cycle-timeout="__transitionms__"',
	'before_show: data-cycle-speed="500"',
	'before_show: data-cycle-swipe="true"',
	'before_show: data-cycle-auto-height="calc"',
	'before_show: data-cycle-log="false"',
	'before_show:>',
	'before_show:#SLIDEBODY#',
	"image_slide_show:__customstarttime__",
	"image_slide_show:|SLIDEDATA|",
	"image_slide_show:__customendtime__",
	"image_slide_show:|SLIDEDATA|",
	"image_slide_show:<li class='slide' data-position='__position__'>",
	"image_slide_show:<a class='image-slide'",
	"image_slide_show: href='__url__'",
	"image_slide_show:__ifurlnewwindow__target='_blank'",
	"image_slide_show:>",
	"image_slide_show:<img src='__imageurl width='880' height='350'__' width='880' height='350' alt=\"__title__\"/>",
	"image_slide_show:</a>",
	"image_slide_show:</li>",
	"image_slide_show:~SLIDE~",
	"video_slide_show:__customstarttime__",
	"video_slide_show:|SLIDEDATA|",
	"video_slide_show:__customendtime__",
	"video_slide_show:|SLIDEDATA|",
	"video_slide_show:<li class='slide' data-position='__position__'>",
	"video_slide_show:<a class='video-slide'",
	"video_slide_show:__ifvideoembed__data-video-type='embed'",
	"video_slide_show:__ifvideohosted__data-video-type='file'",
	"video_slide_show: data-embed-src='__videoembedurl__'",
	"video_slide_show: data-file-url='__videourl__'",
	"video_slide_show: data-image='__videopreviewimageurl__'",
	"video_slide_show: data-title='__title__'",
	"video_slide_show: href='#'",
	"video_slide_show:>",
	"video_slide_show:<img src='__videopreviewimageurl width='880' height='350'__'  width='880' height='350' alt=\"__title__\"/>",
	"video_slide_show:</a>",
	"video_slide_show:</li>",
	"video_slide_show:~SLIDE~",
	"after_show:#SLIDEBODY#",
	"after_show:<div class='cycle-pager'></div>",
	"after_show:</div><!-- .cycle-slideshow -->",
	"noecho"
	);

	date_default_timezone_set('America/Los_Angeles'); // or the client's timezone

	$rotator_arr = explode('#SLIDEBODY#',$get_rotator);

	$rotator_open = $rotator_arr[0];
	$rotator_items = trim($rotator_arr[1],'~SLIDE~');
	$rotator_items_arr = explode('~SLIDE~',$rotator_items);

	$rotator_close = $rotator_arr[2];
	$now = strtotime('now');

	foreach($rotator_items_arr as $slide_item){

		$slide_item_arr = explode('|SLIDEDATA|',$slide_item);
		$slide = $slide_item_arr[2];
		$start_time = trim($slide_item_arr[0]);
		$end_time = trim($slide_item_arr[1]);

		// start + end time
		if($start_time!='' && $end_time!=''){
			if((strtotime($start_time) < $now) && ($now < strtotime($end_time))){
				$rotator_slides .= $slide;
			}
		}
		// start time only
		elseif($start_time!='' && $end_time==''){
			if(strtotime($start_time) < $now){
				$rotator_slides .= $slide;
			}
		}
		// end time only
		elseif($start_time=='' && $end_time!=''){
			if($now < strtotime($end_time)){
				$rotator_slides .= $slide;
			}
		}
		// no times for you!
		elseif($start_time=='' && $end_time=='') {
			$rotator_slides .= $slide;
		}

	}

	echo $rotator_open;
	echo $rotator_slides;
	echo $rotator_close;

?>

  </div>