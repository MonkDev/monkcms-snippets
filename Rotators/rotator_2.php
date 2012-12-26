<?php
/* ======================================================= //

	ROTATOR #2 USING ROTATOR API + JQUERY CYCLE
	http://layouts.ekklesia360.com/rotators/2/

	http://developers.monkcms.com/article/rotators-api/
	http://jquery.malsup.com/cycle/

// ======================================================= */
?>

<div id="billboard">
<?php

	getContent(
		'rotator',
		'display:slides',
		'find:' . $_GET['nav'],
		'before_show:<div id="rotator" data-duration="__transitionms__">',
		'slide_show:<div class="slide">' . "\n",
		'slide_show:<a',
		'slide_show: href="__url__"',
		'slide_show:__ifurlnewwindow__target="_blank"',
		'slide_show:>',
		"slide_show:<img src='__imageurl width='718' height='370'__' alt=\"__title__\" width='718' height='370' />",
		'slide_show:</a>' . "\n",
		'slide_show:  <div class="overlay">' . "\n",
		'slide_show:    <h2>__title__</h2>' . "\n",
		'slide_show:    <p>__caption__</p>' . "\n",
		'slide_show:  </div>' . "\n",
		'slide_show:</div>' . "\n\n",
		'after_show:</div><!-- #rotator -->',
		'after_show:<ul id="rotatorbtns" class="clearfix"></ul>'
	);

?>
</div> <!-- #billboard -->

<script type="text/javascript" src="/_js/jquery.cycle.min.js"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){

	$('#rotator').cycle({
		slideExpr: 'div.slide',
		fx: 'fade',
		speed: 'slow',
		timeoutFn: function() {
			return $('#rotator').data('duration');
		},
		pager: '#rotatorbtns'
	});

});
// ]]>
</script>