<?
// Often used in columns of links in footer or header areas. This code outputs the parent and 
// child links of a specific link list. 
?>
<? getContent(
      "linklist",
      "display:links",
      "find:your-link-list-name",
      "level1:<h4><a href='__url__'",
      "level1:__ifnewwindow__target='_blank'",
      "level1:>__name__</a></h4>",
      "level2:<a href='__url__' ",
      "level2:__ifnewwindow__target='_blank'",
      "level2:>__name__</a>"
    );
?>
