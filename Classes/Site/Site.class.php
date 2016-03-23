<?php

/**
 * Site
 *
 * Provides site helper methods.
 *
 * Dependencies:
 * - class Content
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Site
{

  // Site info (tracking code, timezone)
  public $info;

  /**
   * Construct the Site object.
   */
  public function __construct()
  {
    $this->info = $this->getSiteData();
  }

  /**
   * Get site info
   */
  private function getSiteData()
  {
    $siteData = Content::getContentArray(array(
      'module'  => 'site',
      'display' => 'detail',
      'tags'    => array(
        'logourl',
        'trackingcode',
        'timezone'
      )
    ));

    return $siteData;
  }

  /**
   * Get a page URL
   */
  public static function getPageURL($id)
  {
    $id = trim($id);

    if (preg_match('/^\d{1,}$/', $id)) {
      $id = 'p-' . $id;
    }

    $url = getContent(
      'page',
      'display:detail',
      'find:' . $id,
      'show:__url__',
      'noecho',
      'noedit'
    );

    if ($url) {
      return rtrim($url, '/');
    } else {
      return false;
    }
  }

}
