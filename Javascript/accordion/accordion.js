/*============================================
 *
 *                 accordion()
 *
 *=============================================
 *
 * This controls user defined accordions
 *
 * @return - null
 *
 */
function accordion() {
	var allPanels = $('.accordiontext');
	$('.accordiontrigger').each(function() {
		$(this).append('<span class="accordionstatus"> [+]</span>');
	});
	$('.accordiontrigger').click(function() {
		$this = $(this);
		$target = $this.next('.accordiontext');
		$(this).toggleClass('active');
		if (!$target.hasClass('active')) {
			allPanels.slideUp(function() {
				allPanels.removeClass('active')
			});
			$('.accordionstatus').text(' [+]');
			$this.find('.accordionstatus').text(' [–]');
			allPanels.prev('.accordiontrigger').find('.plus-icon').removeClass('hidden');
			$target.slideDown(function() {
				$target.addClass('active')
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
