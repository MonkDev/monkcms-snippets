<?php 

// Generates an export of sermon data delimited by "~" and a newline after each sermon record. 
// The results can be imported into Excel with the specified delimiters. 
require($_SERVER['DOCUMENT_ROOT'] . '/monkcms.php'); 
?>

<pre>
"Title"~"Category"~"Speaker"~"Passage"~"Series"~"Date"~"Audio"~"Notes"~"Video"

<?php
      getContent(
            'sermon',
            'display:list',
            'order:recent',
            'show_:',
            'show:__title__',
            'show:~',
            'show:__category__',
            'show:~',
            'show:__preacher__',
            'show:~',
            'show:__passage__',
            'show:~',
            'show:__series__',
            'show:~',
            'show:__date__',
            'show:~',
            'show:__audiourl__',
            'show:~',
            'show:__notes__',
            'show:~',
            'show:__videourl__',
            'show:'."\n",
            'nocache'
    );
   
?>
</pre>
