<?php

/**
* Parse a CSV file into a multi-dimensional array of arrays.
* Array keys will be set by the headers in the CSV file.
*
* @param  string $file The path to the file for parsing.
* @return array
*/
function parseCSV($file)
{
    // read CSV file
    $data = array();
    $file = fopen($file, 'r');
    while(!feof($file)) {
        $data[] = fgetcsv($file);
    }
    fclose($file);

    // make headers
    $headers = array_shift($data);
    foreach ($headers as $k => $h) {
        $headers[$k] = preg_replace('/\s+/', '_', $h);
    }

    // parse
    $parsedData = array();
    foreach($data as $k => $line) {
        if (!implode('', $line)) {
            continue;
        }
        $parsedItem = array();
        foreach($headers as $k2 => $nodeKey) {
            $parsedItem[$nodeKey] = trim($line[$k2]);
        }
        $parsedData[] = $parsedItem;
    }

    return $parsedData;
}

?>
