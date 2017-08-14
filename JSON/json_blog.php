<?php

/**
 * MonkCMS JSON output for a blog
 *
 * Builds JSON output for a blog, supporting all getContent parameters via the
 * query string, like so:
 *
 * http://example.com/json_blog.php?name=our-blog
 * http://example.com/json_blog.php?name=out-blog&offset=10&howmany=42
 *
 * Skyler Katz <skyler@monkdevelopment.com>
 */


// Include MonkCMS
require($_SERVER["DOCUMENT_ROOT"] . '/monkcms.php');

// Verify a blog name is included
if (!isset($_GET['name'])) {
    $message = "You must include a blog slug name in the 'name' query parameter.  For example http://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}?name=our-blog";

    header('Content-type: application/json');
    echo json_encode(
        array(
            'status'  => 'error',
            'message'  => $message,
        )
    );
    die();
}

// Define default parameters
$params = array(
    'display' => 'list',
    'order'   => 'recent',
    'howmany' => 50,
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
    "blogpostdate format='r'",
    "blogpostdate2",
    "preview limit='200'",
    "imageurl width='1280' height='720'",
    "imageurl2 width='200' height='200'",
);


// Build the arguments for getContent
$gcArgs = array('blog');
$gcArgs = array_merge($gcArgs, $gcParams);
foreach ($gcTags as $key => $tag) {
    $gcArgs[] = "show:__{$tag}__";
}
$gcArgs[] = 'json';


// Make API request
$response = call_user_func_array('getContent', $gcArgs);
$items = $response['show_postlist'];


// Make second request to get total number of possible items
$gcArgs = array('blog');
$gcArgs = array_merge($gcArgs, $gcParams);
$gcArgs[] = 'before_show_postlist:__totalpossible__';
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
