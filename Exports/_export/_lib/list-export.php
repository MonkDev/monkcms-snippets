<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php"); ?>
<?php require_once('content.class.php'); ?>
<?php

/* LIST EXPORT FUNCTIONS */

function singular_module_name($module){
	switch ($module) {
	case 'galleries':
		$name = 'gallery';
		break;
	default:
		$name = preg_replace('/(s|es)$/', '', $module);
		break;
	}
	return $name;
}


function csv_headers($array){
	$headers = '"num",';
	foreach($array as $i){
		$i = preg_replace('/\s+.*$/', '', $i);
		$headers .= '"'.$i.'",';
	}
	$headers = trim($headers,',');
	$headers .= "\n";
	return $headers;
}


function csv_string($in){
	$out = trim($in);
	$out = str_replace('"','""',$out);
	$out = str_replace('&amp;','&',$out);
	$out = '"' . $out . '"';
	return $out;
}


function gc_module_params($module){
	$params = array(
		'display' => 'list',
		'show' => 'show'
	);
	switch ($module) {
	case 'page':
		$params['display'] = 'detail';
		break;
	case 'blog':
		$params['show'] = 'show_postlist';
		break;
	case 'product':
		$params['show'] = 'show_productlist';
		break;
	}
	return $params;
}


function get_tags($module, $path){
	$tags = array();
	$file_path = rtrim($path,'/') . '/' . $module . '.php';
	$file_contents = file_get_contents($file_path);
	if(!$file_contents){
		exit("Error: Tags expected in \"$file_path\" not found.");
	}
	$file_lines = explode("\n", trim($file_contents));
	$file_lines = array_filter($file_lines);
	foreach($file_lines as $line){
		$tag = trim(trim($line),'_');
		$tags[] = $tag;
	}
	return $tags;
}



?>
<?php

/* LIST EXPORT RUNTIME */

// get module
$module = '';
if(isset($_GET['module']) && $_GET['module']!=''){
	$module = singular_module_name($_GET['module']);
} else {
	exit('No module defined.');
}

// get select (export a single data point)
$select = '';
if(isset($_GET['select']) && $_GET['select']!=''){
	$select = $_GET['select'];
}

// get filter
$filter = array(0 => '', 1 => '');
if(isset($_GET['filter']) && $_GET['filter']!=''){
	$filter = explode(':', $_GET['filter'], 2);
}


// headers
if(!isset($_GET['test']) && !isset($_GET['select'])){
	$filename = $module . 'Export' . date('M') . '_' . date('d') . '_' . date('Y');
	header("Content-Disposition: attachment; filename=" . $filename . ".csv");
	header("Content-type: text/csv");
	header("Pragma: no-cache");
	header("Expires: 0");
} else {
	header("Content-Type:text/plain");
}


// get howmany
$howmany = 5000;
if(isset($_GET['howmany']) && $_GET['howmany']!=''){
	$howmany = $_GET['howmany'];
}


// get tags
$module_tags = get_tags($module, '../_modules/list-type');


// get data
$data = array();
$batch_length = 50;
$batch_count = ceil($howmany / $batch_length);
$gc_params = gc_module_params($module);
for($i=1; $i<=$batch_count; $i++){
	$this_howmany = strval($batch_length);
	$this_offset = strval(($batch_length * ($i-1)));
	$gc = Content::getContentArray(array(
			'module' => $module,
			'display' => $gc_params['display'],
			'show' => $gc_params['show'],
			'params' => array(
				'howmany' => $this_howmany,
				'offset' => $this_offset,
				$filter[0] => $filter[1]
			),
			'tags' => $module_tags
		));
	if(empty($gc)){ break; }
	foreach($gc as $item){
		$data[] = $item;
	}
}

// unique values only
$data = array_map("unserialize", array_unique(array_map("serialize", $data)));


// select
if($select){
	foreach($data as $key => $item){
		if($item[$select]){
			echo $item[$select] . "\n";
		}
	}
	exit();
}


// build CSV
$csv = '';
$csv .= csv_headers($module_tags);
foreach($data as $key1 => $item){
	$line = '';
	$line .= csv_string($key1+1) . ",";
	foreach($item as $key2 => $cell){
		$line .= csv_string($cell) . ",";
	}
	$csv .= trim($line,", ") . "\n";
}

echo $csv;


?>