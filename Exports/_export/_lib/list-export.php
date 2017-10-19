<?php require('../_inc/config.php'); ?>
<?php require('content.class.php'); ?>
<?php require('export-functions.php'); ?>
<?php


/* LIST EXPORT */


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
	$filename = getSiteId() . '_' . $module . 'Export' . date('M') . '_' . date('d') . '_' . date('Y');
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
				$filter[0] => $filter[1],
				'howmany' => $this_howmany,
				'offset' => $this_offset,
				'find_booklist' => 'all', //books
				'name' => 'all', // blogs
                'enablepast' => 'yes' // events
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