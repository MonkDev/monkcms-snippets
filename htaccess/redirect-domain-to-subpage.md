# Redirect a main domain to a subpage

Use case: Redirect _cool-domain.com_ to _church-site.com/cool-thing_

```ApacheConf
#Domain Alias Redirecs
# Redirects a domain to a specific page. Alias domain "A" recordssss
# must be pointed to the same IP address as target domain.
RewriteEngine On
RewriteCond %{HTTP_HOST} ^(www\.)?aliasdomain\.com$
RewriteRule ^(.*)$ http://targetdomain.org/landing-page/ [R=301,L]
```
Point the _cool-domiain.com_ at the same IP Address as _church-site.com_ (In Cloudsites this would need to be created as an alias).

Put the above `.htaccess` rules in the _Root & Reseller_ box in the CMS htaccess module (Site Config > Account > htaccess)
