<?php

/**
*
* Abstracts generic helper functions
*
* @author - Kenny Kaye <kenny@monkdevelopment.com>
*         - Ricky Ybarra <ricky@monkdevelopment.com>
*         - Chris Ullyott <chris@monkdevelopment.com>
*
*/
class Helper
{

	/**
	 * Truncates a string and adds ellipse
	 *
	 * @param  string  $string       - String to Operate on
	 * @param  int 	$length 	     - Desired character amount
	 * @param  boolean $stopanywhere - Stop on any character
	 * @return string                - Truncated String
	 */
	public static function truncate($string, $length, $stopanywhere=false)
	{
		// truncates a string to a certain char length, stopping on a word if not specified otherwise.
	    if (strlen($string) > $length) {
	        // limit hit!
	        $string = substr($string,0,($length -3));
	        if ($stopanywhere) {
	            // stop anywhere
	            $string .= '...';
	        } else{
	            // stop on a word.
	            $string = substr($string,0,strrpos($string,' ')).'...';
	        }
	    }
	    return $string;
	}

	/**
	 * Adds a unique time stamp to a file to prevent caching
	 *
	 * @param  string $file - path to the file relative to root
	 * @return string       - file path with appended timestamp
	 */
	public static function fileTimestamp($file)
	{
		$file_path = $_SERVER['DOCUMENT_ROOT'] . $file;
		if(file_exists($file_path))
		{
			return $file . '?t=' . date('YmdHis',filemtime($file_path));
		}
		else
		{
			return $file;
		}
	}

	/**
	 * Slugifies a string
	 *
	 * @param  string $string - String to be slugified
	 * @return string         - Slug
	 */
	public static function createSlug($string) {

		// remove spaces, tabs, linebreaks from the begining and the end
		$string = trim($string);

		// array of patters to change characters with the following symbols
		$pattern = array('$(à|á|â|ã|ä|å|À|Á|Â|Ã|Ä|Å|æ|Æ)$',
						'$(è|é|é|ê|ë|È|É|Ê|Ë)$',


						'$(ì|í|î|ï|Ì|Í|Î|Ï)$',
						'$(ò|ó|ô|õ|ö|ø|Ò|Ó|Ô|Õ|Ö|Ø|œ|Œ)$',
						'$(ù|ú|û|ü|Ù|Ú|Û|Ü)$',
						'$(ñ|Ñ)$',
						'$(ý|ÿ|Ý|Ÿ)$',
						'$(ç|Ç)$',
						'$(ð|Ð)$',
						'$(ß)$');
		// array of replacements
		$replacement = array('a',
						'e',
						'i',
						'o',
						'u',
						'n',
						'y',
						'c',
						'd',
						's');

		// Convert special chars to ascii and remove them
		$string = htmlentities($string, ENT_QUOTES, "UTF-8");
		$search = array (
			'@(&(#?))[a-zA-Z0-9]{1,7}(;)@'   // Strip out any ascii
		);

		$replace = array (
			''
		);

		$string = preg_replace($search, $replace, $string);
		// all funny characters in the string get replaced with the
		// equivalent in ascii.
		$string =  preg_replace($pattern, $replacement, $string);

		$search = array (
			'@<script[^>]*?>.*?</script>@si', // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',          // Strip out HTML tags
			'@(\s-\s)@',                      // Strip out space dash space
			'@[\s]{1,99}@',                   // Strip out white space
			'@—@',                            // Replace em dash with regular one
			'@[\\\/)({}^\@\[\]|!#$%*+=~`?.,_;:]@' // Things not covered by htmlentities
		);

		$replace = array (
			'',
			'',
			'-',
			'-',
			'-',
			''
		);

		$string = preg_replace($search, $replace, $string);

		$string = strtolower($string);

		return $string;
	}

	public static function createSlug2($string) {

		// remove spaces, tabs, linebreaks from the begining and the end
		$string = trim($string);

		// array of patters to change characters with the following symbols
		$pattern = array('$(à|á|â|ã|ä|å|À|Á|Â|Ã|Ä|Å|æ|Æ)$',
						'$(è|é|é|ê|ë|È|É|Ê|Ë)$',


						'$(ì|í|î|ï|Ì|Í|Î|Ï)$',
						'$(ò|ó|ô|õ|ö|ø|Ò|Ó|Ô|Õ|Ö|Ø|œ|Œ)$',
						'$(ù|ú|û|ü|Ù|Ú|Û|Ü)$',
						'$(ñ|Ñ)$',
						'$(ý|ÿ|Ý|Ÿ)$',
						'$(ç|Ç)$',
						'$(ð|Ð)$',
						'$(ß)$');
		// array of replacements
		$replacement = array('a',
						'e',
						'i',
						'o',
						'u',
						'n',
						'y',
						'c',
						'd',
						's');

		// Convert special chars to ascii and remove them
		$search = array (
			'@(&(#?))[a-zA-Z0-9]{1,7}(;)@'   // Strip out any ascii
		);

		$replace = array (
			''
		);

		$string = preg_replace($search, $replace, $string);
		// all funny characters in the string get replaced with the
		// equivalent in ascii.
		$string =  preg_replace($pattern, $replacement, $string);

		$search = array (
			'@<script[^>]*?>.*?</script>@si', // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',          // Strip out HTML tags
			'@(\s-\s)@',                      // Strip out space dash space
			'@[\s]{1,99}@',                   // Strip out white space
			'@—@',                            // Replace em dash with regular one
			'@[\\\/)({}^\@\[\]|!#$%*+=~`?.,_;:]@' // Things not covered by htmlentities
		);

		$replace = array (
			'',
			'',
			'-',
			'-',
			'-',
			''
		);

		$string = preg_replace($search, $replace, $string);

		$string = strtolower($string);

		return $string;
	}

  /**
   * Reformat a date
   */
  public static function formatDate($date, $format)
  {
    $dateTime = strtotime($date);
    $formattedDate = date($format, $dateTime);

    return $formattedDate;
  }


  /**
   * Get a tag attribute.
   */
  public static function getTagAttribute($attrib, $tag)
  {
    //get attribute from html tag
    $re = '/' . $attrib . '=["\']?([^"\' ]*)["\' ]/is';
    preg_match($re, $tag, $match);

    if ($match) {
      return urldecode($match[1]);
    } else {
      return false;
    }
  }


  /**
   * Convert HTML to plain text.
   */
  public static function toPlainText($html)
  {
    // strip tags
    $string = strip_tags($html);

    // spaces
    $string = str_replace('&nbsp;', ' ', $string);
    $string = preg_replace('/\s+/', ' ', $string);

    return $string;
  }


  /**
   * Some HTML tidying.
   */
  public static function tidyHTML($html)
  {
    $toDelete = array(
      '<div>&nbsp;</div>',
      '<div></div>',
      '<p>&nbsp;</p>',
      '<p></p>'
    );

    $html = trim(str_replace($toDelete, '', $html));

    return $html;
  }


  /**
   * Explode a URL into parts, optionally excluding a home url
   */
  public static function parseUrl($rootUrl)
  {
    $currentUrl = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);
    $currentUrl = str_replace(trim($rootUrl, '/'), '', trim($currentUrl, '/'));
    $parsedUrl  = explode('/', trim($currentUrl, '/'));
    return $parsedUrl;
  }


  /**
   * Get the page's view and filter information
   */
  public static function parseUrlFilter($rootUrl)
  {
    $viewInfo = array();

    $sorters = array(
      'category',
      'group',
      'month',
      'year',
      'passage',
      'preacher',
      'series',
      'tag'
    );

    $filter = self::parseUrl($rootUrl);

    if ($filter[0] && in_array($filter[0], $sorters)) {
      $viewInfo['view']    = 'sort';
      $viewInfo['fKey']    = $filter[0];
      $viewInfo['fValue']  = $filter[1];
      $viewInfo['fString'] = 'find_' . $filter[0] . ':' . $filter[1];
    } elseif ($filter[0]) {
      $viewInfo['view']    = 'detail';
      $viewInfo['fKey']    = $filter[0];
      $viewInfo['fValue']  = $filter[0];
      $viewInfo['fString'] = '';
    } else {
      $viewInfo['view']    = 'list';
      $viewInfo['fKey']    = '';
      $viewInfo['fValue']  = '';
      $viewInfo['fString'] = '';
    }

    return $viewInfo;
  }


  /**
   * Redirect to a $url
   */
  public function redirect($url)
  {
    $path = parse_url($url, PHP_URL_PATH);
    $redirectUrl = 'http://' . $_SERVER['HTTP_HOST'] . $path;
    header('Location:' . $redirectUrl);
    exit;
  }

}
