<?php 
    
    // MonkCMS
    require_once($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php");
    
    // class Content
    require_once($_SERVER["DOCUMENT_ROOT"] . "/_lib/monk/content.class.php");
    
    // Plain text 
    header("Content-type: text/xml");
    
    // Get array of sermon data
    $sermons = Content::getContentArray(array(
        'module' => 'sermon',
        'display' => 'list',
        'params' => array(
            'howmany' => 50
        ),
        'tags' => array(
            'id',
            'title',
            "date format='r'",
            'imageurl',
            'preview',
            'audiourl',
            'videourl',
            'videoplayer'
        )
    ));
    
    // Add custom array nodes
    foreach ($sermons as $k => $i) {
        $sermons[$k]['videourlExt'] = pathinfo($i['videourl'], PATHINFO_EXTENSION);
    }
    
    // Build feed
    $xml_lines = array();
    $xml_lines[] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    $xml_lines[] = "<feed>";
    $xml_lines[] = "<resultLength>" . count($sermons) . "</resultLength>";
    $xml_lines[] = "<endIndex>" . count($sermons) . "</endIndex>";
    foreach ($sermons as $k => $s) {
        $xml_lines[] = "<item sdImg=\"{$s['imageurl']}\" hdImg=\"{$s['imageurl']}\">";
        $xml_lines[] = "<title>{$s['title']}</title>";
        $xml_lines[] = "<contentId>{$s['id']}</contentId>";
        $xml_lines[] = "<contentType>Talk</contentType>";
        $xml_lines[] = "<contentQuality>HD</contentQuality>";
        $xml_lines[] = "<media>";
        $xml_lines[] = "<streamFormat>{$s['videourlExt']}</streamFormat>";
        $xml_lines[] = "<streamQuality>HD</streamQuality>";
        $xml_lines[] = "<streamBitrate>1500</streamBitrate>";
        $xml_lines[] = "<streamUrl>{$s['videourl']}</streamUrl>";
        $xml_lines[] = "<audioUrl>{$s['audiourl']}</audioUrl>";
        $xml_lines[] = "</media>";
        $xml_lines[] = "<synopsis>";
        $xml_lines[] = "<![CDATA[{$s['preview']}]]>";
        $xml_lines[] = "</synopsis>";
        $xml_lines[] = "<genres>Clip</genres>";
        $xml_lines[] = "</item>";
    }
    $xml_lines[] = "</feed>";

    // Print feed
    echo implode("\n", $xml_lines);
