<?php

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
function addArrayToCSV($array, $filename)
{
    // Use a path not available on the web
    $logDir = '<FULL PATH TO DIRECTORY>';

    $filePath = $logDir . '/' . $filename;
    $array['timestamp'] = date('r');
    $array['timestamp_u'] = date('U');

    $headers = array();
    if (!file_exists($filePath)) {
        foreach ($array as $k => $val) {
            $headers[] = $k;
        }
    }

    $file = fopen($filePath, 'a');

    if ($headers) {
        fputcsv($file, $headers);
    }
    if ($array) {
        fputcsv($file, $array);
    }

    fclose($file);
    return;
}

?>