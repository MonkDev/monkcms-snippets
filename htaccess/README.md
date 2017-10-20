# Redirect a main domain to a subpage

**Use case:** Redirect _cool-domain.com_ to _church-site.com/cool-thing_

 - Point the _cool-domiain.com_ at the same IP Address as _church-site.com_ (In Cloudsites this would need to be created as an alias).
 - Put the below `.htaccess` rules in the _Root & Reseller_ box in the CMS htaccess module _(Site Config > Account > htaccess)_
```ApacheConf
# Domain Alias Redirecs
# Redirects a domain to a specific page. Alias domain "A" recordssss
# must be pointed to the same IP address as target domain.
RewriteEngine On
RewriteCond %{HTTP_HOST} ^(www\.)?aliasdomain\.com$
RewriteRule ^(.*)$ http://targetdomain.org/landing-page/ [R=301,L]
```

# Direct Download

**Use case:** Force a file to be downloaded, rather than play in the browser

```ApacheConf
<FilesMatch "my-soul-sings-out\.mp3$">
  ForceType application/octet-stream
  Header set Content-Disposition attachment
</FilesMatch>
```

# Password Protection

- Place the following in a _php file_ in the root folder of your site and then visit the url.
```php
header("Content-Type:text/plain");
$root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
echo "\nYour AuthUserFile line will be:\n\n";
echo "AuthUserFile " . str_replace('/content', '/auth', $root) . '/.htpasswd';
```
 - For security purposes, delete the file from the server as soon as you have the information needed.
 - The path returned will replace "/content" with "/auth", but if your own path to the ".htpasswd" file is different, it will need to be changed manually.

```ApacheConf
# PASSWORD-PROTECTION
# This protects only "/about-us/"
SetEnvIf Request_URI ^/about-us(\/?)$ require_auth=true

# This protects "/about-us/" and all child pages
SetEnvIf Request_URI ^/about-us/?(.*?)$ require_auth=true

# Authentication
AuthUserFile /web/auth/.htpasswd #UPDATE THIS AREA WITH WHAT YOU COPIED FROM THE FILE YOU CREATED
AuthName "This page is protected."
AuthType Basic
Require valid-user
Order allow,deny
Allow from all
Deny from env=require_auth
Satisfy any
```
