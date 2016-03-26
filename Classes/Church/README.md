# class Church

Provides data and methods for implementing a multi-campus site. Using the _Churches_ module in Ekklesia 360 and  the current _Content_ class, we build a data model of a `Church` which contains multiple campuses:

```
Array
(
    [north-campus] => Array
        (
            [slug] => north-campus
            [name] => North Campus
            [description] => Come join us for our Easter Sunrise Service at 6:00 am!
            [customhomepageid] => 603465
        )
    [east-campus] => Array
        (
            [slug] => east-campus
            [name] => East Campus
            [description] => We are a community of believers seeking to build God's kingdom here in East County.
            [customhomepageid] => 465712
        )
)

```

Other methods are available for getting and setting the _current campus_ via cookie:

### getCampuses()

Requests the data for all campuses using `getContent()`. To avoid making multiple API calls for this data, we'll only use this method once (inside the class itself) and make its contents accessible with the property `campuses`.

It'll now be easy to build a campus selector, using the query `?setCampus` in our links:

```
<?php

  foreach ($Church->campuses as $c) {
    $output  = '<li class="campus">';
    $output .= '<a href="?setCampus=' . $c['slug'] . '">';
    $output .= '<span class="icon-pin"></span>';
    $output .= $c['name'];
    $output .= '</a>';
    $output .= '</li>';
    echo $output;
  }

?>
```

### getCampus()

Get the current campus from the cookie. If `$default == true`, the default campus will be returned if no cookie is set.

### setCampus()

Set the desired campus in a cookie.

### setCampusAndRedirect()

Set the campus with `setCampus()` and also redirect to the campus homepage.

## Working with Javascript
It's recommended to include some of the campus information as meta tags for the site, such as:

```
<meta name="campus-cookie" content="<?= Church::CAMPUS_COOKIE ?>" />
<meta name="campus" content="<?= $Church->getCampus()['slug'] ?>" />
```

This will allow you to access these values with Javascript/jQuery, like:
```
var campus_cookie = $('meta[name=campus-cookie]').attr('content');
var campus = $('meta[name=campus]').attr('content');
```
