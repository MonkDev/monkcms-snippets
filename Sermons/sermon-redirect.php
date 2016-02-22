<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php"); ?>
<?php

  /*
    For ekk_sermonpage.php
    Redirects the default sermon detail URL to the custom detail page

    To implement, add this script to a file called "ekk_sermonpage.php".
    The CMS uses an htaccess rule linking "/sermon/the-sermon-slug" to this file.
  */


  /**
   * Build a path from a list of parts
   */
  function path()
  {
    $path = '';
    foreach (func_get_args() as $key => $p) {
      if ($p && $key == 0) {
        $path .= rtrim($p, '/') . '/';
      } elseif ($p) {
        $path .= trim($p, '/') . '/';
      }
    }
    return $path;
  }


  /**
   * Build a sermon detail page URL, knowing the custom sermons page
   * (If the Page cannot be found, no redirect will be done)
   */
  function customSermonRedirect($sermonSlug, $pageId, $detailPath = '')
  {
    $domain = 'http://' . $_SERVER['HTTP_HOST'];
    $sermonPage = getContent(
      'page',
      'display:detail',
      'find:p-' . $pageId,
      'show:__url__',
      'noecho',
      'noedit'
    );
    if ($sermonPage) {
      header('Location:' . path($domain, $sermonPage, $detailPath, $sermonSlug));
    } else {
      return false;
    }
  }


  /**
   * Do the redirect
   */
  customSermonRedirect($_GET['sermonslug'], 637521, 'detail');

?>