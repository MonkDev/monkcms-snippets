# cache_data()

Reads and writes data in a secondary cache folder apart from `/monkcache`. Creates a folder at `/_cache` and writes data in a user-defined directory within.

When first using `'mode' => 'read'`, the function either returns the data, or `false` if the cache has expired. If expired, new data must be written to the same directory using `'mode' => 'write'`. 

## Example

```
/* get cached data */

$instagramFeed = cache_data(array(
	'mode'=>'read',
	'path'=>'instagram/feed'
));


/* if data has expired */

if(!$instagramFeed){	
	
	$instagramFeed = (result of your query) 
	
	cache_data(array(
		'mode'=>'write',
		'path'=>'instagram/feed',
		'data'=>$instagramFeed
	));
	
}
```

## Options

### Mode

`read` or `write`.

## Path

Define a directory name or path relative to the default root folder, which will be `/_cache`.

### Expire

Sets the cache's expiration frequency. 

`hourly`

`nightly` (default)

`weekly`

`monthly`

### Data

A variable containing the data to store if `write` is used.
