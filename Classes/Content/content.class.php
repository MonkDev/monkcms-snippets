<?php
/*
*  ===========================================================================
*
*                            CLASS Content
*
*  ===========================================================================
*
*  @author - Chris Ullyott <chris@monkdevelopment.com>
*  @url - https://github.com/MonkDev/monkcms-snippets/tree/master/Classes/Content
*  @date October 2014
*  @version 1.1
*
*/

class Content {

	/*
	*  ==========================================================================
	*
	*  getContentArray() - output MonkCMS data in an array
	*
	*  ==========================================================================
	*
	* Pass an array of options to getContentArray() to
	* get an array of data from the CMS using only the
	* API tags you need.
	*
	* @author - Chris Ullyott <chris@monkdevelopment.com>
	* @url - https://github.com/MonkDev/monkcms-snippets/
	*
	* @param options - the associative array of options
	* @return - an array of content from the CMS
	*
	* MODULE: The MonkCMS module to be queried.
	*
	* DISPLAY: The display mode. Default is "detail".
	*
	* PARAMS: An array of normal MonkCMS API parameters
	* and their values, such as "find", "howmany". Can be
	* written in any of the following three formats:
	* 1. array('find:30871', 'howmany:10')
	* 2. array('find' => 30871, 'howmany' => 10)
	* 3. "find:30871, howmany:10"
	*
	* SHOW: Default show tag is "show", but you can set
	* to "show_postlist" or etc. for various modules.
	* There are no before_show / after_show capabilities.
	*
	* TAGS: An array of API tags to include in the
	* query, without the double underscores.
	*
	* KEYS: Sets the keys of the array with the value of one
	* of the specified TAGS. Ideal with unique 'id' or 'slug'
	* values (this does not work with 'display' => 'detail').
	* For numerical keys, use 'keys' => false.
	*
	* OUTPUT: Set to 'json' for JSON output.
	*
	* TIPS:
	*
	* 1. Pass "find" either as a first-level parameter,
	* 	or within the "params" option.
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

		$array = Content::getContentArray(array(
			'module' => 'media',
			'display' => 'list',
			'params' => array(
				'howmany' => 10
			),
			'tags' => 'name, filename, url, id'
		));

	/* EXAMPLE 2 --------------------------------

		$array = Content::getContentArray(array(
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

		$array = Content::getContentArray(array(
			'module' => 'page',
			'find' => 'giving',
			'params' => array(
				'nocache' => true
			),
			'tags' => 'name, slug, url, text',
			'easyEdit' => true
		));

	/* EXAMPLE 4 --------------------------------

		$array = Content::getContentArray(array(
			'module' => 'linklist',
			'display' => 'links',
			'params' => array('find:30871','howmany:3'),
			'tags' => 'id, slug, name, url, description'
		));

	------------------------------------------- */

	public static function getContentArray($options){

		$gC_parts = array();

		// !delimiters
		$dL1 = '%dL1%'; $dL2 = '%dL2%'; $dL3 = '%dL3%'; $dL4 = '%dL4%'; $dL5 = '%dL5%';

		// !module
		$m = NULL;
		if(isset($options['module'])){ $m = trim($options['module']); }
		$gC_parts[] = $m;

		// !display
		$d = 'detail';
		if(isset($options['display'])){ $d = trim($options['display']); }
		$gC_parts[] = 'display:' . $d;

		// !params
		$p = NULL;
		$h = NULL;
		$p_str = '';
		if(isset($options['params'])){ $p = $options['params']; }
		if(is_array($p)){
			if(self::arrayIsAssociative($p)){
				$p_str = self::paramArrayToString($p);
			} else {
				$p_str = implode(',',$p);
			}
		} else {
			$p_str = self::cleanParamString($p);
		}

		// !find
		$f = NULL;
		if(isset($options['find'])){
			$f = trim($options['find']);
			$f_key = 'find';
		} else if(isset($options['find_id'])){
			$f = trim($options['find_id']);
			$f_key = 'find_id';
		}
		preg_match('/(find(_id)?):/', $p_str, $find_param_matches);
		if($f && !$find_param_matches[1]){
			$p_str = $f_key . ':' . $f . ',' . $p_str;
		}

		// !join params and find
		$p_str = self::replaceTrueParams($p_str);
		$p_str_array = explode(',', trim($p_str, ','));
		foreach($p_str_array as $p_str_item){
			if(preg_match('/^howmany:(\d{1,})$/', $p_str_item, $h_matches)){
				$h = $h_matches[1];
			}
			$gC_parts[] = $p_str_item;
		}

		// !show tag
		$show_tag = 'show';
		if(isset($options['show'])){ $show_tag = trim($options['show']); }

		// !easy edit
		$easyEdit = false;
		if(isset($options['easyEdit']) && $options['easyEdit']==true){ $easyEdit = true; }

		// !api tags
		$t = NULL;
		$t_str = '';
		if(isset($options['tags'])){ $t = $options['tags']; }
		if(!is_array($t)){
			$t = explode(',', trim(trim($t), ','));
		}
		foreach($t as $key => $tag){
			$tag = trim(trim($tag), '_');
			$api_tag = '__' . "$tag nokill='yes'" . '__';
			if(preg_match('/ /', $tag)){
				$tag = self::explodeSelect(' ', $tag, 0);
			}
			if($easyEdit && $key==0){
				$gC_parts[] = $show_tag . ':'. $dL5;
			}
			$gC_parts[] = $show_tag . ':'. $dL3 . $tag . $dL4 . $api_tag . $dL1;
		}
		$gC_parts[] = $show_tag . ':' . $dL2;
		$gC_str = self::buildGetContent($gC_parts, $easyEdit);
		$gC = str_getcsv($gC_str, ',');
		$gC = call_user_func_array('getContent', $gC);
		if(!$gC){ return NULL; } // nothing returned.

		// !get Easy Edit HTML
		if($easyEdit){
			$gC_array = explode($dL5, $gC, 2);
			$gC_easyEdit = $gC_array[0];
			$gC = str_replace($dL5, '', $gC_array[1]);
		}

		// !build getContent data
		$gC = self::trimString($gC, $dL2);
		$gC_array = explode($dL2, $gC);
		$gC_data = array();

		foreach($gC_array as $key1 => $gC_line){

			if(isset($h) && (($key1 + 1)>$h)){ break; }
			$gC_line = self::trimString($gC_line, $dL1);
			$gC_line_array = explode($dL1, $gC_line);

			foreach($gC_line_array as $key2 => $gC_line_item){

				preg_match("/^$dL3(.*?)$dL4/", $gC_line_item, $tag_matches);
				$gC_line_tag = self::explodeSelect(' ', $tag_matches[1], 0);
				$gC_line_item = str_replace($tag_matches[0], '', $gC_line_item);

				// booleans
				if(preg_match('/^(custom|if|is)/', $gC_line_tag) && $gC_line_item==' '){
					$gC_line_item = 1; // tag is boolean
				}

				// add to array
				$gC_data[$key1][$gC_line_tag] = $gC_line_item;

				// !process custom tags
				// new window
				if(preg_match('/newwindow/', $gC_line_tag)){
					$target_attr = ($gC_line_item ? '_blank' : '');
				}

			}

		}

		// !customize array keys
		$k = NULL;
		if(isset($options['keys'])){ $k = $options['keys']; }
		if($k===false){
			$gC_data = self::multiArrayKeyReset($gC_data);
		} else {
			if($k && $d!='detail'){
				$gC_data = self::customArrayKeys($gC_data, $k);
			}
		}

		// !build output
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

		// !return
		return $gC_data;

	}


	/*
	*  ==========================================================================
	*
	*  arrayIsAssociative() - returns boolean TRUE if array is associative
	*
	*  ==========================================================================
	*
	*/
	private static function arrayIsAssociative($array){
		return (bool)count(array_filter(array_keys($array), 'is_string'));
	}


	/*
	*  ==========================================================================
	*
	*  paramArrayToString() - builds a getContent param string from an array
	*
	*  ==========================================================================
	*
	*/
	private static function paramArrayToString($array){
		foreach($array as $key => $item){
			$string .= trim($key) . ':' . trim($item) . ',';
		}
		return $string;
	}


	/*
	*  ==========================================================================
	*
	*  trimString() - trims an entire string from an input string via regex
	*
	*  ==========================================================================
	*
	*/
	private static function trimString($input, $string){
		$string = preg_quote($string);
		$input = preg_replace("/^($string)*|($string)*$/", "", $input);
		return $input;
	}


	/*
	*  ==========================================================================
	*
	*  cleanParamString() - sanitize a param string
	*
	*  ==========================================================================
	*
	*/
	private static function cleanParamString($input){
		$string = preg_replace('/(\s+)?:(\s+)?/', ':', $input);
		$string = preg_replace('/(\s+)?,(\s+)?/', ',', $string);
		return trim($string);
	}


	/*
	*  ==========================================================================
	*
	*  cleanGetContentString() - strips params that are false or not set
	*
	*  ==========================================================================
	*
	*/
	private static function cleanGetContentString($input){
		// clears strings like "item:" or "item:0"
		$gC = preg_replace('/("[a-zA-Z0-9]*?:0?",)/', '', $input);
		return trim($gC);
	}


	/*
	*  ==========================================================================
	*
	*  replaceTrueParams() - removes "true" to simply set these params
	*
	*  ==========================================================================
	*
	*/
	private static function replaceTrueParams($input){
		// removes "true" to simply set these params as is getContent style
		$gC = preg_replace('/(nocache|noecho|noedit):1,/', '$1,', $input);
		return trim($gC);
	}


	/*
	*  ==========================================================================
	*
	*  buildGetContent() - builds a getContent string from array of parts
	*
	*  ==========================================================================
	*
	*/
	private static function buildGetContent($array, $easyEdit=false){
		if(!$easyEdit){ $array[] = 'noedit'; }
		$array[] = 'noecho';
		$getContent = '';
		foreach($array as $item){
			$item = '"' . $item . '",';
			$getContent .= $item;
		}
		$getContent = self::cleanGetContentString($getContent);
		return $getContent;
	}


	/*
	*  ==========================================================================
	*
	*  customArrayKeys() - sets array keys to the value of a deeper array item
	*
	*  ==========================================================================
	*
	*/
	private static function customArrayKeys($array, $deep_key){
		$new_array = array();
		foreach($array as $item){
			$this_key = $item[$deep_key];
			if($this_key=='' || isset($new_array[$this_key])){
				$new_array = array(); // error! array keys not unique
				break;
			} else {
				$new_array[$this_key] = $item;
			}
		}
		return $new_array;
	}


	/*
	*  ==========================================================================
	*
	*  multiArrayKeyReset() - resets the keys of a two-dimensional array
	*
	*  ==========================================================================
	*
	*/
	private static function multiArrayKeyReset($array){
		$array = array_values($array);
		foreach($array as $key => $array_2){
			$array[$key] = array_values($array_2);
		}
		return $array;
	}


	/*
	*  ==========================================================================
	*
	*  explodeSelect() - explode and select one of the items by the index
	*
	*  ==========================================================================
	*
	*/
	private static function explodeSelect($delimiter, $string, $index){
		$array = explode($delimiter, $string);
		return $array[$index];
	}


	/*
	*  ==========================================================================
	*
	*  extractEmbedSrc() - get the source of an iframe in HTML
	*
	*  ==========================================================================
	*
	*/
	private static function extractEmbedSrc($html){
		$pattern = '/(<object>)?<\s?iframe.*?src\s?=\s?["\'](.*?)["\']/';
		preg_match($pattern, $html, $matches);
		$src = $matches[2];
		return $src;
	}


}

?>
