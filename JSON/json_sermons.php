<?php

/**
 * MonkCMS JSON output for sermons
 *
 * Builds JSON output for sermons, supporting all getContent parameters via the
 * query string, like so:
 *
 * http://example.com/json_sermons.php?find_series=he-is-risen
 * http://example.com/json_sermons.php?find_group=east-campus&offset=10
 *
 * Chris Ullyott <chris@monkdevelopment.com>
 */


// Include MonkCMS
require($_SERVER["DOCUMENT_ROOT"] . '/monkcms.php');


// Define default parameters
$params = array(
    'display' => 'list',
    'order'   => 'recent',
    'howmany' => 50
);


// Merge custom parameters from query string
$params = array_merge($params, array_filter($_GET));


// Parse parameters for getContent
$gcParams = array();
foreach ($params as $k => $v) {
    $gcParams[] = "{$k}:{$v}";
}


// Define custom formatted API tags to request
$gcTags = array(
    "date format='r'",
    "dateTwo format='Y-m-d'",
    "dateThree format='F j, Y'",
    "preview limit='200'",
    "imageurl width='1280' height='720'",
    "imageurl2 width='200' height='200'",
);


// Build the arguments for getContent
$gcArgs = array('sermons');
$gcArgs = array_merge($gcArgs, $gcParams);
foreach ($gcTags as $key => $tag) {
    $gcArgs[] = "show:__{$tag}__";
}
$gcArgs[] = 'json';


// Make API request
$response = call_user_func_array('getContent', $gcArgs);
$items = $response['show'];


// Make second request to get total number of possible items
$gcArgs = array('sermons');
$gcArgs = array_merge($gcArgs, $gcParams);
$gcArgs[] = 'before_show:__totalpossible__';
$gcArgs[] = 'noecho';
$gcArgs[] = 'noedit';
$total = call_user_func_array('getContent', $gcArgs);


// Build json
$json = json_encode(array(
    'total'  => intval($total),
    'count'  => count($items),
    'offset' => !empty($_GET['offset']) ? intval($_GET['offset']) : 0,
    'items'  => $items
));


// Output
$callback = !empty($_REQUEST['callback']) ? $_REQUEST['callback'] : null;

if ($callback) {
    header('Content-type: text/javascript');
    echo $callback . '(' . $json . ');';
} else {
    header('Content-type: application/json');
    echo $json;
}
