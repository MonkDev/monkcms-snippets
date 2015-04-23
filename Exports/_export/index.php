<?php

	// Find files in a directory
	function find_files($dir, $extension='*'){
		$files = array();
		$dir = rtrim($dir,'/');
		if($extension){
			$extension = '*.' . trim($extension,'.');
		}
		$glob = glob("$dir/$extension", GLOB_BRACE);
		foreach($glob as $match){
			$pathinfo = pathinfo($match);
			$file = array(
				'path' => $match,
				'runtime_path' => $match,
				'basename' => $pathinfo['basename'],
				'filename' => $pathinfo['filename'],
				'title' => ucwords($pathinfo['filename'])
			);
			$files[] = $file;
		}
		return $files;
	}

	// Sort an array by the key
	function sortByKey(&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
	}

?>
<?php

	// get files
	$files_list = find_files('_modules/list-type', 'php');
	foreach($files_list as $key => $list_type){
		$files_list[$key]['runtime_path'] = '_lib/list-export.php?module=' . $list_type['filename'];
	}
	$files_custom = find_files('_modules/custom-type', 'php');
	$files = array_merge($files_list, $files_custom);
	sortByKey($files, 'title');

?>
<!DOCTYPE html>
<html>
	<head>
		<title>MonkCMS Export</title>
		<meta charset="utf-8">
		<meta name="robots" content="noindex, nofollow" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="_css/styles.css" />
	</head>
	<body>

		<h1>Exports</h1>
		<?php

			// Build link list
			foreach($files as $file){
				echo "<p><a target='_blank' href='".$file['runtime_path']."'>".$file['title']."</a></p>\n";
			}

		?>
	</body>
</html>
