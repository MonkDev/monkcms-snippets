<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php"); ?>
<?php

/**
 *
 * getContentArray() - output MonkCMS data in an array
 *
 *
 * @author - Chris Ullyott
 * @date - 2014.09.17
 *
 *
 * Pass an array of options to getContentArray() to
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
 * values (this does not work with 'display' => 'detail').
 * For plain numerical keys, use 'keys' => false.
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
 * 4. "howmany" => INTEGER will limit the number of items
 * 	returned even where the API does not do this.
 *
 * 5. Easy Edit is disabled by default. Add the HTML
 *		for the Easy Edit links to your query by adding
 *		'easyEdit' => true
 *
 */

/* EXAMPLE 1 --------------------------------

	$array = getContentArray(array(
		'module' => 'media',
		'display' => 'list',
		'params' => array(
			'howmany' => 10
		),
		'tags' => 'name, filename, url, id'
	));

/* EXAMPLE 2 --------------------------------

	$array = getContentArray(array(
		'module' => 'blog',
		'display' => 'list',
		'params' => 'name:all,find_category:missions,howmany:10',
		'show' => 'show_postlist',
		'tags' => array(
			"blogposttitle",
			"__blogpostdate format='Y-m-d'__"
		),
		'output' => 'json'
	));

/* EXAMPLE 3 --------------------------------

	$array = getContentArray(array(
		'module' => 'page',
		'find' => 'giving',
		'params' => array(
			'nocache' => true
		),
		'tags' => 'name, slug, url, text',
		'easyEdit' => true
	));

/* EXAMPLE 4 --------------------------------

	$array = getContentArray(array(
		'module' => 'linklist',
		'display' => 'links',
		'params' => array(
			'find' => 'social-media-links',
			'howmany' => 3
		),
		'tags' => 'id, slug, name, url, description',
		'keys' => false
	));

------------------------------------------- */


function getContentArray($options){

	$gC_parts = array();

	// delimiters.
	$dL1 = '%DELIM1%';
	$dL2 = '%DELIM2%';
	$dL3 = '%DELIM3%';
	$dL4 = '%DELIM4%';
	$dL5 = '%DELIM5%';

	// module.
	$m = NULL;
	if(isset($options['module'])){ $m = trim($options['module']); }
	$gC_parts[] = '"' . $m . '"';

	// display.
	$d = 'detail';
	if(isset($options['display'])){ $d = trim($options['display']); }
	$gC_parts[] = '"display:' . $d . '"';

	// params.
	$p = NULL;
	$f = NULL;
	$h = NULL;
	$p_str = '';
	if(isset($options['find'])){ $f = trim($options['find']); }
	if(isset($options['params'])){ $p = $options['params']; }
	if(is_array($p)){
		foreach($p as $key => $param){
			$p_str .= trim($key) . ':' . trim($param) . ',';
		}
	} else {
		$p = preg_replace('/(\s+)?:(\s+)?/', ':', $p);
		$p = preg_replace('/(\s+)?,(\s+)?/', ',', $p);
		$p_str = trim($p);
	}
	$p_str = preg_replace('/(nocache|noecho):1,/', '$1,', $p_str);
	if($f && !preg_match('/find:/', $p_str)){
		$p_str = 'find:' . $f . ',' . $p_str;
	}
	$p_str_array = explode(',', trim($p_str, ','));
	foreach($p_str_array as $p_str_item){
		if(!isset($h) && preg_match('/^howmany:(\d{1,})$/', $p_str_item, $h_matches)){
			$h = $h_matches[1];
		}
		$gC_parts[] .= '"' . $p_str_item . '"';
	}

	// show tag.
	$show_tag = 'show';
	if(isset($options['show'])){ $show_tag = trim($options['show']); }

	// easy edit.
	$easyEdit = false;
	if(isset($options['easyEdit']) && $options['easyEdit']==true){ $easyEdit = true; }

	// api tags.
	$t = NULL;
	$single = false;
	$t_str = '';
	if(isset($options['tags'])){ $t = $options['tags']; }
	if(!is_array($t)){
		$t = explode(',', trim(trim($t), ','));
	}
	if(count($t)==1){
		$single = true;
	}
	foreach($t as $key => $tag){
		$tag = trim(trim($tag), '_');
		$api_tag = '__' . "$tag nokill='yes'" . '__';
		if(preg_match('/ /', $tag)){
			$tag_array = explode(' ', $tag);
			$tag = $tag_array[0];
		}
		if($easyEdit && $key==0){
			$gC_parts[] .= '"' . $show_tag . ':'. $dL5 . '"';
		}
		$gC_parts[] .= '"' . $show_tag . ':'. $dL3 . $tag . $dL4 . $api_tag . $dL1 . '"';
	}
	$gC_parts[] .= '"' . $show_tag . ':' . $dL2 . '"';

	// build getContent.
	if($easyEdit==true){
		$gC_parts[] .= '"noecho"';
	} else {
		$gC_parts[] .= '"noecho"';
		$gC_parts[] .= '"noedit"';
	}
	$gC_str = implode($gC_parts, ',');
	$gC_str = preg_replace('/("[a-zA-Z0-9]*?:0?",)/', '', $gC_str); // strip params that are false.

	// request getContent.
	$gC = str_getcsv($gC_str, ',');
	$gC = call_user_func_array('getContent', $gC);

	// get Easy Edit HTML.
	if($easyEdit){
		$gC_array = explode($dL5, $gC, 2);
		$gC_easyEdit = $gC_array[0];
		$gC = $gC_array[1];
		$gC = str_replace($dL5, '', $gC);
	}

	$k = NULL;
	if(isset($options['keys'])){ $k = $options['keys']; }

	// build getContent data.
	$gC = preg_replace("/($dL2)*$/", "", $gC);
	$gC_array = explode($dL2, $gC);
	$gC_data = array();
	foreach($gC_array as $key1 => $gC_line){
		if(isset($h) && (($key1 + 1)>$h)){ break; }
		$gC_line = preg_replace("/($dL1)*$/", "", $gC_line);
		$gC_line_array = explode($dL1, $gC_line);
		foreach($gC_line_array as $key2 => $gC_line_item){
			preg_match("/^$dL3(.*?)$dL4/", $gC_line_item, $tag_matches);
			$gC_line_tag = $tag_matches[1];
			$gC_line_item = str_replace($tag_matches[0], '', $gC_line_item);
			$gC_line_tag_arr = explode(' ', $gC_line_tag);
			$gC_line_tag = $gC_line_tag_arr[0];
			if(preg_match('/^(custom|if|is)/', $gC_line_tag) && $gC_line_item==' '){
				$gC_line_item = 1; // tag is boolean.
			}
			if($k===false){
				$gC_data[$key1][$key2] = $gC_line_item;
			} else {
				$gC_data[$key1][$gC_line_tag] = $gC_line_item;
			}
		}
	}

	// apply custom array key.
	if($k && $d!='detail'){
		$k = trim($k);
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

	// if there is only one tag... no need for multi-dimensional array.
	if($single){
		$gC_data_single = array();
		foreach($gC_data as $gC_data_item){
			foreach($gC_data_item as $gC_data_val){
				$gC_data_single[] = $gC_data_val;
			}
		}
		$gC_data = $gC_data_single;
	}

	// build output.
	$output = NULL;
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

	// return.
	return $gC_data;

}

?>