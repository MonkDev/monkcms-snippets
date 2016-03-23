<?php

/**
 * Church
 *
 * Provides church information and campuses.
 *
 * Dependencies:
 * - class Content
 * - class Site
 * - class Helper
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Church
{

  // The cookie name for the campus
  const CAMPUS_COOKIE = 'site_campus';

  // The slug of the default campus, if any
  const DEFAULT_CAMPUS = '';

  // Data for all campuses
  public $campuses;

  // Data for the current (or default) campus
  public $campus;


  /**
   * Construct the Site object by setting the campus,
   * using the default campus if none is set.
   */
  public function __construct()
  {
    // Request campus data
    $this->campuses = self::getCampuses();
    $this->campus = $this->getCampus(1);

    // Set a campus
    if (isset($_GET['setCampus'])) {
      $this->setCampusAndRedirect($_GET['setCampus']);
    }
  }

  /**
   * Get info for all campuses via the Churches module
   * The custom field "customhomepageid" is required if the campuses have
   * home pages.
   */
  public function getCampuses()
  {
    $campuses = Content::getContentArray(
      array(
        'module'  => 'church',
        'display' => 'list',
        'keys'    => 'slug',
        'tags'    => array(
          'slug',
          'name',
          'description',
          'email',
          'phone',
          'address',
          'imageurl',
          'pastor',
          'street',
          'street2',
          'city',
          'state',
          'zip',
          'latitude',
          'longitude',
          'customhidelocation',
          'customhomepageid',
          'customadditionaldescription'
        )
      )
    );

    return $campuses;
  }

  /**
   * Get the current campus from the cookie. If $default is true,
   * the default campus will be returned if no cookie is set.
   */
  public function getCampus($default = false)
  {
    $cookieValue = $_COOKIE[self::CAMPUS_COOKIE];
    $campusExists = isset($this->campuses[$cookieValue]);

    if ($campusExists) {
      return $this->campuses[$cookieValue];
    } else if (!$campusExists && $default) {
      return $this->campuses[self::DEFAULT_CAMPUS];
    } else {
      return false;
    }
  }

  /**
   * Set the desired campus in a cookie. $campus must be the slug of a Church
   * in the Churches module, or it will not be recognized.
   */
  public function setCampus($campus)
  {
    if (isset($this->campuses[$campus])) {
      $isCookieSet = setcookie(
        self::CAMPUS_COOKIE,
        $campus,
        strtotime('+30 days'),
        '/'
      );
    }

    if ($isCookieSet) {
      return $campus;
    } else {
      return false;
    }
  }

  /**
   * Set the desired campus in a cookie and redirect to its defined homepage.
   * If the cookie fails to set, or the campus homepage is not known, we'll
   * redirect back home.
   */
  public function setCampusAndRedirect($campus)
  {
    $cookieSet = $this->setCampus($campus);

    if ($cookieSet) {
      if ($url = Site::getPageURL($this->campuses[$campus]['customhomepageid'])) {
        Helper::redirect($url);
      } else {
        Helper::redirect('/');
      }
    } else {
      return false;
    }
  }

  /**
   * Create a button that can be used to set the campus. If the campus does not
   * have a landing page, you'll be redirected back home.
   */
  public function getCampusButton($campus, $class = '')
  {
    $campusButton = '';

    if (isset($this->campuses[$campus])) {
      $url = $_SERVER['REQUEST_URI'] . '?setCampus=' . $this->campuses[$campus]['slug'];
      $campusButton .= '<a';
      $campusButton .= ' class="' . $class . '"';
      $campusButton .= ' href="' . $url . '"';
      $campusButton .= '>';
      $campusButton .= 'Set as my campus';
      $campusButton .= '</a>';
    }

    return $campusButton;
  }

}
