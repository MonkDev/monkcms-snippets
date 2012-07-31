<?php
	
	// Default Blog Post Image
	// Use an image Custom Field for Blogs to specify a default image for your posts.
	
  $default_post_image = 
  getContent(
    'blog',
    'display:auto',
    "before_show_postlist:__customdefaultpostimage width='150' height='100'__",
    'noecho'
  );

?> 


<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$('div.post a.postimage:not(:has(img))').each(function(){
		$(this).append('<img src="<?=$default_post_image?>" width="150" height="100" alt="default-post-image" />');
	});
});
// ]]>
</script>


<!-- EXAMPLE HTML -->

<div class="post">
	<a class="postimage">
		<img src="/mediafiles/post-1.jpg" alt="Image description" />
	</a>
</div>
<div class="post">
	<a class="postimage">
		<!-- jQuery will add the image here -->
	</a>
</div>
<div class="post">
	<a class="postimage">
		<img src="/mediafiles/post-3.jpg" alt="Image description" />
	</a>
</div>