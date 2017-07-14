<?php

    /**
     * MCMS ROKU VIDEO FEED
     *
     * @author Chris Ullyott <chris@monkdevelopment.com>
     */

    require_once($_SERVER['DOCUMENT_ROOT'] . '/monkcms.php');

    $sermons = getContent(
        'sermon',
        'display:list',
        "show:__date format='j M Y'__",
        'json'
    );

    $sermons = $sermons['show'];

    // Remove items which don't have video.
    foreach ($sermons as $k => $i) {
        if (!trim($i['videourl'])) {
            unset($sermons[$k]);
        }
    }

    // Add custom nodes.
    foreach ($sermons as $k => $i) {
        $sermons[$k]['videourlExt'] = pathinfo($i['videourl'], PATHINFO_EXTENSION);
    }

    // Build feed.
    $xml = array();
    $xml[] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    $xml[] = "<feed>";
    $xml[] = "\t<resultLength>" . count($sermons) . "</resultLength>";
    $xml[] = "\t<endIndex>" . count($sermons) . "</endIndex>";

    foreach ($sermons as $k => $s) {
        $xml[] = "\t<item sdImg=\"{$s['imageurl']}\" hdImg=\"{$s['imageurl']}\">";
        $xml[] = "\t\t<title>{$s['title']}</title>";
        $xml[] = "\t\t<releaseDate>{$s['date']}</releaseDate>";
        $xml[] = "\t\t<contentId>{$s['id']}</contentId>";
        $xml[] = "\t\t<contentType>Talk</contentType>";
        $xml[] = "\t\t<contentQuality>HD</contentQuality>";
        $xml[] = "\t\t<media>";
        $xml[] = "\t\t\t<streamFormat>{$s['videourlExt']}</streamFormat>";
        $xml[] = "\t\t\t<streamQuality>HD</streamQuality>";
        $xml[] = "\t\t\t<streamBitrate>1500</streamBitrate>";
        $xml[] = "\t\t\t<streamUrl>{$s['videourl']}</streamUrl>";
        $xml[] = "\t\t\t<audioUrl>{$s['audiourl']}</audioUrl>";
        $xml[] = "\t\t</media>";
        $xml[] = "\t\t<synopsis>";
        $xml[] = "\t\t<![CDATA[{$s['preview']}]]>";
        $xml[] = "\t\t</synopsis>";
        $xml[] = "\t\t<genres>Clip</genres>";
        $xml[] = "\t</item>";
    }

    $xml[] = "</feed>";

    // Print feed
    header('Content-type: text/xml');
    echo implode("\n", $xml);
