<?php

	/*

	OUTPUT MEDIA RECORDS AS CSV

	How to use:
	Upload this file to your site root, and use this URL to download a CSV:
	http://www.site.com/export-media.php

	*/


	$filename = "media-export";
	$howmany = 651; // Set to number of items in the module


	// Header
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=" . $filename . ".csv");
	header("Pragma: no-cache");
	header("Expires: 0");


	// MonkCMS
	require($_SERVER['DOCUMENT_ROOT'] . '/monkcms.php');
	//date_default_timezone_set('America/Los_Angeles');


	// Functions
	function processItem($in){
		$out = trim($in);
		$out = str_replace('"','""',$out);
		$out = str_replace('&amp;','&',$out);
		$out = '"' . $out . '"';
		return $out;
	}
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
	function getRemoteModDate($url,$format){
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FILETIME, true);
		$result = curl_exec($curl);
		if ($result === false) {
		    die (curl_error($curl));
		}
		$timestamp = curl_getinfo($curl, CURLINFO_FILETIME);
		if ($timestamp != -1) {
		    return date($format, $timestamp);
		}
	}
	function formatSizeUnits($bytes){
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }
	function getFileSize($url,$format){
	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_NOBODY, true);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HEADER, true);
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	    $result = curl_exec($curl);
	    curl_close($curl);
	    if (preg_match('/Content-Length: (\d+)/', $result, $matches)) {
	        $length = (int)$matches[1];
	        $size = $length;
	        if($format=='formatted'){
				return formatSizeUnits($size);
			}
			if($format=='bytes'){
				return $size;
			}
	    }
	}

	// Headers
	$headers .= '"ID",';
	$headers .= '"Type",';
	$headers .= '"Filename",';
	$headers .= '"Source",';
	$headers .= '"Modified",';
	$headers .= '"Size",';
	$headers .= '"Size (Bytes)",';
	$headers .= '"Name",';
	$headers .= '"Description",';
	$headers .= '"Tags"';
	$headers .= "\n";


	// Lines
	$batch_length = 100;
	$batch_count = ceil($howmany / $batch_length);

	$get_media = '';
	for($i=1; $i<=$batch_count; $i++){

		$this_howmany = strval($batch_length);
		$this_offset = strval(($batch_length * ($i-1)));

		$get_media .=
		getContent(
		"media",
		"display:list",
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
		$embed_code = trim($media_array[4]);
		$media_filename = trim($media_array[2]);

		if($embed_code!=''){
			$media_url = '';
			$media_mtime = '';
			$media_type = 'embed code';
			$media_size_b = '0';
			$media_size_f = '0';
			$media_source = $embed_code;
		} else {
			$embed_code = '';
			$media_mtime = getRemoteModDate($media_url,"M d, Y g:i A");
			$media_type = getFileType($media_filename);
			$media_size_b = getFileSize($media_url,'bytes');
			$media_size_f = getFileSize($media_url,'formatted');
			$media_source = $media_url;
		}

		$media_id = trim($media_array[0]);
		$media_name = trim($media_array[1]);
		$media_desc = trim($media_array[5]);
		$media_tags = trim($media_array[6]);

		$line =
		processItem($media_id)		. "," .
		processItem($media_type)	. "," .
		processItem($media_filename). "," .
		processItem($media_source)	. "," .
		processItem($media_mtime)	. "," .
		processItem($media_size_f)	. "," .
		processItem($media_size_b)	. "," .
		processItem($media_name)	. "," .
		processItem($media_desc)	. "," .
		processItem($media_tags)	. "\n";

		$lines .= $line;

	}

	$lines = trim($lines,"\n");


	// Output
	echo $headers . $lines;

?>