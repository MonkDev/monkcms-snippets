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

		Custom variables:
		Blogs - $meta->page_rss


// ===================================================================== */

class pageMeta {
  public $page_title = "";
  public $page_description = "";
  public $page_keywords = "";
  public $page_group = "";
  public $page_rss = "";
  public $blog_title = "";
  public $debug = "";

  function pageMeta($modid, $def){

    //set default page title
    if($def){
      $this->page_title = $def;
    } else {
      $this->page_title = 'Ekklesia 360';  // We <3 fallbacks
    }

    //get wildcard
    $ifdetail = explode("://",$_GET['wildcard']);

    //debug text output of wildcard
    //$this->debug = $ifdetail;

    //if page/module uses display:auto will return $ifdetail[1] else just display page.
    if($ifdetail[1]!==""){
      if(strtolower($modid) == "page" || strtolower($modid) == "pages" || strtolower($modid) == "index"){
        $pmeta = getContent(
          "page",
          "display:detail",
          "find:".$_GET['nav'],
          "show:__title__",
          "show:|~",
          "show:__description__",
          "show:|~",
          "show:__tags__",
          "show:|~",
          "show:__group__",
          "noecho"
        );
        $this->assignMeta($pmeta,$modid,$def);
      }else if(strtolower($modid) == "blog" || strtolower($modid) == "blogs"){
        //blogs
        $pmeta = getContent(
          "blog",
          "display:auto",
          "before_show_postlist:__blogtitle__",
          "before_show_postlist:|~",
          "before_show_postlist:__blogtitle__",
          "before_show_postlist:|~",
          "before_show_postlist:__blogdescription__",
          "before_show_postlist:|~",
          "before_show_postlist:__group__",
          "before_show_postlist:|~",
          "before_show_postlist:__blogrss__",
          "show_detail:__blogtitle__",
          "show_detail:|~",
          "show_detail:__blogtitle__ - __blogposttitle__",
          "show_detail:|~",
          "show_detail:__blogsummary__",
          "show_detail:|~",
          "show_detail:__group__",
          "show_detail:|~",
          "show_detail:__blogrss__",
          "show_detail:|~",
          "show_detail:__tags__",
          "noecho"
          );
          $this->assignBlogMeta($pmeta);
      }else if(strtolower($modid) == "article" || strtolower($modid) == "articles"){
        //articles
        $pmeta = getContent(
          "article",
          "display:detail",
          "find:".$_GET['sermonslug'],
          "show:__title__",
          "show:|~",
          "show:__summary__",
          "show:|~",
          "show:__tags__",
          "show:|~",
          "show:__group__",
          "noecho"
          );
          $this->assignMeta($pmeta,$modid,$def);
      }else if(strtolower($modid) == "event" || strtolower($modid) == "events"){
        $pmeta = getContent(
          "event",
          "display:detail",
          "find:".$_GET['slug'],
          "show:__title__",
          "show:|~",
          "show:__summary__",
          "show:|~",
          "show:__category__",
          "show:|~",
          "show:__group__",
          "noecho"
          );
          $this->assignMeta($pmeta,$modid,$def);
      }else if(strtolower($modid) == "product" || strtolower($modid) == "products"){
        $pmeta = getContent(
          "products",
          "display:auto",
          "before_show_productlist:__familytitle__",
          "show_detail:__familytitle__ - __producttitle__",
          "show_detail:|~",
          "show_detail:__productdescription__",
          "show_detail:|~",
          "show_detail:__producttags__",
          "show_detail:|~",
          "show_detail:__group__",
          "noecho"
          );
          $this->assignMeta($pmeta,$modid,$def);
      }else if(strtolower($modid) == "sermon" || strtolower($modid) == "sermons"){
        $pmeta = getContent(
          "sermon",
          "display:auto",
          "find:".$_GET['sermonslug'],
          "show_detail:__title__",
          "show_detail:|~",
          "show_detail:__summary__",
          "show_detail:|~",
          "show_detail:__tags__",
          "show_detail:|~",
          "show_detail:__group__",
          "noecho"
          );
          $this->assignMeta($pmeta,$modid,$def);
      }
    } else {
        $pmeta = getContent(
          "page",
          "display:detail",
          "find:".$_GET['nav'],
          "show:__title__",
          "show:|~",
          "show:__description__",
          "show:|~",
          "show:__tags__",
          "show:|~",
          "show:__group__",
          "noecho"
          );
        $this->assignMeta($pmeta,$modid,$def);
    }
  }
  private function html_to_text($html){
		$text = strip_tags($html);
	  $text = str_replace('&amp;', ' ', $text);
	  $text = preg_replace("/\s+/", " ", $text);
	  $text = str_replace('"', '&quot;', $text);
	  foreach(explode("\n", $html) as $key => $line){
			$line = trim($line);
			if(!preg_match("/\.$/", $line)){
				$line = $line . ".";
			}
			if($key==0){
				$text .= $line;
			} else {
				$text .= ' ' . $line;
			}
		}
		//$text = htmlspecialchars($text);
	  return $text;
  }
  // assigns module data to class variables for output
  private function assignMeta($value,$modid,$def){
      list($ptitle,$pdes,$ptag,$pgroup) = explode("|~", $value);

      //if use display:auto sermons on the list page it doesn't output page data so we check if ptitle if not default is used
      if($pseotitle){
        $this->page_title = $pseotitle." | ".$this->page_title;
      } else if($modid == "index"){
        $this->page_title = $def;
      } else if($ptitle && $modid != "page"){
        $this->page_title = $ptitle." | ".$def;
      } else if($ptitle){
        $this->page_title = $ptitle ." | ".$this->page_title;
      }
      $this->page_description = $pdes;
      $this->page_keywords = $ptag;
      $this->page_group = $pgroup;
  }
  private function assignBlogMeta($value){
			list($blogtitle,$ptitle,$pdes,$pgroup,$prss,$ptags) = explode("|~", $value);
			$this->blog_title = $blogtitle;
			$this->page_title = $ptitle;
			$this->page_description = self::html_to_text($pdes);
			$this->page_group = $pgroup;
			$this->page_rss = $prss;
			$this->page_keywords = $ptags;
  }
}
?>