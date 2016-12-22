<?php

  /*
    EVENT DATE REDIRECT
    Chris Ullyott <chris@monkdevelopment.com>

    For recurring events. Redirects passed event pages to the latest (most quickly
    approaching) event. If the given event is in the future (has not passed), no
    redirect will be run.

    To use:
    Include at the top of the event detail page (most likely ekk_eventpage.php),
    just after the require of monkcms.php. Verify that headers are not already
    being sent by an earlier script including blank space, etc.

    Note:
    The expected $eventSlug should include the event ID and date, such as:
    "780666-2016-02-04-financial-peace-university"
  */

  function updatedEventRedirect($eventSlug)
  {
    preg_match_all('/^(\d{1,10})-(\d{4}-\d{2}-\d{2})-([a-z0-9\-]+)$/', $eventSlug, $matches);

    $eventId   = $matches[1][0];
    $eventDate = $matches[2][0];
    $eventTime = strtotime($eventDate);
    $todayTime = strtotime(date('Y-m-d'));

    if (is_numeric($eventTime) && ($eventTime < $todayTime)) {
      $latestEventUrl = getContent(
        'event',
        'display:detail',
        'find_id:' . $eventId,
        'show:__url__',
        'noecho',
        'noedit'
      );

      $latestEventSlug = preg_replace('/^event\//', '', trim($latestEventUrl, '/'));

      if (($eventSlug != $latestEventSlug) && ($latestEventSlug != '')) {
        $redirectUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/event/' . $latestEventSlug . '/';
        header('Location:' . $redirectUrl);
      }
    }
  }

  updatedEventRedirect($_GET['slug']);

?>
