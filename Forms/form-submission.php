<?php

	/*

	FORM OUTPUT EXAMPLE - SUBMISSION RESULTS
	Allows users to update their member account password,
	without needing the entire profile edit screen.

	Option must be checked inside Form options in CMS:
	"Allow form submissions to be viewed on the public site"

	Data API tags are examples. The correct tags will be "__form-[FEILD NAME SLUG]__"

	*/

	getContent(
		"form",
		"display:detail",
		"find:".$_GET['keyId'], // the submission key
		"show:<h2>__form-name__</h2>",
		"show:<p><strong>Date Submitted: </strong>__form-submit-date__</p>",
		"show:<p><strong>Email: </strong>__form-email__</p>",
		"show:<p><strong>Phone: </strong>__form-phone-number__</p>",
		"show:<p><strong>Address: </strong>__form-address__ __form-city__ ,__form-state__ __form-zip-code__</p>",
		"show:<p><strong>Comments: </strong>__form-comments__</p>"
	);

?>