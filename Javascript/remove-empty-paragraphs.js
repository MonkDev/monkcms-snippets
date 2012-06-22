/* 
	
	REMOVE EMPTY PARAGRAPHS
	
	Removes single empty paragraphs from the document, 
	while allowing multiple empty paragraphs to remain.
	Helpful if empty paragraphs were once created to add 
	space in a document instead of using CSS.
	
	Before:
	<div id="content>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
		<p>&nbsp;</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
	</div>
	
	After:
	<div id="content>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
	</div>
	 
	
*/
$('#content p').filter(function(){

	// Remove single empty paragraphs
	if($.trim($(this).prev().html()) == '&nbsp;' || $.trim($(this).next().html()) == '&nbsp;'){
	} else {
		return $.trim($(this).html()) == '&nbsp;';
	}
		
}).remove();