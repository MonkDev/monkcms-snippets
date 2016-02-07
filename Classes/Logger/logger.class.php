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
   * If the data stored will be sensitive in nature, be sure to
   * store this outside of the public web directory so that the
   * file is not accessible from the site.
   *
   * Example:
   * addArrayToCSV($_GET, 'signups.csv');
   *
   * @param array $array An array of data, preferably associative.
   * @param string $filename The file name, such as "file.csv".
   * @return null
   */
  function addArrayToCSV($array, $file)
  {
      $time = strtotime('now');
      $array['timestamp'] = date('r', $time);
      $array['timestamp_u'] = $time;

      $headers = array();
      if (!file_exists($file)) {
          foreach ($array as $k => $val) {
              $headers[] = $k;
          }
      }

      $file = fopen($file, 'a');

      if ($headers) {
          fputcsv($file, $headers);
      }
      if ($array) {
          fputcsv($file, $array);
      }

      fclose($file);
      return;
  }

}

?>