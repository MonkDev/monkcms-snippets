<?php

    /*

    EVENT PAGE REDIRECT

    Redirects the event page to a link entered
    in the "Website" field in the CMS.

    This script must be run before any content
    is output on the page.

    http://php.net/manual/en/function.header.php

    */

    $website = trim(
        getContent(
            'event',
            'display:detail',
            'find:' . $_GET['slug'],
            'show:__website__',
            'noecho',
            'noedit'
        )
    );

    if ($website) {
        if (stripos($website, 'http')===false) {
            $website = 'http://' . $website;
        }
        header('Location:' . $website);
    }

?>
