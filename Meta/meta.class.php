<?php
/* ===================================================================== //

	META INFORMATION OUTPUT

	1. Initialize class in your template to set the module type.

		$meta = new pageMeta("module name", "default title");

		"module name" - the module in use:
		page  |  blog  |  article  |  event  |  sermon

		"default title" - the title in case other methods fail

	2. Call variables.

		Default variables:
		$meta->page_title
		$meta->page_description
		$meta->page_keywords
		$meta->page_group
		$meta->page_image

// ===================================================================== */

class pageMeta {
  public $page_title = "";
  public $page_description = "";
  public $page_keywords = "";
  public $page_group = "";
  public $page_image = "";
  public $debug = "";

  function pageMeta($modid,$default){

    //set default page title
    if($default){
      $this->page_title = $default;
    }

    //get wildcard
    $ifdetail = explode("://",$_GET['wildcard']);

    //debug text output of wildcard
    //$this->debug = $ifdetail;

    // PAGES
    // if page/module uses display:auto will return $ifdetail[1] else just display page.
    if($ifdetail[1]!==""){
      if(strtolower($modid) == "page" || strtolower($modid) == "pages"){
        $pmeta = getContent(
          "page",
          "display:detail",
          "find:".$_GET['nav'],
          "show:__title__",
          "show:~|~",
          "show:__description__",
          "show:~|~",
          "show:__tags__",
          "show:~|~",
          "show:__group__",
          "show:~|~",
          "noecho"
        );
        $pmeta .= getContent("media","display:detail","find:".$_GET['nav'],"label:header","show:__imageurl maxWidth='300' maxHeight='300'__",'noecho');
        $this->assignMeta($pmeta);

      // BLOGS
      }else if(strtolower($modid) == "blog" || strtolower($modid) == "blogs"){
        $pmeta = getContent(
          "blog",
          "display:auto",
          "before_show_postlist:__blogtitle__",
          "before_show_postlist:~|~",
          "before_show_postlist:__blogdescription__",
          "before_show_postlist:~|~",
          "before_show_postlist:~|~",
          "before_show_postlist:__group__",
          "before_show_postlist:~|~",
          "before_show_postlist:---headerimage---",
          "show_detail:__blogtitle__ - __blogposttitle__",
          "show_detail:~|~",
          "show_detail:__blogsummary__",
          "show_detail:~|~",
          "show_detail:__tags__",
          "show_detail:~|~",
          "show_detail:__group__",
          "show_detail:~|~",
          "show_detail:__imageurl maxWidth='300' maxHeight='300'__",
          "noecho"
          );
          $blog_header_image = getContent("media","display:detail","find:".$_GET['nav'],"label:header","show:__imageurl maxWidth='300' maxHeight='300'__",'noecho');
          $pmeta = str_replace('---headerimage---',$blog_header_image,$pmeta);
          $this->assignMeta($pmeta);

		// ARTICLES
      }else if(strtolower($modid) == "article" || strtolower($modid) == "articles"){
        $pmeta = getContent(
          "article",
          "display:detail",
          "find:".$_GET['slug'],
          "show:__title__",
          "show:~|~",
          "show:__summary__",
          "show:~|~",
          "show:__tags__",
          "show:~|~",
          "show:__group__",
          "show:~|~",
          "show:__imageurl maxWidth='300' maxHeight='300'__",
          "noecho"
          );
          $this->assignMeta($pmeta);

      // EVENTS
      }else if(strtolower($modid) == "event" || strtolower($modid) == "events"){
        $pmeta = getContent(
          "event",
          "display:detail",
          "find:".$_GET['slug'],
          "show:__title__",
          "show:~|~",
          "show:__summary__",
          "show:~|~",
          "show:__category__",
          "show:~|~",
          "show:__group__",
          "show:~|~",
          "show:__imageurl maxWidth='300' maxHeight='300'__",
          "noecho"
          );
          $this->assignMeta($pmeta);

      // PRODUCTS
      }else if(strtolower($modid) == "product" || strtolower($modid) == "products"){
        $pmeta = getContent(
          "products",
          "display:auto",
          "before_show_productlist:__familytitle__",
          "show_detail:__familytitle__ - __producttitle__",
          "show_detail:~|~",
          "show_detail:__productdescription__",
          "show_detail:~|~",
          "show_detail:__producttags__",
          "show_detail:~|~",
          "show_detail:__group__",
          "show_detail:~|~",
          "show_detail:__productimageURL maxWidth='300' maxHeight='300'__",
          "noecho"
          );
          $this->assignMeta($pmeta);

      // SERMONS
      }else if(strtolower($modid) == "sermon" || strtolower($modid) == "sermons"){
        $pmeta = getContent(
          "sermon",
          "display:auto",
          "show_detail:__title__",
          "show_detail:~|~",
          "show_detail:__summary__",
          "show_detail:~|~",
          "show_detail:__tags__",
          "show_detail:~|~",
          "show_detail:__group__",
          "show_detail:~|~",
          "show_detail:__imageurl maxWidth='300' maxHeight='300'__",
          "noecho"
          );
        if(trim($pmeta)==''){
	        $pmeta = getContent(
	          "sermon",
	          "display:detail",
	          "find:".$_GET['sermonslug'],
	          "show:__title__",
	          "show:~|~",
	          "show:__summary__",
	          "show:~|~",
	          "show:__tags__",
	          "show:~|~",
	          "show:__group__",
	          "show:~|~",
	          "show:__imageurl maxWidth='300' maxHeight='300'__",
	          "noecho"
	          );
	        }
          $this->assignMeta($pmeta);
      }
    }else{
        $pmeta = getContent(
          "page",
          "display:detail",
          "find:".$_GET['nav'],
          "show:__title__",
          "show:~|~",
          "show:__description__",
          "show:~|~",
          "show:__tags__",
          "show:~|~",
          "show:__group__",
          "noecho"
          );
        $pmeta .= getContent("media","display:detail","find:".$_GET['nav'],"label:header","show:__imageurl maxWidth='300' maxHeight='300'__",'noecho');
        $this->assignMeta($pmeta);
    }
  }

  // assigns module data to class variables for output
  private function assignMeta($value){
      list($ptitle,$pdes,$ptag,$pgroup,$pimage) = explode("~|~",$value);
      function processMetaItem($meta_input){
	  		return trim(strip_tags($meta_input));
	   }
	   if($ptitle=='INDEX'){
      	global $MCMS_SITENAME;
	      $ptitle = $MCMS_SITENAME;
      }
      if($ptitle!=''){
      	$this->page_title = processMetaItem($ptitle);
      }
      $this->page_description = processMetaItem($pdes);
      $this->page_keywords = processMetaItem($ptag);
      $this->page_group = processMetaItem($pgroup);
      $this->page_image = processMetaItem($pimage);
  }
}
?>