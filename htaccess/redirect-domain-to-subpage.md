# Redirect a main domain to a subpage
s
If you need to redirect _cool-domain.com_ to _church-site.com/cool-thing_ you can accomplish this by following the steps below:

```apache
 Domain Alias Redirecs
# Redirects a domain to a specific page. Alias domain "A" recordssss
# must be pointed to the same IP address as target domain.
RewriteEngine On
RewriteCond %{HTTP_HOST} ^(www\.)?aliasdomain\.com$
RewriteRule ^(.*)$ http://targetdomain.org/landing-page/ [R=301,L]
```
