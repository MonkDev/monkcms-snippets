<?php

  // Open Graph
  getContent(
  'media',
  'display:detail',
  'find:'.$_GET['nav'],
  'label:header',
  'show:<!-- Open Graph -->' . "\n",
  'show:<meta property="og:image" content="__imageurl width=\'1200\' height=\'630\'__">' . "\n",
  'show:<meta property="og:image:width" content="1600">' . "\n",
  'show:<meta property="og:image:height" content="630">' . "\n"
  );

?>
<?php

  // Open Graph
  getContent(
  'event',
  'display:detail',
  'find:'.$_GET['slug'],
  'show:<!-- Open Graph -->' . "\n",
  'show:<meta property="og:site_name" content="'.trim($MCMS_SITENAME).'">' . "\n",
  'show:<meta property="og:type" content="article">' . "\n",
  'show:<meta property="og:title" content="__title__">' . "\n",
  'show:<meta property="og:url" content="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">' . "\n",
  'show:<meta property="og:description" content="__preview__">' . "\n",
  'show:<meta property="og:image" content="__imageurl width=\'1200\' height=\'630\'__">' . "\n",
  'show:<meta property="og:image:width" content="1600">' . "\n",
  'show:<meta property="og:image:height" content="630">' . "\n"
  );

?>
<?php

  // Open Graph
  getContent(
  'article',
  'display:auto',
  'howmany:1',
  'show_detail:<!-- Open Graph -->' . "\n",
  'show_detail:<meta property="og:site_name" content="'.trim($MCMS_SITENAME).'">' . "\n",
  'show_detail:<meta property="og:type" content="article">' . "\n",
  'show_detail:<meta property="og:title" content="__title__">' . "\n",
  'show_detail:<meta property="og:url" content="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">' . "\n",
  'show_detail:<meta property="og:description" content="__preview__">' . "\n",
  'show_detail:<meta property="og:image" content="__imageurl width=\'1200\' height=\'630\'__">' . "\n",
  'show_detail:<meta property="og:image:width" content="1600">' . "\n",
  'show_detail:<meta property="og:image:height" content="630">' . "\n"
  );

?>
<?php

  // Open Graph
  getContent(
  'sermon',
  'display:auto',
  'howmany:1',
  'show_detail:<!-- Open Graph -->' . "\n",
  'show_detail:<meta property="og:site_name" content="'.trim($MCMS_SITENAME).'">' . "\n",
  'show_detail:<meta property="og:type" content="article">' . "\n",
  'show_detail:<meta property="og:title" content="__title__">' . "\n",
  'show_detail:<meta property="og:url" content="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">' . "\n",
  'show_detail:<meta property="og:description" content="__preview__">' . "\n",
  'show_detail:<meta property="og:image" content="__imageurl width=\'1200\' height=\'630\'__">' . "\n",
  'show_detail:<meta property="og:image:width" content="1600">' . "\n",
  'show_detail:<meta property="og:image:height" content="630">' . "\n"
  );

?>
<?php

  // Open Graph
  getContent(
  'blog',
  'display:auto',
  'howmany:1',
  'show_detail:<!-- Open Graph -->' . "\n",
  'show_detail:<meta property="og:site_name" content="'.trim($MCMS_SITENAME).'">' . "\n",
  'show_detail:<meta property="og:type" content="article">' . "\n",
  'show_detail:<meta property="og:title" content="__blogposttitle__">' . "\n",
  'show_detail:<meta property="og:url" content="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">' . "\n",
  'show_detail:<meta property="og:description" content="__blogsummary__">' . "\n",
  'show_detail:<meta property="og:image" content="__imageurl width=\'1200\' height=\'630\'__">' . "\n",
  'show_detail:<meta property="og:image:width" content="1600">' . "\n",
  'show_detail:<meta property="og:image:height" content="630">' . "\n"
  );

?>
