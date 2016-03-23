<?php
/**
 * CLASS Monk_HTTPS
 *
 * Provides methods to output CMS content in https.
 *
 * @public  method toHTTPS()          - Rewrite a string with http:// to https//
 * @public  method secureRSCFile()    - Change a Rackspace Cloud File url to the SSL format.
 * @public  method getSContent()      - Get the secure version of values returned by a getContent call.
 * @public  method Sinclude()         - Includes a file but re-writes source calls to http to https
 * @version - 1.3.0
 * @authors - Chris Ullyott, Kenny Kaye, Ricky Ybarra
 *
 * WARNING:
 * If you have variables set in your script that are used in included
 * files (e.g. a page title in the head) those aren't gonna be available unless
 * you make them globals :(
 */

class Monk_HTTPS
{
    /**
     * Setup object.
     *
     * @param string  $hostDomain The domain with SSL installed (without "www").
     * @param boolean $testMode   Whether to rewrite URLs even when not on HTTPS.
     */
    public function __construct($hostDomain = null, $testMode = false)
    {
        if ($hostDomain) {
            $this->hostDomain = $hostDomain;
        } else {
            $this->hostDomain = $_SERVER['SERVER_NAME'];
        }

        $this->testMode = $testMode;
    }


    /**
     * Rewrite URLs in a string from "http://" to "https://"
     *
     * @param  string  $string      The string to do the operation on.
     * @param  boolean $checkScheme Whether to bypass if not currently on HTTPS.
     * @return string
     */
    public function toHTTPS($string, $checkScheme = true)
    {
        $proceed = false;

        if ($checkScheme && !$this->testMode) {
            $proceed = ($_SERVER["HTTPS"] == "on") ? true : false;
        } else {
            $proceed = true;
        }

        if ($proceed) {
            $urls = $this->getAbsoluteUrls($string);
            if (count($urls) > 0) {
                foreach ($urls as $url) {
                    $urlDomain = $this->getDomain($url);
                    $secureUrl = $url;
                    if ($urlDomain == $this->hostDomain) {
                        $secureUrl = preg_replace('/^http:/', 'https:', $secureUrl);
                    } else if (preg_match('/rackcdn\.com$/', $urlDomain)) {
                        $secureUrl = $this->secureRSCFile($secureUrl);
                    }
                    if ($secureUrl != $url) {
                        $string = str_replace($url, $secureUrl, $string);
                    }
                }
            }
        }

        return $string;
    }


    /**
     * Change a Rackspace Cloud File url to the SSL format.
     * http://www.rackspace.com/blog/rackspace-cloud-files-cdn-launches-ssl-delivery/
     *
     * @param  string $url A Rackspace Cloud File url.
     * @return string The secure file URL.
     */
    public function secureRSCFile($url)
    {
        if (strpos($url, 'rackcdn.com')!==false) {
            $url = preg_replace('/^http:\/\//', 'https://', $url);
            $url = preg_replace('/\.r\d{1,2}\./', '.ssl.', $url);
        }

        return $url;
    }


    /**
     * Get the secure version of values returned by a getContent call.
     *
     * @return mixed The getContent.
     */
    public function getSContent()
    {
        $args = func_get_args();
        $noecho = false;

        if (in_array('noecho', $args)) {
            $noecho = true;
        } else {
            $args[] = 'noecho';
        }

        $ret = call_user_func_array("getContent", $args);
        if ($ret) {
            $ret = $this->toHTTPS($ret);
            if ($noecho==false) {
                echo $ret;
            }
        }

        return $ret;
    }


    /**
     * Include a file, with URLs rewritten with "https://"
     *
     * Returns what PHP include() would return, otherwise, prints what include
     * would print but with http replaced with https for all page content
     *
     * @param  string $file The path to the file
     * @return mixed The file content.
     */
    public function Sinclude($file)
    {
        ob_start();

        $ret = include $file;
        $output = $this->toHTTPS(ob_get_contents());
        ob_end_clean();
        echo $output;

        return $ret;
    }


    /**
     * Extract all absolute URLs possible from a string of HTML.
     *
     * @param  string $string The HTML content.
     * @return array An array of URLs.
     */
    private function getAbsoluteUrls($string)
    {
        $urls = array();

        // Arbitrary URLs
        preg_match_all('@(https?://([-\w\.]+)+(:\d+)?(/([\w-/_\.]*(\?\S+)?)?)?)@', $string, $matches);
        $urls = array_merge($urls, $matches[1]);
        foreach ($urls as $key => $url) {
            $urls[$key] = trim($url, "'\";#>.)");
        }

        // CSS URLs
        preg_match_all('/url\(\s*["\']?(.*?)["\']?\s*\)/i', $string, $matches);
        $urls = array_merge($urls, $matches[1]);

        // From HTML attributes
        preg_match_all('/(href|src)\s*=\s*["\'](.*?)["\']/si', $string, $matches);
        $urls = array_merge($urls, $matches[2]);

        // Unique URLs only
        $urls = array_unique($urls);

        // Discard if not absolute (begins with "http:")
        foreach ($urls as $key => $url) {
            if (substr($url, 0, 5) != 'http:') {
                unset($urls[$key]);
            }
        }

        // Filter and reset
        $urls = array_filter($urls);
        $urls = array_values($urls);

        return $urls;
    }


    /**
     * Parse the domain from a full URL, optionally removing "www".
     *
     * @param  string  $url The URL to parse.
     * @param  boolean $www Whether to include "www" in the resulting domain.
     * @return string
     */
    private function getDomain($url, $www = false)
    {
        $urlHost = parse_url($url, PHP_URL_HOST);

        if (!$www) {
            $urlHost = preg_replace('/www\./', '', $urlHost);
        }

        return $urlHost;
    }

}

?>