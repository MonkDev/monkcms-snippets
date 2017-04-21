Maintenance Splash Page
-----------------------

Redirect with htaccess like:

```
RewriteCond %{REQUEST_URI} !\.(jpg|png|css|js)$
RewriteCond %{REQUEST_URI} !^/maintenance\/?$
RewriteRule ^(.*)$ /maintenance/? [R=307,L]
```

Add your IP address as an additional RewriteCond to avoid being redirected while letting the rest of the world redirect.

```
RewriteCond %{REMOTE_ADDR} !^YOUR_IP_ADDRESS
```

You can find out what your own IP address is with sites like:

http://www.whatsmyip.org/

The full block of htaccess might look like:

```
RewriteCond %{REMOTE_ADDR} !^50.26.158.9
RewriteCond %{REMOTE_ADDR} !^68.224.168.150
RewriteCond %{REQUEST_URI} !\.(jpg|png|css|js)$
RewriteCond %{REQUEST_URI} !^/maintenance\/?$
RewriteRule ^(.*)$ /maintenance/? [R=307,L]
```

Note
====

The htaccess used in this example hasn't worked well on Monk One-Click servers.
