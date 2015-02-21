<?php
/*
*  ===========================================================================
*
*                            CLASS Monk_HTTPS
*
*  ===========================================================================
*
*  @public method toHTTPS()      		- Rewrite a string with http:// to https//
*  @public method secureRSCFile()		- Change a Rackspace Cloud File url to the SSL format.
*  @public method getSContent()  		- Get the secure version of values returned by a getContent call.
*  @public method Sinclude()     		- Includes a file but re-writes source calls to http to https
*  @description 										- This class provides methods to output CMS content in https
*  @version 												- 1.1.0
*  @authors													- Kenny Kaye, Ricky Ybarra, Chris Ullyott
*
*  WARNING - If you have variables set in your script that are used in included files (e.g. a page title in
*  the head) those aren't gonna be avaiable unless you make them globals :(
*
*/
class Monk_HTTPS {

	/*
	*  ==========================================================================
	*
	*  toHTTPS() - Rewrite a string with http:// to https//
	*
	*  ==========================================================================
	*
    *
    * @param string - the string to do the operation on.
    * @param check - boolean - whether to check if currently on a secure page or not, defaults to true
    *
    * @return - a string with http replaced with https for all page content (but not link destinations)
 	*/

	public function toHTTPS($string, $check = true){

		$proceed = false;

		if($check){
			$proceed = ($_SERVER["HTTPS"] == "on") ? true : false;
		}
		else{
		 	$proceed = true;
		}

		if($proceed){
		 	preg_match_all('@(https?://([-\w\.]+)+(:\d+)?(/([\w-/_\.]*(\?\S+)?)?)?)@',$string,$matches);
		 	foreach($matches[1] as $url){
			 	$secure_url = str_replace('http://', 'https://', $url);
			 	$secure_url = $this->secureRSCFile($secure_url);
			 	$string = str_replace($url, $secure_url, $string);
			}
		}

	 	return $string;

	}

	/*
	*  ==========================================================================
	*
	*  secureRSCFile() - Change a Rackspace Cloud File url to the SSL format.
	*
	*	 http://www.rackspace.com/blog/rackspace-cloud-files-cdn-launches-ssl-delivery/
	*
	*  ==========================================================================
	*
    *
    * @param string - a Rackspace Cloud File url.
    *
    * @return - the secure Rackspace Cloud File url.
 	*/

	public function secureRSCFile($url){

		if(strpos($url,'rackcdn.com')!==false){
			$url = str_replace('http://','https://',$url);
			$url = preg_replace('/\.r\d{2}\./','.ssl.',$url);
		}

		return $url;

	}

	/*
	*  ==========================================================================
	*
	*  getSContent() - Get the secure version of values returned by a getContent call.
	*
	*  ==========================================================================
	*
	* The parameters are variable to match the same format as getContent
	* Returns what getContent returns with http replaced with https for all
	* page content (but not link destinations)
	*
	*/
	public function getSContent(){

	 	$args = func_get_args();
	 	if(!in_array('noecho', $args)){
			$args[] = 'noecho';
		}
	 	$ret = call_user_func_array("getContent", $args);
	 	if($ret){
	 		$output = $this->toHTTPS($ret, true);
	 	}
   	if($output){
    	print($output);
    }
    return $ret;

	}


	/*
	*  ==========================================================================
	*
	*  Sinclude() - includes a file but re-writes source calls to http to https
	*
	*  ==========================================================================
	*
	* returns what include would return, otherwise, it prints what include would print
	* but with http replaced with https for all page content (but not link destinations)
	*/
	public function Sinclude($file){
		ob_start();
		$ret = include($file);
		$output = $this->toHTTPS(ob_get_contents(), true);
		ob_end_clean();
		print($output);
		return $ret;
	}
}
?>