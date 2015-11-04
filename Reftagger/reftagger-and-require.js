// Make sure to remove old references to Reftagger from main.js or where ever the require.js configuration is located.
// We are bascially re-creating the reftagger implemenation since our old implementation no longer works with the Reftagger API.

// Add the following snippet to the top of scripts.php or another file that would be globally included on every page.
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
	
// Thats all folks!
