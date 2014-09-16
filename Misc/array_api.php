<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php"); ?>
<?php

/**
 *
 * getContentData() - output MonkCMS data in an array
 *
 * @author Chris Ullyott
 *
 *
 * Pass an array of options to getContentData() to
 * generate a simple array of data using only the API
 * tags you want.
 *
 * MODULE: The MonkCMS module to be queried.
 * 
 * DISPLAY: The display mode. Default is "detail". 
 *
 * PARAMS: An array of normal MonkCMS API parameters
 * and their values, such as "find", "howmany".
 *
 * SHOW_TAG: If the show tag "show" is not appropriate,
 * This will override it with another show tag. 
 *
 * API_TAGS: An array of API tags to include in the
 * query, without the double underscores.
 *
 * TIPS: 
 *
 * 1.	Pass "find" either as a first-level parameter, or
 *		within the "params" array.
 *
 * 2.	In PARAMS and API_TAGS, you can pass options 
 *		either as an array or as a comma-separated list.
 *
 * 3.	"noecho" is already set.
 * 
 * 4.	Works well in straightforward implementations
 *		where display:auto is not needed.
 * 
 */

/* EXAMPLE 1 --------------------------------
	
	$data = getContentData(array(
		'module' => 'media',
		'display' => 'list',
		'params' => array(
			'howmany' => 10
		),
		'api_tags' => 'name, filename, url, id'
	));
	
/* EXAMPLE 2 --------------------------------
	
	$data = getContentData(array(
		'module' => 'blog',
		'display' => 'list',
		'params' => 'find_category:missions,howmany:10',
		'show_tag' => 'show_postlist',
		'api_tags' => array(
			"blogposttitle",
			"__blogpostdate format='Y-m-d'__"
		)
	));
	
/* EXAMPLE 3 --------------------------------
	
	$data = getContentData(array(
		'module' => 'page',
		'find' => 'p-123456',
		'params' => array(
			'nocache' => true
		),
		'api_tags' => 'name, slug, url, text'
	));
	
------------------------------------------- */
	
	function getContentData($options){
		
		// delimiters
		$dL1 = '%DELIM1%';
		$dL2 = '%DELIM2%';
		$dL3 = '%DELIM3%';
		$dL4 = '%DELIM4%';
		
		// module
		$m = '';
		if(isset($options['module'])){ $m = trim($options['module']); }
		$m_string = $m;
		
		// display 
		$d = 'detail';
		if(isset($options['display'])){ $d = trim($options['display']); }
		$d_string = 'display:' . $d;
		
		// params
		$p = '';
		$f = '';
		$p_string = '';
		if(isset($options['find'])){ $f = trim($options['find']); }
		if(isset($options['params'])){ $p = $options['params']; }
		if(is_array($p)){
			foreach($p as $key => $param){
				$p_string .= trim($key) . ':' . trim($param) . ',';
			}
		} else {
			$p = preg_replace('/(\s+)?:(\s+)?/', ':', $p);
			$p = explode(',', $p);
			$p = array_map('trim', $p);
			$p_string = implode(',', $p);
		}
		if(!preg_match('/find:/', $p_string)){
			$p_string = 'find:' . $f . ',' . $p_string;
		}
		$p_string = preg_replace('/(.*?):1,/', '$1,', $p_string);
		$p_string_array = explode(',', trim($p_string, ','));
		$p_string_new = '';
		foreach($p_string_array as $p_string_item){
			$p_string_new .= '"' . $p_string_item . '",';
		}
		$p_string = trim($p_string_new, ',');
		
		// show tag
		$show_tag = 'show';
		if(isset($options['show_tag'])){ $show_tag = trim($options['show_tag']); }
		
		// tags
		$t = '';
		$t_string = '';
		if(isset($options['api_tags'])){ $t = $options['api_tags']; }
		if(!is_array($t)){
			$t = explode(',', trim(trim($t), ','));
		}
		$t = array_map('trim', $t);
		foreach($t as $tag){
			$tag = trim(trim($tag), '_');
			$api_tag = '__' . "$tag nokill='yes'" . '__';
			if(preg_match('/ /', $tag)){
				$tag_array = explode(' ', $tag);
				$tag = $tag_array[0];
			}
			if($tag == 'ifnewwindow'){
				$api_tag = '__' . $tag . '__' . '1';
			}
			$t_string .= '"' . $show_tag . ':'. $dL3 . $tag . $dL4 . $api_tag . $dL1 . '"' .  ',';
		}
		$t_string .= '"' . $show_tag . ':' . $dL2 . '"' .  ',';
		$t_string = trim($t_string, ',');
		
		// build getContent
		$gC_string = '"' . $m_string . '",' . '"' . $d_string . '",' . $p_string . ',' . $t_string;
		$gC_string = preg_replace('/("[a-zA-Z0-9]*?:0?",)/', '', $gC_string);
		$gC_string = $gC_string . ',"noecho"';
		
		// request getContent
		$gC = str_getcsv($gC_string, ",");
		$gC = call_user_func_array("getContent", $gC);
		$gC = preg_replace("/($dL2)*$/", "", $gC);
		$gC_array = explode($dL2, $gC);
		
		// build getContent data
		$gC_data = array();
		foreach($gC_array as $key => $gC_line){
			$gC_line = preg_replace("/($dL1)*$/", "", $gC_line);
			$gC_line_array = explode($dL1, $gC_line);
			foreach($gC_line_array as $gC_line_item){
				preg_match("/^$dL3(.*?)$dL4/", $gC_line_item, $tag_matches);
				$gC_line_item = str_replace($tag_matches[0], '', $gC_line_item);
				$gC_line_tag = $tag_matches[1];
				$gC_line_tag_arr = explode(' ', $gC_line_tag);
				$gC_line_tag = $gC_line_tag_arr[0];
				if($gC_line_item == ' 1'){ $gC_line_item = trim($gC_line_item); }
				$gC_data[$key][$gC_line_tag] = $gC_line_item;
			}
		}
		
		// output
		$output = '';
		if(isset($options['output'])){ $output = $options['output']; }
		if($d=='detail' && count($gC_data)==1){
			$gC_data = $gC_data[0];
		}
		if($output=='json'){
			$gC_data = json_encode($gC_data);
		}
		return $gC_data;
	
	}

?>