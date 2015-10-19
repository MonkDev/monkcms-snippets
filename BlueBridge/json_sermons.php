<?php require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php");

$outarray;
$nodes;
$json;

$category = "";
$series = "";
$group = "";
$howmany = "";
$offset = "";
$order = "recent";
$hideseries="";
$hidecategory="";
$hidegroup="";
$passage="";
$preacher="";
$tags="";


//$filters = json_decode(stripslashes(urldecode($_GET['filter'])),true);
$filters = $_GET;
if($filters != null){
	foreach($filters as $key=>$value){
		switch ($key)
		{
		case 'series':
			$series = $value;
			break;
		case 'category':
			$category = $value;
			break;
		case 'group':
			$group = $value;
			break;
		case 'limit':
			$howmany = $value;
			break;
		case 'order':
			$order = $value;
			break;
		case 'hide_series':
			$hideseries = $value;
			break;
		case 'hide_category':
			$hidecategory = $value;
			break;
		case 'hide_group':
			$hidegroup = $value;
			break;
		case 'preacher':
			$preacher = $value;
			break;
		case 'tags':
			$tags = $value;
			break;
		case 'passage':
			$passage = $value;
			break;
		case 'start':
			$offset = $value;
			break;

		}
	}
}

$string = getContent(
	"sermons",
	"display:list",
	"order:".$order,
	"find_category:".$category,
	"find_series:".$series,
	"find_group:".$group,
	"hide_series:".$hideseries,
	"hide_category:".$hidecategory,
	"hide_group:".$hidegroup,
	"find_preacher:".$preacher,
	"find_tag:".$tags,
	"find_passage:".$passage,
	"howmany:".$howmany,
	"offset:".$offset,
	"show:__title__",
	"show:||",
	"show:__imageurl width='320' height='150'__",
	"show:||",
	"show:__preview limit='200'__",
	"show:||",
	"show:__preacher__",
	"show:||",
	"show:__date format='M d Y'__",
	"show:||",
	"show:__summary__",
	"show:||",
	"show:__slug__",
	"show:||",
	"show:__audiourl__",
	"show:||",
	"show:__videourl__",
	"show:||",
	"show:__videoembed__",
	"show:||",
	"show:__imageurl2 width='50' height='50'__",
	"show:||",
	"show:__text__",
	"show:||",
	"show:__category__",
	"show:||",
	"show:__series__",
	"show:||",
	"show:__seriesimage width='480'__",
	"show:||",
	"show:__passage__",
	"show:||",
	"show:__passageslug__",
	"show:||",
	"show:__passagebook__",
	"show:||",
	"show:__passageverse__",
	"show:||",
	"show:__group__",
	"show:||",
	"show:__tags__",
	"show:||",
	"show:__notes__",
	"show:||",
	"show:__id__",
	"show:~~~~",
	"noecho"
);

//echo($string);

$prearray = explode("~~~~",$string);

for ($i=0; $i <count($prearray)-1; $i++) {
	$outarray[$i] = explode("||",$prearray[$i]);
}

/* function getAudio($url){
        $a = preg_match('@(url=http?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\S+(mp3|aac|wav|m4a))?)?)?)@', $url,$matches);
    $audio = str_replace("url=","",$matches[0]);
    return $audio;
    }

    function getVideo($url){
         $v = preg_match('@(url=http?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\S+(mov|mp4|m4v))?)?)?)@', $url,$matches);
       $video = str_replace("url=","",$matches[0]);
       return $video;
    }*/

$i = 0;
foreach ($outarray as $key => $value) {

	preg_match_all('/(src)=("[^"]*")/i',$value[9],$parts);
	$type;
	$media_id = substr($parts[0][0],5,-1);
	$posyt = strpos($parts[0][0],"youtube");
	$posvm = strpos($parts[0][0],"vimeo");
	//$iframe[0][2][0] gets src value from iframe
	if($posyt !==false){
		$type = "youtube";
	}
	if($posvm !==false){
		$type = "vimeo";
	}

	$nodes[$i] = array(

		title => $value[0],
		image => $value[1],
		preview => $value[2],
		author => $value[3],
		date => $value[4],
		summary => $value[5],
		slug => $value[6],
		audio => $value[7],
		video => $value[8],
		embed => array(
			type => $type,
			media_id => $media_id,
			code => $value[9]
		),
		thmb => $value[10],
		text => $value[11],
		category => $value[12],
		series => $value[13],
		seriesimage => $value[14],
		passage => $value[15],
		passageslug => $value[16],
		passagebook => $value[17],
		passageverse => $value[18],
		group => $value[19],
		tags => $value[20],
		notes => $value[21],
		id => $value[22]
	);
	$i++;
}

$totalpossible = getContent(
	"sermons",
	"display:list",
	"order:".$order,
	"find_category:".$category,
	"find_series:".$series,
	"find_group:".$group,
	//"howmany:".$howmany, hide both howmany and offest because it needs to know total of possible articles to return.
	"find_author:".$author,
	"find_tag:".$tags,
	"hide_series:".$hideseries,
	"hide_category:".$hidecategory,
	"hide_group:".$hidegroup,
	"before_show:__totalpossible__",
	"noecho"
);


$output = array(
	items => $nodes,
	total => intval($totalpossible)
);

//$output = array("items" => $nodes);

$json = json_encode($output);
//print_r($articles);

$callback = $_REQUEST['callback'];

if($callback){
	header('Content-type: text/javascript');
	//echo("jsonp");
	echo $callback . '(' . $json . ');';
}else{
	header('Content-type: application/json');
	//echo "no-jibber";
	echo $json;
}

?>