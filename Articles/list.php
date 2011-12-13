<? 
  /*
   * Article List
   * Note the various CSS classes are included only for the sake of example.
   * http://developers.monkcms.com/article/articles-api/
   */
  getContent(
  "article",
  "display:list",
  "show:<div class='articlebox'><h4 class='title'>__titlelink__</h4>",
  "show:<p>__date format='m/d/y'__</p>",
  "show:<p>__author__</p>",
  "show:<p>__summary__</p>",
  "show:</div>"
  );
?>
