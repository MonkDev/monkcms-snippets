<?php

/**
 * Content
 *
 * Provides getContentArray(), a wrapper for getContent()
 * Created October 2014
 *
 * For documentation, see:
 * https://github.com/MonkDev/monkcms-snippets/tree/master/Classes/Content
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 * @version 1.3
 */

class Content
{
  /**
   * Provide array output for getContent()
   */
  public static function getContentArray($options)
  {
    $gC_parts = array();

    // !delimiters
    $dL1 = '%dL1%';
    $dL2 = '%dL2%';
    $dL3 = '%dL3%';
    $dL4 = '%dL4%';
    $dL5 = '%dL5%';

    // !module
    $m = null;
    if (isset($options['module'])) {
      $m = trim($options['module']);
    }
    $gC_parts[] = $m;

    // !display
    $d = 'detail';
    if (isset($options['display'])) {
      $d = trim($options['display']);
    }
    $gC_parts[] = 'display:' . $d;

    // !params
    $p = null;
    $h = null;
    $p_str = '';
    if (isset($options['params'])) {
      $p = $options['params'];
    }
    if (is_array($p)) {
      if (self::arrayIsAssociative($p)) {
        $p_str = self::paramArrayToString($p);
      } else {
        $p_str = implode(',', $p);
      }
    } else {
      $p_str = self::cleanParamString($p);
    }

    // !find
    $f = null;
    if (isset($options['find'])) {
      $f = trim($options['find']);
      $f_key = 'find';
    } else if (isset($options['find_id'])) {
      $f = trim($options['find_id']);
      $f_key = 'find_id';
    }
    preg_match('/(find(_id)?):/', $p_str, $find_param_matches);
    if ($f && !isset($find_param_matches[1])) {
      $p_str = $f_key . ':' . $f . ',' . $p_str;
    }

    // !join params and find
    $p_str_array = explode(',', trim($p_str, ','));
    foreach ($p_str_array as $p_str_item) {
      if (preg_match('/^howmany:(\d{1,})$/', $p_str_item, $h_matches)) {
        $h = $h_matches[1];
      }
      if ($p_str_item) {
        $gC_parts[] = $p_str_item;
      }
    }

    // !show tags
    if (isset($options['show'])) {
      $showTags = $options['show'];
      if (!is_array($showTags)) {
        $showTags = array($showTags);
      }
    } else {
      $showTags = array('show');
    }

    // !easy edit
    $easyEdit = false;
    if (isset($options['easyEdit']) && $options['easyEdit'] == true) {
      $easyEdit = true;
    }

    // !api tags
    $t = null;
    $t_str = '';
    if (isset($options['tags'])) {
      $t = $options['tags'];
    }
    if (!is_array($t)) {
      $t = explode(',', trim(trim($t), ','));
    }

    // !build lines
    foreach ($showTags as $showTag) {
      foreach ($t as $key => $tag) {
        $tag = trim(trim($tag), '_');
        $api_tag = '__' . "$tag nokill='yes'" . '__';
        if (preg_match('/ /', $tag)) {
          $tag = self::explodeSelect(' ', $tag, 0);
        }
        if ($easyEdit && $key==0) {
          $gC_parts[] = $showTag . ':'. $dL5;
        }
        $gC_parts[] = $showTag . ':'. $dL3 . $tag . $dL4 . $api_tag . $dL1;
      }
      $gC_parts[] = $showTag . ':' . $dL2;
    }

    if (!$easyEdit) {
      $gC_parts[] = 'noedit';
    }
    $gC_parts[] = 'noecho';
    $gC_parts = self::replaceBooleans($gC_parts);

    // !debug: check getContent
    // print_r($gC_parts);

    // !call getContent
    $gC = call_user_func_array('getContent', $gC_parts);

    // !get Easy Edit HTML
    if ($easyEdit) {
      $gC_array = explode($dL5, $gC, 2);
      $gC_easyEdit = $gC_array[0];
      $gC = str_replace($dL5, '', $gC_array[1]);
    }

    // !build getContent data
    $gC_array = self::explodeAndFilter($dL2, $gC);
    $gC_data = array();

    foreach ($gC_array as $key1 => $gC_line) {
      if (isset($h) && (($key1 + 1) > $h)) {
        break;
      }

      $gC_line_array = self::explodeAndFilter($dL1, $gC_line);

      foreach ($gC_line_array as $key2 => $gC_line_item) {
        preg_match("/^$dL3(.*?)$dL4/", $gC_line_item, $tag_matches);
        $gC_line_tag = self::explodeSelect(' ', $tag_matches[1], 0);
        $gC_line_item = str_replace($tag_matches[0], '', $gC_line_item);

        // tag is a boolean
        if (preg_match('/^(if|is|custom)/', $gC_line_tag) && $gC_line_item == ' ') {
          $gC_line_item = 1;
        }

        // add to array
        $gC_data[$key1][$gC_line_tag] = $gC_line_item;
      }
    }

    // !customize array keys
    $k = null;
    if (isset($options['keys'])) {
      $k = $options['keys'];
    }
    if ($k === false) {
      $gC_data = self::multiArrayKeyReset($gC_data);
    } else if ($k && $d != 'detail') {
      $gC_data = self::customArrayKeys($gC_data, $k);
    }

    // !build output
    $output = null;
    if (isset($options['output'])) {
      $output = trim($options['output']);
    }
    if ($d == 'detail' && count($gC_data) == 1) {
      $gC_data = $gC_data[0];
    }
    if ($easyEdit) {
      $gC_dataStore = $gC_data;
      $gC_data = array();
      $gC_data[$d] = $gC_dataStore;
      $gC_data['easyEdit'] = $gC_easyEdit;
    }
    if (strtolower($output) == 'json') {
      $gC_data = json_encode($gC_data);
    }

    // !return
    return $gC_data;
  }

  /**
   * Whether array is associative
   */
  private static function arrayIsAssociative($array)
  {
    return (bool)count(array_filter(array_keys($array), 'is_string'));
  }

  /**
   * Builds a getContent param string from an array
   */
  private static function paramArrayToString($array)
  {
    foreach ($array as $key => $item) {
      $string .= trim($key) . ':' . trim($item) . ',';
    }
    return $string;
  }

  /**
   * Sanitize a param string
   */
  private static function cleanParamString($input)
  {
    $string = preg_replace('/(\s+)?:(\s+)?/', ':', $input);
    $string = preg_replace('/(\s+)?,(\s+)?/', ',', $string);
    return trim($string);
  }

  /**
   * Handle "true" and "false" params
   */
  private static function replaceBooleans($array)
  {
    foreach ($array as $k => $i) {
      // replace true params
      if ($trueMatch = preg_replace('/^(.+):(1|true)$/i', '$1', $i)) {
        $array[$k] = $trueMatch;
      }

      // replace false params
      if (preg_match('/^(.+):(|0|false)$/i', $i)) {
        unset($array[$k]);
      }
    }
    return $array;
  }

  /**
   * Set an array's keys to the value of an array item (if keys are unique)
   */
  private static function customArrayKeys($array, $deep_key)
  {
    $new_array = array();
    foreach ($array as $item) {
      $this_key = $item[$deep_key];
      if ($this_key=='' || isset($new_array[$this_key])) {
        $new_array = array(); // error! array keys not unique
        break;
      } else {
        $new_array[$this_key] = $item;
      }
    }
    return $new_array;
  }

  /**
   * Reset the keys of a two-dimensional array
   */
  private static function multiArrayKeyReset($array)
  {
    $array = array_values($array);
    foreach ($array as $key => $array_2) {
      $array[$key] = array_values($array_2);
    }
    return $array;
  }

  /**
   * Explode a string and remove empty items
   */
  private static function explodeAndFilter($delimiter, $string)
  {
    $array = explode($delimiter, $string);
    return array_filter($array);
  }

  /**
   * Explode and select one of the items by the index
   */
  private static function explodeSelect($delimiter, $string, $index)
  {
    $array = explode($delimiter, $string);
    return $array[$index];
  }
}
