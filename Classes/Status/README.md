# class Status

Provides site launch status booleans and helpers. Useful for the switching of functionalities that must be different in each stage of the site's _status_.

```
$siteStatus = new Monk_Site_Status(array(
  'easy_edit' => isEasyEditOn()
));

if ($siteStatus->isLive) {
  echo 'This site live!';
}
```

An Ekklesia 360 theme site build, as opposed to a custom site build, maintains the 
same code throughout its three changes in _status_ – throughout each stage in its "life cycle". These statuses are:

#### Demo

A demonstration site for the Ekklesia 360 theme portfolio. When the property `isDemo` is true, we can make functionalities like the Color Picker accessible to the public when normally this is a function available only to logged-in users. Or, we can show purchase links and product information.

#### Staging

A copy of the demo site which is now on the customer's server, for futher development and use with the CMS. For this stage, we can add checks for `isLive == false` to postpone running certain certain peices of code until launch.

In staging, the method `robotsMetaTag()` will output an HTML meta tag with `noindex, nofollow` to keep search engines from indexing the site. 

#### Live

A site that has been launched into production. When `isLive == true`, we can enable functionality or show content that should have not been used in staging.
