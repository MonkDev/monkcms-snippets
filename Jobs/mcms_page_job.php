<?
  require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php");
  $_GET['nav'] = 'p-29805';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>

      <title><?php include($_SERVER["DOCUMENT_ROOT"]."/includes/site-name.php") ?> > Job Board > <? getContent(
                        "job",
                        "display:detail",
                        "find_org:".$_GET['org'],
                        "find:".$_GET['job'],
                        "find_year:".$_GET['year'],
                        "find_month:".$_GET['month'],
                        "find_day:".$_GET['day'],
                        "show:__title__ at __organization__"
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

<?
getContent(
                  "job",
                  "display:detail",
                  "find_org:".$_GET['org'],
                  "find:".$_GET['job'],
                  "find_year:".$_GET['year'],
                  "find_month:".$_GET['month'],
                  "find_day:".$_GET['day'],
                  "show:<h3>__title__ at __organization__</h3>\n",
                  "show:<ul>",
                  "show:<li><strong>Posted:</strong> __postdate format='n/j/Y'__</li>\n",
                  "show:<li><strong>Location:</strong> __location__</li>\n",
                  "show:<li><strong>URL:</strong> <a href=\"__website__\" target=\"_blank\">__website__</a></li>\n",
                  "show:<li><strong>Salary/Range:</strong> __budget__</li>\n",
                  "show:<li><strong>Hours:</strong> __hours__</li>\n",
                  "show:</ul>",
                  "show:<h5>Description:</h5><p>__description__</p>\n",
                  "show:<p><strong>To Apply:</strong> __contact__</p>\n",
                  "show:<p class=\"footnote\">Go to <a href=\"/job-category/__categoryslug__\">__category__</a> projects / <a href=\"/job-board/\">All Jobs</a></p>"
                  );
?>

               </div> <!-- #content -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/sidebar.php") ?>
            </div> <!-- #content-wrap -->
         </div> <!-- #container-inner -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/footer.php") ?>
      </div> <!-- #container -->
   </body>
</html>