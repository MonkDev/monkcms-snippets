<? require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php"); ?>
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

   getContent(
   "page",
   "find:".$_GET['nav'],
   "show:<h3>__title__</h3>",
   "show:<div id=\"text\">__text__</div>"
   );
?>

                   <p class="back">Find a Job:
<?
  // A list of categories with current jobs
  $x = getContent(
   "job",
   "display:list",
   "groupby:category",
   "howmany_category:1",
   "howmanydays:120",
   "group_show:<a href=\"/job-category/__slug__/\">__title__</a>~~~~",
   "noecho"
   );

   $x = explode('~~~~', $x, -1);
   natsort($x);
   foreach ($x as $category) {
     echo $category . ', ';
   }

?>
                  <a href="/job-board/">All Jobs</a></p>

<table class="jobs">
   <col span="1" class="listing" />
    <col span="1" class="rss" />
    <thead>
       <tr>
           <th class="heading">The Latest Jobs</th>
            <th class="rss"></th>
        </tr>
   </thead>
   <tbody>

<? getContent(
   "job",
   "display:list",
   "howmany_category:5",
   "howmanydays:120",
   "groupby:category",
   "order:recent",
   "before_show:<tr><td>There are __count__ jobs right now</td><td></td></tr>",
   "group_show:<tr class=\"even view-all\"><td><a href=\"/job-category/__prevslug__/\"> View all __prevtitle__ Jobs</a></td><td></td></tr>",
   "group_show:<tr><td><!-- __prevtitle__ --></td><td></td></tr>",
   "group_show:<tr class=\"even\"><td><a href=\"/job-category/__slug__/\">__title__</a></td><td class=\"rss\"><a href=\"/media/jobsCat__slug__.xml\">RSS Feed</a></td></tr>",
   "show:<tr><td colspan=\"2\">__location__: <a href='__link__'>__title__</a> at ",
   "show:<a href=\"__website__\" target=\"_blank\">",
   "show:__organization__",
   "show:</a><!-- __website__ -->",
   "show:</td></tr>"
   );
?>

   </tbody>
</table>

                  <p class="footnote">Subscribe to the full <a href="/media/jobs.xml" title="RSS Feed">RSS Feed</a> and know when new jobs are posted.</p>

               </div> <!-- #content -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/sidebar.php") ?>
            </div> <!-- #content-wrap -->
         </div> <!-- #container-inner -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/footer.php") ?>
      </div> <!-- #container -->
   </body>
</html>