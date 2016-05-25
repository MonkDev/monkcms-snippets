Roku Channel Sermons Feed
=========================

Use these snippets to create a Roku-compatible sermons feed. While some work will need to be done by the client to configure a Roku "Channel" beforehand, this code provides the content feed required for this app.

Add feed files
--------------

1. Add `categories.xml` to the site. This can be a static file, with the required attributes filled in.

2. Add `roku-sermons-feed.php`. Modify the feed according to the client's needs. 

Add htaccess rule
-----------------

To make this file available at a ".xml" URL, add this line to the site's _htaccess_ file:

```
# Roku sermons feed
RewriteRule ^mediafiles/roku-sermons-feed.xml/?$ /mediafiles/roku-sermons-feed.php [L]
```

### Roku documentation

https://sdkdocs.roku.com/display/sdkdoc/

https://github.com/rokudev/
