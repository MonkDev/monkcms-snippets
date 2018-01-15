# Alert Message Bar

This will set up an Alert Message bar at the top of a client's webpage. See FBC Grove City (18336) for example.

## CMS Setup

### 1. Create a custom field called "Show Alert Message":

	Module: [Pages]
	Type: [Checkbox]
	Label: [Show Alert Message]
	API TAG: __custom[showalertmessage]__
### 2. If it doesn't already exist, create a custom field called "Background Color":
	Module: [Sections]
	Type: [Single Line Text]
	Label: [Background Color]
	API TAG: __custom[backgroundcolor]__
### 3. If it doesn't already exist, create a custom field called "More Link":
	Module: [Sections]
	Type: [Single Line Text]
	Label: [More Link]
	API TAG: __custom[morelink]__
### 4. Create a Section in the CMS called "Alert Message Bar":
	Name: [Alert Message Bar]
	Description (optional): [This alert/message bar will appear at the top of the website where this section exists.]
	Category (optional): [None]
	Content: [This is where the alert text should go.]
	Background Color (optional): [#F44336]
	More Link (optional): [URL] - The entire alert box will link to this URL
	
## Edit Files

### 1. Edit styles.css
Add this to the end of the file:

```
/* ALERT MESSAGE BOX */
	body.hasAlert {
		padding-top: 42px; /* set to the height of #alert */
	}
	#alert {
	    padding: 10px;
		text-align: center;
	    background-color: #f44336; /* fallback (could be set in monklet) */
	    color: white;
	}
	#alert p {
		margin: 0;
		padding: 0;
		font-weight: bold;
		color: #fff;
		line-height: initial;
	}
	#alert a {
		display: block;
		width: 97%;
		height: 100%;
	}

	/* The close button */
	#alert .closebtn {
	    margin-left: 15px;
	    color: white;
	    font-weight: bold;
	    float: right;
	    font-size: 22px;
	    line-height: 20px;
	    cursor: pointer;
	    transition: 0.3s;
	}
	#alert .closebtn:hover {
	    color: black;
	}
```

### 2. Add this to the top of header.php:

This should show as close to the opening &lt;body&gt; tag as possible.

```
<?php
// ALERT MESSAGE BOX
$showAlert = trim(getContent(
	"page",
	"display:detail",
	"find:".$_GET['nav'],
	"show:__customshowalertmessage__true",
	"noecho"
));
if ($showAlert == true){
	getContent(
		"section",
		"display:detail",
		"find:alert-message-bar",
		"show:<div id='alert'",
		"show: style='background-color:__custombackgroundcolor__'",
		"show:>",
		"show:<span class='closebtn' onclick='this.parentElement.style.display=none;'>×</span>",
		"show: <a href='__custommorelink__'>",
		"show:__text__",
		"show:</a>",
		"show:</div>"
	);
}
?>
```