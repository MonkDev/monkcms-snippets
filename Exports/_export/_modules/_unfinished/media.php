<?php

	/*

	OUTPUT MEDIA RECORDS AS CSV

	How to use:
	Upload this file to your site root, and use this URL to download a CSV:
	http://www.site.com/export-media.php

	*/


	$filename = getSiteId() . '_' . 'media' . 'Export' . date('M') . '_' . date('d') . '_' . date('Y');
	$howmany = 5000; // Set to number of items in the module


	// Header
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=" . $filename . ".csv");
	header("Pragma: no-cache");
	header("Expires: 0");


	// MonkCMS
	require($_SERVER['DOCUMENT_ROOT'] . '/monkcms.php');
	//date_default_timezone_set('America/Los_Angeles');


	// Functions
	function getFileType($file_name){
		$types_image = array('jpg','jpeg','png','gif','bmp','tiff');
		$types_video = array('m4v','mov','flv','mp4','wmv','webm');
		$types_audio = array('mp3','mp4','m4a','wav');
		$types_feed = array('xml');
		$file_name_arr = explode('.',$file_name);
		$ext = $file_name_arr[1];
		if(trim($ext)!=''){
			if (in_array($ext, $types_image)) {
			   return 'image';
			} elseif (in_array($ext, $types_video)) {
			   return 'video';
			} elseif (in_array($ext, $types_audio)) {
			   return 'audio';
			} elseif (in_array($ext, $types_feed)) {
			   return 'feed';
			} else {
				return 'doc';
			}
		} else {
			return '';
		}
	}
	function processItem($in){
		$out = trim($in);
		$out = str_replace('"','""',$out);
		$out = str_replace('&amp;','&',$out);
		$out = '"' . $out . '"';
		return $out;
	}
	function hostName($url){
		$parse_url = parse_url($url);
		return $parse_url['host'];
	}

	// Headers
	$headers .= '"ID",';
	$headers .= '"Type",';
	$headers .= '"Filename",';
	$headers .= '"Source",';
	$headers .= '"Service",';
	$headers .= '"Name",';
	$headers .= '"Description",';
	$headers .= '"Tags"';
	$headers .= "\n";


	// Lines
	$batch_length = 100;
	$batch_count = ceil($howmany / $batch_length);

	$get_media = '';
	$source_urls = '';
	for($i=1; $i<=$batch_count; $i++){

		$this_howmany = strval($batch_length);
		$this_offset = strval(($batch_length * ($i-1)));

		$get_media .=
		getContent(
		"media",
		"display:list",
		"type:".$_GET['type'],
		"howmany:".$this_howmany,
		"offset:".$this_offset,
		"order:recent",
		"show:__id__", // 0
		"show:~||~",
		"show:__name__", // 1
		"show:~||~",
		"show:__filename__", // 2
		"show:~||~",
		"show:__url__", // 3
		"show:~||~",
		"show:__embed__", // 4
		"show:~||~",
		"show:__description__", // 5
		"show:~||~",
		"show:__tags__", // 6
		"show:~|~|~",
		"noecho"
		);

	}

	$get_media_array = explode("~|~|~", $get_media);

	for($i=0;$i<count($get_media_array)-1;$i++){

		$media_array = explode("~||~",$get_media_array[$i]);

		$media_url = trim($media_array[3]);
		$media_url_arr = explode('/',$media_url);
		$media_filename = $media_url_arr[count(explode('/',$media_url))-1];
		if($media_filename==''){
			$media_filename = trim($media_array[2]);
		} else {
			if(strpos($media_filename,'_')!==false) {
				$media_filename_arr = explode('_',$media_filename);
				$media_filename = $media_filename_arr[count(explode('_',$media_filename))-1];
			}
		}

		$embed_code = trim($media_array[4]);

		if($embed_code!=''){
			$media_url = '';
			$media_type = 'embed';
			$media_source = $embed_code;
		} else {
			$embed_code = '';
			$media_type = getFileType($media_filename);
			$media_source = $media_url;
			if($media_source){
				$source_urls .= $media_source . "\n";
			}
		}

		$media_id = trim($media_array[0]);
		$media_name = trim($media_array[1]);
		$media_desc = trim($media_array[5]);
		$media_tags = trim($media_array[6]);

		// media service
		$media_host = 'Ekklesia 360';
		if(	stripos($media_source, '/mediafiles/')===false &&
				stripos($media_source, '/uploaded/')===false &&
				stripos($media_source, '/h264-720/')===false) {
			$media_host = 'External';
		}

		$line =
		processItem($media_id)			. "," .
		processItem($media_type)		. "," .
		processItem($media_filename)	. "," .
		processItem($media_source)		. "," .
		processItem($media_host)		. "," .
		processItem($media_name)		. "," .
		processItem($media_desc)		. "," .
		processItem($media_tags)		. "\n";

		$lines .= $line;

	}

	$lines = trim($lines,"\n");

	// Output
	if(isset($_GET['urls'])){
		echo $source_urls;
	} else {
		echo $headers . $lines;
	}

?>