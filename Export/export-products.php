<!-- A basic script for exporting a product list to an HTML table -->

<? require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>MonkCMS Product Export</title>
<body>
<table border="1" cellspacing="6" id="product-list">
<tr>
<th>Product Code</th>
<th>Product Name</th>
<th>Author</th>
<th>Current Price</th>
<th>Original Price</th>
<th>Type</th>
<th>Subscription</th>
<th>Publisher</th>
<th>Reference</th>
<th>Program Affiliation</th>
</tr>
<?
getContent(
   "products",
   "display:list",
   "order:title",
   "family:all",
   "product:all",
   "howmany_product:100",
   "howmany_family:1",
   //"howmany:100",  
   //"page:".$_GET['page'],
   "show_productlist:<tr>",
   "show_productlist:<td>",
   "show_productlist:__productcode__",
   "show_productlist:</td>",
   "show_productlist:<td>",
   "show_productlist:__producttitle__",
   "show_productlist:</td>",
   "show_productlist:<td>",
   "show_productlist:__skuauthor__",
   "show_productlist:</td>",
   "show_productlist:<td>",
   "show_productlist:__productprice__",
   "show_productlist:</td>",
   "show_productlist:<td>",
   "show_productlist:__productOriginalprice__",
   "show_productlist:</td>",
   "show_productlist:<td>",
   "show_productlist:__type__",
   "show_productlist:</td>",
   "show_productlist:<td>",
   "show_productlist:__subscription__",
   "show_productlist:</td>",
   "show_productlist:<td>",
   "show_productlist:__publisher__",
   "show_productlist:</td>",
   "show_productlist:<td>",
   "show_productlist:__reference__",
   "show_productlist:</td>",
   "show_productlist:<td>",
   "show_productlist:__affiliation__",
   "show_productlist:</td>",
   "show_productlist:</tr>",
   "nocache"
   );
?>
</table>
</body>
</html>