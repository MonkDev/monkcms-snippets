# getContentArray()

### A wrapper for getContent() including getContentArray(), useful for getting array output from the CMS.

@author - Chris Ullyott <chris@monkdevelopment.com>
@url - https://github.com/MonkDev/monkcms-snippets/
@demo - http://files.monkdev.com/chris/getContentArray-Demo-Small.mp4

##Module
The MonkCMS module to be queried.

##Display
The display mode. Default is `detail`.

##Params
An array of normal MonkCMS API parameters and their values, such as "find", "howmany". Can be written in any of the following three formats:
1. `array('find:30871', 'howmany:10')`
2. `array('find' => 30871, 'howmany' => 10)`
3. `"find:30871, howmany:10"`

##Show
Default show tag is `show`, but you can set to `show_postlist` or etc. for various modules. There are no "before_show" / "after_show" capabilities.

##Tags
An array of API tags to include in the query, without the double underscores. Pass either as an array or as a comma-separated list.

##Keys
Sets the keys of the array with the value of one of the specified TAGS. Ideal with unique 'id' or 'slug' values (this does not work with `'display' => 'detail'`). For numerical keys, use `'keys' => false`.

## Output
Set to 'json' for JSON output.


## Tips

### Find
Pass "find" either as a first-level parameter, or within the "params" option.

### Booleans
Some tags that respond by outputting a single space are considered to be a boolean of TRUE. They are:
`__custom(.*?)__`  `__if(.*?)__`  `__is(.*?)__`

###Howmany
"howmany" => INTEGER will limit the number of items returned even where the API does not do this.

###Easy Edit
Easy Edit is disabled by default. Add the HTML for the Easy Edit links to your query by adding:
`'easyEdit' => true`

## Examples

###Example 1

```
$array = Content::getContentArray(array(
	'module' => 'media',
	'display' => 'list',
	'params' => array(
		'howmany' => 10
	),
	'tags' => 'name, filename, url, id'
));
```
	
	
###Example 2

```
$array = Content::getContentArray(array(
	'module' => 'blog',
	'display' => 'list',
	'params' => 'name:all,find_category:missions,howmany:10',
	'show' => 'show_postlist',
	'tags' => array(
		"blogposttitle",
		"__blogpostdate format='Y-m-d'__"
	),
	'output' => 'json'
));
```
	
	
###Example 3
	
```
$array = Content::getContentArray(array(
	'module' => 'page',
	'find' => 'giving',
	'params' => array(
		'nocache' => true
	),
	'tags' => 'name, slug, url, text',
	'easyEdit' => true
));
```


###Example 4

```
$array = Content::getContentArray(array(
	'module' => 'linklist',
	'display' => 'links',
	'params' => array('find:30871','howmany:3'), 
	'tags' => 'id, slug, name, url, description'
));
```
