<?php

  /*
   * Display Article Detail 
   * http://developers.monkcms.com/article/articles-api/
   */
	  
  getContent(
  "article",
  "display:detail",
  "find:".$_GET['sermonslug'],
  "show:<h3>__title__</h3>",
  "show:<ul id="byline">",
  "show:<li id="bl_preacher">__author__</li>",
  "show:<li id="bl_date">__date format='M j, Y'__</li>",
  "show:<li id="bl_series">Series: __series__</li>",
  "show:</ul>",
  "show:<div id="text">__text__</div>"
  );
?>
