<?php

    /*

    REDIRECT MCMS SEARCH RESULTS

    For modules like Articles and Events, the Search API returns only default URLs
    using the structure "/article/the-article-slug" or "/event/the-event-slug".

    The default template files for these URLs, set in htaccess, are
    "ekk_articlepage.php" and "ekk_eventpage.php", respectively. So how do you use
    custom templates for different types of items?

    Use a snippet like this one to redirect traffic to the default
    "ekk_articlepage.php" or "ekk_eventpage.php", based on the item's Category or
    Group. Here's an example using Events.

    */


    // Redirect to subsite event template

    $eGroups = getContent(
      "event",
      "display:detail",
      "find:".$_GET['slug'],
      "show:__groupslug__",
      "noecho",
      "noedit"
    );

    $eGroups = array_filter(explode(',', $eGroups));

    // High School
    if(in_array('high-school-web-site', $eGroups)) {
      header('Location:http://'.$_SERVER['HTTP_HOST'].'/high-school-event/'.$_GET['slug'].'/');

    // Middle School
    } elseif(in_array('middle-school-web-site', $eGroups)) {
      header('Location:http://'.$_SERVER['HTTP_HOST'].'/middle-school-event/'.$_GET['slug'].'/');
    }

    /*
    Custom htaccess added to site:

    # Subsite redirects
    RewriteRule ^high-school-event/([^\/]+)/?$ /ekk_eventpage_highschool.php?slug=$1 [L,NC,QSA]
    RewriteRule ^middle-school-event/([^\/]+)/?$ /ekk_eventpage_middleschool.php?slug=$1 [L,NC,QSA]
    */

?>
