<?php
/**
 * Monk_Site_Status
 *
 * Provides site launch status booleans and helpers. To take advantage of Easy Edit
 * helpers, pass easyEditOn() from monkcms.php when creating an object. Example:
 *
 * $siteStatus = new Monk_Site_Status(array(
 *   'easy_edit' => isEasyEditOn()
 * ));
 * if ($siteStatus->isLive) {
 *   echo 'This site live!';
 * }
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 * @version 1.2
 * @dependencies monkcms.php
 */

class Monk_Site_Status
{
    public $isLive;
    public $isDemo;
    public $easyEdit;
    public $wardrobeShow;
    public $wardrobeEnable;

    public function __construct($params)
    {
        $siteStatus           = self::getSiteStatus();

        $this->isLive         = $siteStatus['is_live'];
        $this->isDemo         = $siteStatus['is_demo'];

        $this->easyEdit       = $params['easy_edit'];
        $this->wardrobeShow   = 0;
        $this->wardrobeEnable = 0;

        // show/enable wardrobe (color picker)
        if ($this->easyEdit || $this->isDemo) {
            $this->wardrobeShow = 1;
            if (!$this->isDemo) {
                $this->wardrobeEnable = 1;
            }
        }
    }

    /**
     * Get status info for the site.
     *
     * @return array
     */
    private static function getSiteStatus()
    {
        $siteDetails = getcontent(
            'site',
            'display:detail',
            'show:__islive__ true',
            'show:||',
            'show:__isdemo__ true',
            'noecho',
            'noedit'
        );

        $siteDetails = explode('||', $siteDetails);

        $siteStatus = array();

        if ($siteDetails[0]) {
            $siteStatus['is_live'] = 1;
        } else {
            $siteStatus['is_live'] = 0;
        }

        if ($siteDetails[1]) {
            $siteStatus['is_demo'] = 1;
        } else {
            $siteStatus['is_demo'] = 0;
        }

        return $siteStatus;
    }

    /**
     * Output the robots meta tag for a client site in staging
     *
     * @return null
     */
    public function robotsMetaTag()
    {
        if (!$this->isLive && !$this->isDemo) {
            echo "<meta name=\"robots\" content=\"noindex, nofollow\" />\n";
        }
    }

    /**
     * Return class names for the color picker
     *
     * @return null
     */
    public function wardrobeClass()
    {
        $wardrobeClass = '';

        if ($this->wardrobeShow) {
            $wardrobeClass .= ' wardrobe-show';
        }
        if ($this->wardrobeEnable) {
            $wardrobeClass .= ' wardrobe-enable';
        }

        return $wardrobeClass;
    }

    /**
     * Return a class name for the color picker
     *
     * @return null
     */
    public function setupguideClass()
    {
        if (!$this->isLive && !$this->isDemo && $this->wardrobeShow) {
            return ' setup-guide-notice';
        }
    }

}

?>