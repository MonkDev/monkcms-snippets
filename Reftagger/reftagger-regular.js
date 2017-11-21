// Add the following snippet to the top of scripts.php or another file that would be globally included on every page.
// You could optionally remove the "<script> </script>" tags and place the javascript in a javascript file (global.js or main.js)
// The important thing here is that the code is loaded on every single page.
// If the client gives you a script to use instead, it means they have customized this, and you should use theirs rather than this example one.
<script>
  var refTagger = {
    settings: {
      bibleVersion: "ESV",
      tagChapters: true
    }
  };
  (function(d, t) {
    var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
    g.src = "//api.reftagger.com/v2/RefTagger.js";
    s.parentNode.insertBefore(g, s);
  }(document, "script"));
</script>

// Thats all folks!
