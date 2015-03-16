javascript: (function() {

	function callback() {

		(function($) {
			var jQuery = $;
			var ftp_host = encodeURIComponent($('#metaHeaderFTPSettings tr:contains("Host:") input').val());
			var ftp_user = encodeURIComponent($('#metaHeaderFTPSettings tr:contains("UN:") input').val());
			var ftp_pass = encodeURIComponent($('#metaHeaderFTPSettings tr:contains("PW:") input').val());
			var ftp_path = encodeURIComponent($('#metaHeaderFTPSettings tr:contains("Path:") input').val());
			var ftp_protocol = $('#metaHeaderSiteConfig a:contains("Launch")').attr('href').split('://')[0].toUpperCase();
			var site_domain = $('#meta-header ul li:eq(2) a').attr('href').replace(/(http:\/\/)?(https:\/\/)?(www.)?\/?/, '').replace('/', '');
			var dev_url = 'devSetup://' + 'set.up' + '?domain=' + site_domain + '&user=' + ftp_user + '&pass=' + ftp_pass + '&path=' + ftp_path + '&protocol=' + ftp_protocol + '&host=' + ftp_host;
			console.log(dev_url);
			if (ftp_host && ftp_user && ftp_pass) {
				window.location.href = dev_url;
			} else {
				alert('Bookmarklet could not initialize.');
			}
		})(jQuery.noConflict(true));
	}

	var s = document.createElement("script");
	s.src = "https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js";
	if (s.addEventListener) {
		s.addEventListener("load", callback, false);
	} else if (s.readyState) {
		s.onreadystatechange = callback;
	}
	document.body.appendChild(s);

})();