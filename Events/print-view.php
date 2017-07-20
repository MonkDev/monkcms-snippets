<?php require_once($_SERVER["DOCUMENT_ROOT"]."/monkcms.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link href='http://fonts.googleapis.com/css?family=Lato:400,700,400italic%7CVollkorn:400italic' rel='stylesheet' type='text/css'>
  
  <style>
  
  body {
    font-family: 'Lato', sans-serif;
  }

  /* -------- Calendar ------- */
  .calendar {
    width: 100%;
    table-layout: fixed;
  }
  
  .calendar th {
    text-align: center;
    padding: 10px;
    line-height: 1.1;
  }
  
  .calendar td {
    padding: 3px;
    height: 75px;
    /* will grow ... not sure how but it does */
    border: 1px solid;
    vertical-align: top;
  }
  
  .calendar td > a {
    /* this is the clickable number */
    font-weight: bold;
    text-decoration: none;
    line-height: 1.1;
    display: block;
    padding: 3px;
    float: left;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    margin-bottom: 5px;
  }
  
  .calendar td ul {
    margin: 0 0 0 15px;
    padding: 0;
    clear: both;
    list-style-type: none;
  }
  
  .calendar td ul li {
    margin: 0 0 15px 0;
    line-height: 1.1;
  }
  
  a {
    color: black;
    text-decoration: none;
    cursor: text;
    pointer-events: none;
}
  </style>
</head>
<body>
  <?php
    getContent(
      "event",
      "display:calendar",
      "numberOfMonths:12",
      "recurring:yes",
      "eventTitles:inDay",
      "event_title_show_time:yes",
      "enablepast:yes",
      "nextPrev:&raquo;,&laquo;",
      "headingletters:4"  
    );
  ?>
</body>
</html>
