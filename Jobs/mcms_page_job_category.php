<?
  require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php");
  $_GET['nav'] = 'p-29805';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>

      <title><?php include($_SERVER["DOCUMENT_ROOT"]."/includes/site-name.php") ?> > <? getContent(
         "page",
         "find:".$_GET['nav'],
         "show:__title__"
         );
      ?></title>
<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/head.php"); ?> 

   </head>
   <body>
      <div id="container">
<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/header.php") ?>
         <div id="container-inner">
            <div id="content-wrap">
<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-box.php") ?>
               <div id="subnav-wrap">
<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/subnav.php") ?>
               </div>
               <div id="content">

<? getContent(
   "media",
   "display:detail",
   "find:".$_GET['nav'],
   "label:header",
   "show:<img src=\"__imageurl__\" alt=\"__description__\" class=\"header-image\" />"
   );
?>

                  <table class="jobs">

<? getContent(
   "job",
   "display:list",
   "order:recent",
   "howmanydays:120",
   "find_category:".$_GET['catslug'],
   "groupby:category",
   "group_show:<tr class=\"even\"><td><a href=\"/job-category/__slug__/\">__title__</a></td><td class=\"rss\"><a href=\"/media/jobsCat__slug__.xml\">RSS Feed</a></td></tr>",
   "show:<tr><td colspan=\"2\">__location__: <a href=\"__link__\">__title__</a> at ",
   "show:<a href=\"__website__\" target=\"_blank\">",
   "show:__organization__",
   "show:</a><!-- __website__ -->",
   "show:</td></tr>"
   );
?>

                  </table>

                  <p class="footnote">Find a Job:

<?
  getContent(
    "job",
    "display:list",
    "howmanydays:120",
    "groupby:category",
    "howmany_category:3",
    "group_show:<a href=\"/job-category/__slug__/\">__title__</a>,  "
  );
?>
                      <a href="/job-board/">All Jobs</a>
                  </p>

               </div> <!-- #content -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/sidebar.php") ?>
            </div> <!-- #content-wrap -->
         </div> <!-- #container-inner -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/footer.php") ?>
      </div> <!-- #container -->
   </body>
</html>