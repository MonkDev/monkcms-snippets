<?php

/**
 * Church
 *
 * Provides church information and campuses.
 *
 * Dependencies:
 * - class Content
 * - class Site
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */
class Church
{

    // The cookie name for the campus
    const CAMPUS_COOKIE = 'church_campus';

    // The path to the default CSS override file
    const CSS_OVERRIDE_PATH = '/_css/override.css';

    // Data for all campuses
    public $campuses;

    // Data for the current (or default) campus
    public $campus;

    // The slug of the default campus, if any
    public $defaultCampus;


    /**
     * Construct the Site object by setting the campus,
     * using the default campus if none is set.
     */
    public function __construct($defaultCampus)
    {
        // Set default campus
        if ($defaultCampus) {
            $this->defaultCampus = $defaultCampus;
        }

        // Request campus data
        $this->campuses = self::getCampuses();
        $this->campus = $this->getCampus(1);

        // Set the campus from a query string
        if (isset($_GET['setCampus'])) {
            $this->setCampusAndRedirect($_GET['setCampus']);
        }
    }

    /**
     * Get info for all campuses via the Churches module
     * The custom field "customhomepageid" is required if the campuses have
     * home pages.
     */
    private function getCampuses()
    {
        $args = array(
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
                'longitude'

                // custom fields
                // 'customhidelocation',
                // 'customhomepageurl',
                // 'customadditionaldescription'
            )
        );

        $campuses = Content::getContentArray($args);

        // If cache file is empty, make a new request
        if (!$campuses) {
            $args['params'][] = 'nocache';
            $campuses = Content::getContentArray($args);
        }

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
                return $this->campuses[$this->defaultCampus];
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
                $redirectTo = $url;
            } else {
                $redirectTo = '/';
            }

            $redirectUri = parse_url($redirectTo, PHP_URL_PATH);
            header('Location:' . 'http://' . $_SERVER['HTTP_HOST'] . $redirectUri);
            exit;
        } else {
            return false;
        }
    }

    /**
     * Get the current campus override CSS file based on cookie
     * If for any reason the campus-specific override file does not exist,
     * create one from a fresh copy of "override.css", using permissions "775"
     */
    public function getCampusCSS()
    {
        $r = $_SERVER['DOCUMENT_ROOT'];

        if ($this->getCampus()) {
            $pathInfo    = pathinfo(self::CSS_OVERRIDE_PATH);
            $campusFile  = $pathInfo['dirname'];
            $campusFile .= '/' . $pathInfo['filename'];
            $campusFile .= '-' . $this->getCampus()['slug'];
            $campusFile .= '.' . $pathInfo['extension'];

            if (!file_exists($r . $campusFile)) {
                $sourceCSS = file_get_contents($r . self::CSS_OVERRIDE_PATH);
                file_put_contents($r . $campusFile, $sourceCSS);
                chmod($r . $campusFile, 0775);
            }

            return $campusFile;
        } else {
            return self::CSS_OVERRIDE_PATH;
        }
    }
}
