<?php

    /*

    OUTPUT PRODUCTS AS CSV

    */

    require('../../_inc/config.php');

    set_time_limit(0);

    $filename = getSiteId() . '_' . 'products' . 'Export' . date('M') . '_' . date('d') . '_' . date('Y');

    // Header
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=" . $filename . ".csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Functions
    function processItem($in)
    {
        $out = trim($in);
        $out = str_replace('"', '""', $out);
        $out = str_replace('&amp;', '&', $out);
        $out = '"' . $out . '"';

        return $out;
    }

    $howmany = 10000; // Set to number of products in the module

    // Headers
    $headers = '';
    $headers .= '"Product Code",';
    $headers .= '"Product Name",';
    $headers .= '"Author",';
    $headers .= '"Current Price",';
    $headers .= '"Original Price",';
    $headers .= '"Type",';
    $headers .= '"Publisher",';
    $headers .= '"Reference",';
    $headers .= '"Program Affiliation",';
    $headers .= '"Description",';
    $headers .= '"Image1",';
    $headers .= '"Image2",';
    $headers .= '"Image3"';
    $headers .= "\n";

    // Lines
    $batchLength = 100;
    $batchCount = ceil($howmany / $batchLength);

    $getProducts = '';

    for ($i=1; $i<=$batchCount; $i++) {
        $thisHowmany = strval($batchLength);
        $thisOffset = strval(($batchLength * ($i-1)));

        $getProducts .=
        getContent(
            "products",
            "display:list",
            'order:title',
            'product:all',
            "howmany_product:" . $thisHowmany,
            "offset_product:" . $thisOffset,
            'show_productlist:__productcode__',
            'show_productlist:~||~',
            'show_productlist:__producttitle__',
            'show_productlist:~||~',
            'show_productlist:__skuauthor__',
            'show_productlist:~||~',
            'show_productlist:__productprice__',
            'show_productlist:~||~',
            'show_productlist:__productOriginalprice__',
            'show_productlist:~||~',
            'show_productlist:__type__',
            'show_productlist:~||~',
            'show_productlist:__publisher__',
            'show_productlist:~||~',
            'show_productlist:__reference__',
            'show_productlist:~||~',
            'show_productlist:__affiliation__',
            'show_productlist:~||~',
            'show_productlist:__productdescription__',
            'show_productlist:~||~',
            'show_productlist:__productimageURL__',
            'show_productlist:~||~',
            'show_productlist:__productimageURL2__',
            'show_productlist:~||~',
            'show_productlist:__productimageURL3__',
            'show_productlist:~|~|~',
            'noecho'
        );
    }

    $getProductsArray = explode("~|~|~", $getProducts);

    $lines = '';

    for ($i=0;$i<count($getProductsArray)-1;$i++) {
        $productArray = explode("~||~", $getProductsArray[$i]);

        $line =
        processItem($productArray[0]) . "," .
        processItem($productArray[1]) . "," .
        processItem($productArray[2]) . "," .
        processItem($productArray[3]) . "," .
        processItem($productArray[4]) . "," .
        processItem($productArray[5]) . "," .
        processItem($productArray[6]) . "," .
        processItem($productArray[7]) . "," .
        processItem($productArray[8]) . "," .
        processItem($productArray[9]) . "," .
        processItem($productArray[10]) . "," .
        processItem($productArray[11]) . "," .
        processItem($productArray[12]) . "\n";

        $lines .= $line;
    }

    $lines = trim($lines, "\n");

    // Output
    echo $headers . $lines;
