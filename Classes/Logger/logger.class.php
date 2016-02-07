<?php

/**
  * Logger
  *
  * Methods for keeping logs of site data.
  *
  * @author Chris Ullyott <chris@monkdevelopment.com>
  */
class Logger
{

  /**
    * addArrayToCSV()
    * Chris Ullyott <chris@monkdevelopment.com>
    *
    * Adds an array of data into a hosted CSV file. On first run,
    * the CSV is created using the array keys as headers.
    *
    * With $dir left empty, the file will be stored in the "logs" path on the
    * Rackspace Cloud Site. If you will be using a custom path, make sure to
    * hide the contents from the public web if the data is sensitive.
    *
    * Example:
    * addArrayToCSV($_GET, 'signups.csv');
    *
    * @param array $array An array of data, preferably associative.
    * @param string $file The file name and extension, such as "file.csv".
    * @param string $dir The full path of a custom directory for the file.
    * @return null
    */
  public static function addArrayToCSV($array, $file, $dir = '')
  {
      if (!$dir) {
        $dir = str_replace('/web/content', '/logs', $_SERVER['DOCUMENT_ROOT']);
      }

      self::createDir($dir);
      $filePath = self::path($dir, $file);

      $time = strtotime('now');
      $array['timestamp'] = date('r', $time);
      $array['unix_timestamp'] = $time;

      $headers = array();
      if (!file_exists($filePath)) {
          foreach ($array as $k => $val) {
              $headers[] = $k;
          }
      }

      $handle = fopen($filePath, 'a');
      if ($headers) {
          fputcsv($handle, $headers);
      }
      if ($array) {
          fputcsv($handle, $array);
      }

      fclose($handle);

      return;
  }

  /**
   * Create a directory (or directory tree), if it doesn't already exist.
   *
   * @param string $path The full directory path.
   * @return null
   */
  private static function createDir($path, $perms = 755)
  {
      $path = rtrim($path, '/');

      if (!file_exists($path)) {
          if (mkdir($path, $perms, true)) {
              chmod($path, $perms);
          }
      }

      if (file_exists($path)) {
          return true;
      }
  }

  /**
   * Build a path from a list of parts
   *
   * @return string A string representing the concatenated path.
   */
  private static function path()
  {
      $path = '';

      foreach (func_get_args() as $key => $p) {
          if ($key == 0) {
              $path .= rtrim($p, '/') . '/';
          } else {
              $path .= trim($p, '/') . '/';
          }
      }

      return rtrim($path, '/');
  }

}

?>