<?php
/**
 *  ===========================================================================
 *
 *                            CLASS Monk_Site_Status
 *
 *  ===========================================================================
 *
 *  Provides site launch status booleans.
 *
 *  Pass easyEditOn() via monkcms.php when creating object.
 *
 *  Example:
 *  $site_status = new Monk_Site_Status(array(
 *      'easy_edit' => isEasyEditOn()
 *  ));
 *  if($site_status->is_live){
 *      echo 'This site live!';
 *  }
 *
 *  @author       Chris Ullyott <chris@monkdevelopment.com>
 *  @dependencies monkcms.php
 */

class Monk_Site_Status
{
    public $is_live;
    public $is_demo;
    public $easy_edit;
    public $wardrobe_show;
    public $wardrobe_enable;

    public function __construct($params)
    {
        $siteStatus            = self::getSiteStatus();

        $this->is_live         = $siteStatus['is_live'];
        $this->is_demo         = $siteStatus['is_demo'];

        $this->easy_edit       = $params['easy_edit'];
        $this->wardrobe_show   = 0;
        $this->wardrobe_enable = 0;

        // show/enable wardrobe (color picker)
        if ($this->easy_edit || $this->is_demo) {
            $this->wardrobe_show = 1;
            if (!$this->is_demo) {
                $this->wardrobe_enable = 1;
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
    public function robots_meta_tag()
    {
        if(!$this->is_live && !$this->is_demo) {
            echo "<meta name=\"robots\" content=\"noindex, nofollow\" />\n";
        }
    }

    /**
     * Return class names for the color picker
     *
     * @return null
     */
    public function wardrobe_class()
    {
        $wardrobe_class = '';

        if($this->wardrobe_show) {
            $wardrobe_class .= ' wardrobe-show';
        }
        if($this->wardrobe_enable) {
            $wardrobe_class .= ' wardrobe-enable';
        }

        return $wardrobe_class;
    }

    /**
     * Return a class name for the color picker
     *
     * @return null
     */
    public function setupguide_class()
    {
        if(!$this->is_live && $this->wardrobe_show && !$this->is_demo) {
            return ' setup-guide-notice';
        }
    }

}

?>