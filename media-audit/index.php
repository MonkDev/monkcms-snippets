<?php

	/* MONKCMS MEDIA AUDIT */
	/* http://stackoverflow.com/questions/2510434/php-format-bytes-to-kilobytes-megabytes-gigabytes */

	$media_dir = '/am_cms_media/';

	$directory = $_SERVER['DOCUMENT_ROOT'] . $media_dir;

	date_default_timezone_set('America/Los_Angeles');

	$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

	$file_arr = array();

	while($it->valid()) {

	    if (!$it->isDot()) {

	        array_push($file_arr, $it->key());
	    }

	    $it->next();
	}

	$file_list = '';

	// Function: get filename
	function getFilename($file_path){
		$file_path_arr = explode('/',$file_path);
		return $file_path_arr[count($file_path_arr)-1];
	}
	function getModDate($file_path,$format){
		if (file_exists($file_path)) {
		    return date($format, filemtime($file_path));
		}
	}
	function formatSizeUnits($bytes){
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }
        return $bytes;
    }
	function getFileSize($file_path,$format){
		if (file_exists($file_path)) {
			if($format=='formatted'){
				return formatSizeUnits(filesize($file_path));
			}
			if($format=='bytes'){
				return filesize($file_path);
			}
		}
	}
	function getFileType($file_path){
		$types_image = array('jpg','jpeg','png','gif','bmp','tiff');
		$types_video = array('m4v','mov','flv','mp4','wmv','webm');
		$types_audio = array('mp3','mp4','m4a','wav');
		$types_feed = array('xml');
		$ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
		if(trim($ext)!==''){
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
			return '-';
		}
	}
	function getLink($file_path,$media_dir){
		$file_path_arr = explode('/',$file_path);
		$filename = $file_path_arr[count($file_path_arr)-1];
		$url = $media_dir . $filename;
		return '<a href="'. $url .'" target="_blank">&rarr;</a>';
	}



	// Run file list
	$counter = 0;
	natcasesort($file_arr);
	array_values($file_arr);
	foreach($file_arr as $file){
		$counter++;
		$row = '<tr data-index="'. $counter .'" data-timestamp="'. getModDate($file,"U") .'" data-length="'.getFileSize($file,"bytes") .'">';
		$row .= '<td class="num">' . $counter . '</td>';
		$row .= '<td>' . getFilename($file) . '</td>';
		$row .= '<td>' . getModDate($file,"M d, Y g:i A") . '</td>';
		$row .= '<td>' . getFileSize($file,"bytes") . '</td>';
		$row .= '<td>' . getFileType($file) . '</td>';
		$row .= '<td>' . getLink($file,$media_dir) . '</td>';
		$row .= '</tr>';
		$file_list .= $row;
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="robots" content="nofollow" />
		<title>MEDIA AUDIT</title>
		<link rel="stylesheet" href="_css/styles.css" />
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<script type="text/javascript" src="_js/jquery.tablesorter.min.js"></script>
	</head>
	<body>
		<div class="container">
		<h1>foothillschurch.org media audit</h1>
		<p>Directory: <?php echo $media_dir; ?></p>
		<p>Total: <?php echo count($file_arr); ?> files</p>
		<table id="file_list" class="tablesorter">
			<thead>
			<tr>
				<th><!-- # --></th>
				<th>Filename</th>
				<th>Modified</th>
				<th>Size</th>
				<th>Type</th>
				<th>Open</th>
			</tr>
			</thead>
			<tbody>
			<?php echo $file_list; ?>
			</tbody>
		</table>
		</div><!-- .container -->
	</body>
	<script type="text/javascript">
	//<![CDATA[
	$(document).ready(function(){

		$("#file_list").tablesorter();

	});
	// ]]>
	</script>
</html>