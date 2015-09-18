 /*============================================
 *
 *                 popupLinks()
 *
 *=============================================
 *
 * Sets up popup windows for SSL or other links
 *
 * @return - null
 *
 */

 function popupLinks(selector, width, height){
 	 if(!selector){
	 	selector = 'a[href*="ssl.monkdev.com"]';
	 }
	 if(!width){
	 	width = 550;
	 }
	 if(!height){
	 	height = 650;
	 }
	 $(selector).on('click', function(e){
			e.preventDefault();
			var href   = $(this).attr('href'),
					left   = (screen.width/2)-(width/2),
					top    = ((screen.height/2)-(height/2)*1.2);
			window.open(href, null, 'width='+width+', height='+height+', top='+top+', left='+left+', toolbar=0, location=0, status=1, scrollbars=1, resizable=1');
	 });
 }
