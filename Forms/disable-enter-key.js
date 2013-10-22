// Disable the Enter/Return key for CMS forms
if ($(".monkForm").length > 0) {
	$(document).on("keypress", '.monkForm', function(e) {
		var keypress = e.keyCode || e.which;
		if (keypress == 13) {
			e.preventDefault();
			return false;
		}
	});
}