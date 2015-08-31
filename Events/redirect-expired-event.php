<?php

  /*

    REDIRECT RECURRING EVENT TO LATEST OCCURRENCE

    To use:
    Include at the top of the event detail page (most likely ekk_eventpage.php),
    just after the require of monkcms.php. Verify that headers are not already
    being sent by an earlier script including blank space, etc.

    Note:
    Only works on event URLs including an ID: [EVENT-ID]-YYYY-MM-DD-slug

  */

  function updatedEventRedirect($event_slug)
  {
      $event_id = preg_replace('/^(\d{1,10})-(\d{4}-\d{2}-\d{2})-([a-z0-9\-]+)$/', '$1', $event_slug);

      // query for upcoming event
      $event_slug_new = getContent(
          'event',
          'display:detail',
          'find_id:' . $event_id,
          'show:__url__',
          'noecho',
          'noedit'
      );

      $event_slug_new = preg_replace('/^event\//', '', trim($event_slug_new, '/'));

      // if new event is different, redirect to new URL
      if (($event_id && $event_slug && $event_slug_new) && ($event_slug != $event_slug_new)) {
          $redirect_url = 'http://'.$_SERVER['HTTP_HOST'].'/event/'.$event_slug_new.'/';
          header('Location:' . $redirect_url);
      }
  }

  updatedEventRedirect($_GET['slug']);

?>