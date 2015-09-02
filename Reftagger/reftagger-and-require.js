// Add the following snippet to the top of scripts.php
	<script>
	function runReftagger() {
	    window.refTagger = {
	        settings: { bibleVersion: "NIV", noSearchTagNames: ["h1","h2","h3"] }
	    };
	    (function(d, t) {
	        var g = d.createElement(t),
	            s = d.getElementsByTagName(t)[0];
	        g.src = '//api.reftagger.com/v2/RefTagger.js';
	        g.id = 'reftagger';
	        s.parentNode.insertBefore(g, s);
	    }(document, 'script'));
	}
	</script>

// Add the following to global.js at the top of init:function

	//Initialize Reftagger
	runReftagger();

// Make sure to remove old references to Reftagger from main.js

// Create the following file (reftagger.js) and save in the Vendor or Library folder (depending on site folder structure)
// You can remove or add options depending on client request.  For default implementation I would remove customStyle section.

	/* 
	*
	*reftagger.js
	*
	*Loads required RefTagger settings
	*
	*/

	var refTagger = {
		settings: {
			addLogosLink: true,
			bibleVersion: "NIV",
			convertHyperlinks: true,
			logosLinkIcon: "dark",			
			socialSharing: ["twitter","facebook","google"],
			tagChapters: true,
			customStyle : {
				heading: {
					backgroundColor : "#f2f2f2",
					color : "#ec8b2a"
				},
				body   : {
					moreLink : {
						color: "#ec8b2a"
					}
				}
			}
		}
	};
	(function(d, t) {
		var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
		g.src = "//api.reftagger.com/v2/RefTagger.js";
		s.parentNode.insertBefore(g, s);
	}(document, "script"));
