<div id="search">
<?php

 getContent(
    "search",
    "display:results",
    "hide_module:media",
    "howmany:25",
    "before_show:<div class='result'><p><em>__resultsnumber__ items found for \"__term__\"</em></p></div>",
    "show:<div class='result'>",
    "show:<p class='title'>__titlelink__ <span class='type'>__type__</span></p>",
    "show:<p class='url'>" . "http://" . $_SERVER['HTTP_HOST'] . "__url__</p>",
    "show:<p class='preview'>__preview__</p>",
    "show:</div>",
    "after_show:__pagination__",
    "no_show:Sorry, your search for __term__ has no results."
 );

?>
</div>