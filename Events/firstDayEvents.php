<?php

/*
 * We rencetly had a request to show"
 *    - All single day events
 *    - All recurring events
 *    - Just the first day of multi-day events
 *
 *  It could be that I missed some basic stuff and appreciate any feedback. 
 *  The "occurrenceid" produced a different number for each day of a 3-day event. The "id" produced 
 *  the same number for recurring events. This is as expected, but I couldn't figure out how to 
 *  simply compare one value to see if I should show the event.  My conclusion is that there is no
 *  rock solid way to get the results I wanted. But I came close...
 
 *  The end time for recuring events is the end of the first occurrence of the recurring event. With
 *  that knowledge, I could show all those events no matter what. Then I just had to compare event ids
 *  to limit my results to the first day of the event.
 *
 *  FLAW ALERT: This does however leave one huge error.... starting a new recurring event. Only the
 *  first occurance of new recurring events will show until after the first one has passed. Then
 *  they all willl show. A workaround is to play around with the start time and sometime in the 
 *  week before the event starts...set the event start time to the previous week. That previous 
 *  occurance won't show because it's in the past, but the future ones will.
 *
 *
 *  NOTE: It was not required to show events in the past. This solution expects that only future
 *  events are desired.
 *
 *
 *
 *  Oh...this is Ricky Ybarra
 */

$outputRaw = getContent(
    "event",
    "display:list",
    "recurring:yes",
    "repeatevent:yes",
    "enablepast:no",
    "show:__eventend__",
    "show:|~", //just a flag
    "show:__id__",
    "show:|~", //just a flag
    "show:<article class='event' data-id='__id__'>",  //just markup
    "show:<div class='imgwrap'>",
    "show:<img src='__imageurl width='298' height='241' nokill='yes'__' title='__title__'/>", 
    "show:</div>",
    "show:<div class='tooltip' style='display:none'></div>",
    "show:<a href='__url__' class='titlelink'>__title__</a>",
    "show:<time>__eventstart format='l, F j'__</time>",
    "show:<div class='popup' style='display:none'>",
    "show:<p>__preview__</p><a href='__url__'>More Details</a>",
    "show:__registration linktext='SIGN UP NOW'__ ",
    "show:</div> ",
    "show:</article>",
    "show:|^", //just a flag
    "noecho"                        
);

$eventIds = array(); //used to keep track of events we've shown
$output = ""; //our main output

$outputRaw = explode("|^", trim($outputRaw, "|^")); //break the results down into single events
foreach($outputRaw as $outputDetail){

  $outputDetail = explode("|~", $outputDetail); //get my data groups
  if(date('Ymd', strtotime($outputDetail[0])) <= date('Ymd')){
    //the event end is in the past (this likely means that this is a recurring event)
    //we want to show all instances of these
    $output .= $outputDetail[2];
  }
  else{
    //show the other events only once
    if(!isset($eventIds[$outputDetail[1]])){
      $eventIds[$outputDetail[1]] = "1";
      $output .= $outputDetail[2];
    }
  }
}

if(!empty($output)){
    echo $output;   
}else{
    echo "<h3> No events matched your search</h3>";
}