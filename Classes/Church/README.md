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

### getCampus()

Get the current campus from the cookie. If `$default == true`, the default campus will be returned if no cookie is set.

### setCampus()

Set the desired campus in a cookie.

### setCampusAndRedirect()

Set the campus with `setCampus()` and also redirect to the campus homepage.

### getCampusButton() 

Build an HTML button which can set the campus when clicked.