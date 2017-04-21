<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/monkcms.php'); ?>
<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
  <meta name="robots" content="noindex, nofollow">

  <title><?= $MCMS_SITENAME ?></title>

  <link href="http://fonts.googleapis.com/css?family=Kotta+One%7CCantarell:400,700" rel="stylesheet" type="text/css">
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <link rel="stylesheet" href="assets/styles.css" />

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="assets/page.js"></script>
</head>
<body>

  <header class="container">
    <section class="content">
      
    <?php
      getContent( 
        "section",
        "display:detail",
        "find:maintenance-content",
        "show:__text__"
      );
    ?>

    </section>
  </header>

<?php getContent('site', 'display:detail', 'show:__trackingcode__'); ?>

</body>
</html>
