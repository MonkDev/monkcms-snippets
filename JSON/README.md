JSON feeds for content
======================

Sermons
-------

*json_sermons.php*

The following filters and query parameters can be used to manipulate the sermons feed

- `?series=some-series-slug`
- `?category=comma,separated,category-slugs,no-spaces`
- `?group=group-slug`
- `?limit=12` (how many do you want displayed)
- `?order=recent` (recent, oldest, preacher, series, series-title, category, title)
- `?hide_series=series-slug`
- `?hide_category=category-slugs`
- `?hide_group=group-slug`
- `?preacher=preacher-slug`
- `?tags=keyword-slugs`
- `?passage=passage-slug`
- `?offset=12` (used to paginate through results)

Query parameters can be chained by replacing subsequent ? with &.
For example:
```
json_sermons.php?offset=12&limit=12&order=oldest
```

If no parameters are added, we will default to show the most recent 50 sermons.

### Events

*json_events.php*

*json\_events\_custom.php*


Questions?
----------

contact [support@monkdevelopment.com](mailto:support@monkdevelopment.com)
