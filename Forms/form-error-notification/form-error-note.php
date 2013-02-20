<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){

	<?php if(isset($_GET['ferr']) && $_GET['ferr']!=''){ ?>
	$('body').append('<div id="formErrorNotice">Your form submission is not complete.<a href="#">close</a></div>');
	$('#formErrorNotice').fadeIn('fast').css('left',Math.round(($(window).width()-$('#formErrorNotice').width())/2));
	$('#formErrorNotice a').click(function(e){
		e.preventDefault();
		$(this).parent().fadeOut('fast');
	});
	<?php } ?>

});
// ]]>
</script>