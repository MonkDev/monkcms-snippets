
// Accordion
accordion('.accordiontrigger', '.accordiontext', true);


/*============================================
 *
 *                 accordion()
 *
 *=============================================
 *
 * Controls user-defined accordion content.
 *
 * To hide all other open content when an 
 * accordion link is clicked, set the "linked" 
 * param to true. 
 *
 */
function accordion(triggerClass, contentClass, linked) {
	if (typeof linked === undefined) {
		linked = false;
	}
	if ($(triggerClass).length > 0) {
		$(triggerClass).each(function() {
			$(this).append('<span class="accordionstatus"> [+]</span>');
			$(this).nextUntil(triggerClass, contentClass).wrapAll('<div class="accordionbellow">');
		});
		$(triggerClass).click(function() {
			var $this = $(this);
			var $target = $this.next('.accordionbellow');
			var $all = $('.accordionbellow');
			$(triggerClass).removeClass('active');
			$(this).toggleClass('active');
			if (!$target.hasClass('active')) {
				if (linked) {
					$all.slideUp(function() {
						$all.removeClass('active');
					}).prev(triggerClass).find('.plus-icon').removeClass('hidden');
				}
				$('.accordionstatus').text(' [+]');
				$this.find('.accordionstatus').text(' [-]');
				$target.slideDown(function() {
					$target.addClass('active');
				});
				$this.find('.plus-icon').addClass('hidden');
			} else {
				$target.slideUp(function() {
					$target.removeClass('active');
				});
				$this.find('.accordionstatus').text(' [+]');
			}
			return false;
		});
	}
}
