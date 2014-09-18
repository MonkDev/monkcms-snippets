<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php"); ?>
<?php //header("Content-Type: text/plain"); ?>
<?php

/**
 *
 * getContentData() - output MonkCMS data in an array
 *
 *
 * @author - Chris Ullyott
 * @date - 2014.09.17
 *
 *
 * Pass an array of options to getContentData() to
 * generate an array of data using only the API tags
 * you want.
 *
 * MODULE: The MonkCMS module to be queried.
 *
 * DISPLAY: The display mode. Default is "detail".
 *
 * PARAMS: An array of normal MonkCMS API parameters
 * and their values, such as "find", "howmany".
 *
 * SHOW: Default show tag is "show", but you can set
 * to "show_postlist" or etc. for various modules.
 *
 * TAGS: An array of API tags to include in the
 * query, without the double underscores.
 *
 * KEYS: Sets the keys of the array with the value of one
 * of the specified TAGS. Ideal with unique 'id' or 'slug'
 * values. Does not work with 'display' => 'detail'.
 *
 * OUTPUT: Set to 'json' for JSON output.
 *
 *
 * TIPS:
 *
 * 1. Pass "find" either as a first-level parameter,
 * 	or within the "params" array.
 *
 * 2. In PARAMS and TAGS, you can pass options
 *		either as an array or as a comma-separated list.
 *
 * 3. Some tags that respond by outputting a single space
 * 	are considered to be a boolean of TRUE. They are:
 *		__custom(.*?)__  __if(.*?)__  __is(.*?)__
 *
 * 4. Easy Edit is disabled by default. Add the HTML
 *		for the Easy Edit links to your query by adding
 *		the param: 'easyEdit' => true
 *
 * 5. "noecho" is already set.
 *
 */

/* EXAMPLE 1 --------------------------------

	$data = getContentData(array(
		'module' => 'media',
		'display' => 'list',
		'params' => array(
			'howmany' => 10
		),
		'tags' => 'name, filename, url, id'
	));

/* EXAMPLE 2 --------------------------------

	$data = getContentData(array(
		'module' => 'blog',
		'display' => 'list',
		'params' => 'find_category:missions,howmany:10',
		'show' => 'show_postlist',
		'tags' => array(
			"blogposttitle",
			"__blogpostdate format='Y-m-d'__"
		),
		'output' => 'json'
	));

/* EXAMPLE 3 --------------------------------

	$data = getContentData(array(
		'module' => 'page',
		'find' => 'p-123456',
		'params' => array(
			'nocache' => true
		),
		'tags' => 'name, slug, url, text'
	));

------------------------------------------- */


function getContentData($options){

	// delimiters
	$dL1 = '%DELIM1%';
	$dL2 = '%DELIM2%';
	$dL3 = '%DELIM3%';
	$dL4 = '%DELIM4%';
	$dL5 = '%DELIM5%';

	// module
	$m = '';
	if(isset($options['module'])){ $m = trim($options['module']); }
	$m_str = $m;

	// display
	$d = 'detail';
	if(isset($options['display'])){ $d = trim($options['display']); }
	$d_str = 'display:' . $d;

	// params
	$p = '';
	$f = '';
	$p_str = '';
	$easyEdit = false;
	if(isset($options['find'])){ $f = trim($options['find']); }
	if(isset($options['params'])){ $p = $options['params']; }
	if(is_array($p)){
		foreach($p as $key => $param){
			$p_str .= trim($key) . ':' . trim($param) . ',';
		}
	} else {
		$p = preg_replace('/(\s+)?:(\s+)?/', ':', $p);
		$p = explode(',', $p);
		$p = array_map('trim', $p);
		$p_str = implode(',', $p);
	}
	if($f && !preg_match('/find:/', $p_str)){
		$p_str = 'find:' . $f . ',' . $p_str;
	}
	if(preg_match('/easyedit:1/', strtolower($p_str))){
		$easyEdit = true;
	}
	$p_str = preg_replace('/easyEdit(:1)?,/', '', $p_str);
	$p_str = preg_replace('/(nocache|noecho):1,/', '$1,', $p_str);
	$p_str_array = explode(',', trim($p_str, ','));
	$p_str_new = '';
	foreach($p_str_array as $p_str_item){
		$p_str_new .= '"' . $p_str_item . '",';
	}
	$p_str = trim($p_str_new, ',');

	// show tag
	$show_tag = 'show';
	if(isset($options['show'])){ $show_tag = trim($options['show']); }

	// api tags
	$t = '';
	$t_str = '';
	if(isset($options['tags'])){ $t = $options['tags']; }
	if(!is_array($t)){
		$t = explode(',', trim(trim($t), ','));
	}
	foreach($t as $key => $tag){
		$tag = trim(trim($tag), '_');
		$api_tag = '__' . "$tag nokill='yes'" . '__';
		if(preg_match('/ /', $tag)){
			$tag_array = explode(' ', $tag);
			$tag = $tag_array[0];
		}
		if($easyEdit && $key==0){
			$t_str .= '"' . $show_tag . ':'. $dL5 . '"' .  ',';
		}
		$t_str .= '"' . $show_tag . ':'. $dL3 . $tag . $dL4 . $api_tag . $dL1 . '"' .  ',';
	}
	$t_str .= '"' . $show_tag . ':' . $dL2 . '"' .  ',';
	$t_str = trim($t_str, ',');

	// build getContent
	$gC_str = '"' . $m_str . '",' . '"' . $d_str . '",' . $p_str . ',' . $t_str;
	$gC_str = preg_replace('/("[a-zA-Z0-9]*?:0?",)/', '', $gC_str);
	if($easyEdit){
		$gC_str = $gC_str . ',"noecho"';
	} else {
		$gC_str = $gC_str . ',"noecho","noedit"';
	}

	// request getContent
	$gC = str_getcsv($gC_str, ",");
	$gC = call_user_func_array("getContent", $gC);

	// get Easy Edit HTML
	if($easyEdit){
		$gC_array = explode($dL5, $gC, 2);
		$gC_easyEdit = $gC_array[0];
		$gC = $gC_array[1];
		$gC = str_replace($dL5, '', $gC);
	}

	// build getContent data
	$gC = preg_replace("/($dL2)*$/", "", $gC);
	$gC_array = explode($dL2, $gC);
	$gC_data = array();
	foreach($gC_array as $key => $gC_line){
		$gC_line = preg_replace("/($dL1)*$/", "", $gC_line);
		$gC_line_array = explode($dL1, $gC_line);
		foreach($gC_line_array as $gC_line_item){
			preg_match("/^$dL3(.*?)$dL4/", $gC_line_item, $tag_matches);
			$gC_line_tag = $tag_matches[1];
			$gC_line_item = str_replace($tag_matches[0], '', $gC_line_item);
			$gC_line_tag_arr = explode(' ', $gC_line_tag);
			$gC_line_tag = $gC_line_tag_arr[0];
			if(preg_match('/^(custom|if|is)/', $gC_line_tag) && $gC_line_item==' '){
				$gC_line_item = 1; // tag is boolean
			}
			$gC_data[$key][$gC_line_tag] = $gC_line_item;
		}
	}

	// custom array key
	$k = '';
	if(isset($options['keys'])){ $k = trim($options['keys']); }
	if($k && $d!='detail'){
		$gC_data_newKey = array();
		foreach($gC_data as $key => $gC_data_item){
			$this_key = $gC_data_item[$k];
			if(!isset($gC_data_newKey[$this_key])){
				$gC_data_newKey[$this_key] = $gC_data_item;
			} else {
				$gC_data_newKey = array(); // error! array keys not unique.
				break;
			}
		}
		$gC_data = $gC_data_newKey;
	}

	// build output
	$output = '';
	if(isset($options['output'])){ $output = trim($options['output']); }
	if($d=='detail' && count($gC_data)==1){
		$gC_data = $gC_data[0];
	}
	if($easyEdit){
		$gC_dataStore = $gC_data;
		$gC_data = array();
		$gC_data[$d] = $gC_dataStore;
		$gC_data['easyEdit'] = $gC_easyEdit;
	}
	if(strtolower($output)=='json'){
		$gC_data = json_encode($gC_data);
	}

	// return
	return $gC_data;

}

?>