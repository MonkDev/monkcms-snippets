<? require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php");

$outarray;
$nodes;
$category;
$group;
$json;

$category = "";
$group = "";
$howmany = "500";
$offset = "";
$hidecategory="";
$hidegroup="";
$location="";
$state="";
$findnear="";
$radius="";
$howmanydays="365";
$order="";

//$filters = json_decode(stripslashes(urldecode($_GET['filter'])),true);

$filters = $_GET;
if($filters != null){
foreach($filters as $key=>$value){
    switch ($key)
    {
        case 'category':
            $category = $value;
            break;
        case 'group':
            $group = $value;
            break;
        case 'limit':
            $howmany = $value;
            break;
        case 'howmanydays':
            $howmanydays = $value;
            break;
        case 'hide_category':
            $hidecategory = $value;
            break;
        case 'hide_group':
            $hidegroup = $value;
            break;
        case 'location':
            $location = $value;
            break;
        case 'state':
            $state = $value;
            break;
        case 'near':
            $findnear = $value;
            break;
        case 'radius':
            $radius = $value;
            break;
        case 'order':
            $order = $value;
            break;
        case 'start':
              $offset = $value;
              break;
    }
}
}


$string = getContent(
	"event",
	"display:list",
	"find_category:".$category,
	"find_group:".$group,
	"howmany:".$howmany,
	"offset:".$offset,
	"order:".$order,
	"hide_category:".$hidecategory,
	"hide_group:".$hidegroup,
	"find_location:".$location,
	"find_state:".$state,
	"find_near:".$findnear,
	"radius:".$radius,
	"howmanydays:".$howmanydays,
	"recurring:yes",
	"repeatevent:yes",
	"emailencode:no",
	"show:__title__", //[0]
	"show:||",
	"show:__image__", //[1]
	"show:||",
	"show:__preview limit='200'__", //[2]
	"show:||",
	"show:__description__", //[3]
	"show:||",
	"show:__coordname__", //[4]
	"show:||",
	"show:__coordemail__", //[5]
	"show:||",
	"show:__coordphone__", //[6]
	"show:||",
	"show:__eventstart format='Ymd H:i:s'__", //[7]
	"show:||",
	"show:__eventstartTwo format='M'__", //[8]
	"show:||",
	"show:__eventstartThree format='d'__", //[9]
	"show:||",
	"show:__eventend format='Ymd H:i:s'__", //[10]
	"show:||",
	"show:__eventtimes__", //[11]
	"show:||",
	"show:__customdisplaydatestimes__", //[12]
	"show:||",
	"show:__googlemap__", //[13]
	"show:||",
	"show:__fulladdress__", //[14]
	"show:||",
	"show:__location__", //[15]
	"show:||",
	"show:__slug__", //[16]
	"show:||",
	"show:__registration linktext='Register'__", //[17]
	"show:||",
	"show:__website__", //[18]
	"show:||",
	"show:__id__", //[19]
	"show:||",
	"show:__occurrenceid__", //[20]
	"show:||",
	"show:__group__", //[21]
	"show:||",
	"show:__category__", //[22]
	"show:||",
	"show:__url__", //[23]
	"show:||",
	"show:__cost__", //[24]
	"show:||",
	"show:__import__", //[25]
	"show:||",
	"show:__customroom__", //[26]
	"show:||",
	"show:__isrecurring__true", //[27]
	"show:||",
	"show:__isallday__true", //[28]
	"show:~~~~",
	"noecho",
	"nocache"
	);

   //echo($string);

	$prearray = explode("~~~~",$string);

	for ($i=0; $i <count($prearray)-1; $i++) {

		 $outarray[$i] = explode("||",$prearray[$i]);
    }
    //generate json structure
    $i = 0;
	foreach ($outarray as $key => $value) {
		 $j = 0;
		 
		 	$startTS = strtotime($value[7]); //__eventstart format='Ymd H:i:s'__//
		 	$endTS = strtotime($value[10]);  //__eventend format='Ymd H:i:s'__//
		 	$startDay = date("m/d/Y", $startTS); 
		 	$endDay = date("m/d/Y", $endTS); 
		 	$startTime = date("g:i A", $startTS); 
		 	$recurringFlag = trim($value[27]);
		 	$alldayFlag = trim($value[28]);

		 	if($recurringFlag == "true" || $startDay == $endDay){ 
			 	//recurring flag is necessary because recurring event endtimes returns the series end. 
			 	//assume no multi-day events are recurring 
			 	//These events start and end on the same day. 
			 	$endTime = date("g:i A", $endTS);
			 	if ($alldayFlag == "true") {
				 	$endDateTime = $startDay;
			 	} else {
				 	$endDateTime = $startDay . " " . $endTime;
				}
			} else { 
				//the event is not recurring, but the end is not the same day 
			 	if ($alldayFlag == "true") {	
					$endTime = date("m/d/Y", $endTS);
				 } else {
					$endTime = date("m/d/Y g:i A", $endTS);
				 }
					$endDateTime = $endTime;
			}
			
			if ($alldayFlag == "true") {
				$startDateTime = $startDay; 
			} else {
				$startDateTime = $startDay . " " . $startTime; 
			}
			
	     $nodes[$i] = array(
		    title => $value[0],
		    image => $value[1],
		    preview => $value[2],
		    text => $value[3],
				coord => array(
				        name => $value[4],
				        email => $value[5],
				        phone => $value[6]
				    ),
		    date => array(
			    startTS => $value[7],
	               startDateTime => $startDateTime,
	               month => $value[8],
	               day => $value[9],
	            endTS => $value[10],
	               endDateTime => $endDateTime,
	               event_times => $value[11],
				   custom_date_display => $value[12],
	            ),
		    loc => array(
		            googlemap => $value[13],
		            address => $value[14],
		            name => $value[15]
		        ),
		    slug => $value[16],
		    register => $value[17],
		    website => $value[18],
		    eventid => $value[19],
		    occurrenceid => $value[20],
		    groups => $value[21],
		    categories => $value[22],
		    url => $value[23],
		    cost => $value[24],
		    icalimport => $value[25],
			custom_room => $value[26],
			recurring => $value[27],
			allday => $value[28]
		    );
	    $i++;
	}

	//$output = array("items" => $nodes);

	$totalpossible = getContent(
	  "event",
	  "display:list",
	  "find_category:".$category,
	  "find_group:".$group,
	  "order:".$order,
	  "hide_category:".$hidecategory,
	  "hide_group:".$hidegroup,
	  "find_location:".$location,
	  "find_state:".$state,
	  "find_near:".$findnear,
	  "radius:".$radius,
	  "howmanydays:".$howmanydays,
	  "recurring:yes",
	  "repeatevent:yes",
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
	     echo $callback . '(' . $json . ');';
	 }else{
	     header('Content-type: application/json');
	     echo $json;
	 }

?>
