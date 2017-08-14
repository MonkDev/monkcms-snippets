JSON feeds for content
======================

Sermons
-------

*json_sermons.php*

Supports all getContent parameters via query string, such as:

- `?find_series=some-series-slug`
- `?find_category=comma,separated,category-slugs,no-spaces`
- `?find_group=group-slug`
- `?offset=12` (used to paginate through results)

If no parameters are added, we will default to show the most recent 50 sermons.

For full documentation on the API, see http://developers.monkcms.com/article/sermons-api/

Events
------

*json_events.php*

*json\_events\_custom.php*

Blogs
------

*json_blog.php*

Supports all getContent parameters via query string for the `show_postlist` api results.

the name of the blog slug must be included in the query string for any results to be returned.


Questions?
----------

contact [support@monkdevelopment.com](mailto:support@monkdevelopment.com)
