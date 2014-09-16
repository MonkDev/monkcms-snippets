<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php"); ?>
<?php
	
	/**
	 * getContentData() - output CMS data in array format
	 *
	 * @author Chris Ullyott <chris@monkdevelopment.com>
	 *
	 * Pass an array of options to getContentData() to
	 * generate an array of data using only the API tags
	 * you want.
	 *
	 * MODULE: The CMS module to be queried.
	 * 
	 * DISPLAY: The display mode. Default is "detail". 
	 *
	 * PARAMS: An array of all normal API parameters
	 * including "find".
	 *
	 * SHOW_TAG: If the show tag "show" is not appropriate,
	 * This will override it with another show tag. 
	 *
	 * API_TAGS: An array of API tags to include in the
	 * query (without the wrapping double underscores).
	 *
	 * TIPS: 
	 * 1.	Pass "find" as a first-level parameter, or, 
	 * 		within the "params" array.
	 * 2.	In PARAMS and API_TAGS, pass your options 
	 * 		either as an associative array or a 
	 * 		comma-separated list.
	 * 3. "noecho" and "noedit" are already set.
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
		
		---------------------------------------- */
		
	/* EXAMPLE 2 --------------------------------
		
		$data = getContentData(array(
			'module' => 'blog',
			'display' => 'list',
			'params' => 'find_category:missions,howmany:10',
			'api_tags' => array(
				"blogposttitle",
				"__blogpostdate format='Y-m-d'__"
			)
		));
		
		---------------------------------------- */
		
	/* EXAMPLE 3 --------------------------------
		
		$data = getContentData(array(
			'module' => 'page',
			'find' => 'p-123456',
			'params' => array(
				'nocache' => true
			),
			'api_tags' => 'name, slug, url, text'
		));
		
		---------------------------------------- */

	function getContentData($options){
		
		// delimiters
		$d1 = '%#_DELIM1_#%';
		$d2 = '%#_DELIM2_#%';
		$d3 = '%#_DELIM3_#%';
		$d4 = '%#_DELIM4_#%';
		
		// module
		$module = $options['module'];
		$module_string = $module;
		
		// display 
		if(isset($options['display'])){
			$display = $options['display'];
		} else {
			$display = 'detail';
		}
		$display_string = 'display:' . trim($display);
		
		// find
		$find = '';
		if(isset($options['find'])){
			$find = trim($options['find']);
		}
		
		// params
		$params = $options['params'];
		if(isset($options['parameters'])){
			$params = $options['parameters'];
		}
		$params_string = '';
		if(!is_array($params)){
			$params_string = preg_replace('/\s/', '', $params);
		} else {
			foreach($params as $key => $param){
				$params_string .= $key . ':' . trim($param) . ',';
			}
		}
		$params_string = preg_replace('/(.*?):1,/', '$1,', $params_string);
		if(!preg_match('/find:/', $params_string)){
			$params_string = 'find:' . $find . ',' . $params_string;
		}
		$params_string = str_replace('find:,', '', $params_string);
		$params_string = trim($params_string, ',');
		
		// show tag
		$show_tag = 'show';
		if(isset($options['show_tag'])){
			$show_tag = $options['show_tag'];
		}
		
		// tags
		$tags = $options['api_tags'];
		$tags_string = '';
		if(!is_array($tags)){
			$tags = preg_replace('/\s/', '', $tags);
			$tags = explode(',', trim($tags, ','));
		}
		foreach($tags as $key => $tag){
			if($tag == 'ifnewwindow'){
				$api_tag = '__' . trim($tag, '_') . '__' . '1';
			} else {
				$api_tag = '__' . trim($tag, '_') . " nokill='yes'" . '__';
			}
			$tags_string .= $show_tag . ':'. $d3 . $tag . $d4 . trim($api_tag) . $d1 . ',';
		}
		$tags_string .= $show_tag . ':' . $d2 . ',';
		$tags_string = trim($tags_string, ',');
		
		// build getContent
		$gC_string =	$module_string 		. ',' . 
									$display_string 	. ',' . 
									$params_string 		. ',' . 
									$tags_string 			. ',' . 
									'noecho' 					. ',' . 
									'noedit';
											
		// request getContent
		$gC = call_user_func_array("getContent", explode(',', $gC_string));
		$gC_array = array_filter(explode($d2, $gC));
		
		// build getContent data
		$gC_data = array();
		foreach($gC_array as $key => $gC_line){
			$gC_line = preg_replace("/($d1)*$/", "", $gC_line);
			$gC_line_array = explode($d1, $gC_line);
			foreach($gC_line_array as $gC_line_item){
				preg_match("/^$d3(.*?)$d4/", $gC_line_item, $tag_matches);
				$gC_line_item = str_replace($tag_matches[0], '', $gC_line_item);
				$gC_line_tag = $tag_matches[1];
				$gC_line_tag_arr = explode(' ', $gC_line_tag);
				$gC_line_tag = $gC_line_tag_arr[0];
				if($gC_line_item == ' 1'){ $gC_line_item = trim($gC_line_item); }
				$gC_data[$key][$gC_line_tag] = $gC_line_item;
			}
		}
		
		// output
		$output = $options['output'];
		if($display=='detail' && count($gC_data)==1){
			$gC_data = $gC_data[0];
		}
		if($output=='json'){
			$gC_data = json_encode($gC_data);
		}
		return $gC_data;

	}	
	
?>