<? 
  /*
   * Article List
   */
  getContent(
  "page",
  "find:".$_GET['nav'],
  "show:<h3>__title__</h3>",
  "show:<div id="text">__text__</div>"
  );
  ?>

  <? getContent(
  "article",
  "display:list",
  "show:<div class="articlebox"><h4 class="title">__titlelink__</h4>",
  "show:<p>__date format='m/d/y'__</p>",
  "show:<p>__author__</p>",
  "show:<p>__summary__</p>",
  "show:</div>"
  );
?>
