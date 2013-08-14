<!-- set what the link destination for the life feed should be -->
<?php $liveLink = "/watch-live" ?>

<!-- put the live link in a javascript variable so that countdown-manager.js can use it -->
<script type="text/javascript">
    var liveLink = "<?php echo $liveLink; ?>";
</script>


<!-- expected countown html structure -->
<div id="countdown">
    
  <a id='countdown-link' href='<?php echo $liveLink; ?>'>          
    <span class='lf-label'>Watch us live in</span>
    <div id='cnt' class='countdown cnt'>
        00<span>h</span> 00<span>m</span> 00<span>s</span>
    </div>
  </a>

  <a id='countdown-live'  href='<?php echo $liveLink; ?>'>
    <span class='lf-label'>Watch the Live Service 
  </a>
  
</div><!-- #countdown -->

<!-- include the jquery plugin that does all the work -->
<script  type="text/javascript" src="jquery.countdown.min.js"></script>

<!-- include the main control file. This file calls /_ajax/countdown.php so make sure that's included -->
<script  type="text/javascript" src="countdown-manager.js"></script>

<!-- initiate the countdown timer -->
<script type="text/javascript">
	initCountdown();
</script>