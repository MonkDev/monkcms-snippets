<?php

/*
*  ===========================================================================
*
*                           CLASS serviceuEvents
*
*							This class provides static methods to output events from serviceu to events layout #3
* 
*  ===========================================================================
*
* OUTPUT
* @public method outputRawEvents()  - Formats events from getEvents() to match the $rawEvents var in event layout #3
* @public method outputSearchEvents() - Formats events to match $rawEvents search from event layout #3
* @public method outputCalendarEvents() - Return events array for calendar json feed (_components/ajax/fullcalendar.jphp)
* @public method outputEventDetail() - Formats specific event occurence to match event layout #3
* @public method outputCategorySelect()  - Formats categories from getCategories() and returns category select options
* @public method outputDepartmentSelect()  - Formats departments from getDepartments() and returns department select options
*
* EVENTS & FILTERS 
* @public method getEvents() - Get data from serviceu event occurences (uses xml and formats ->simple object->array)
* @public method getEventOccurrence() - Get data from serviceu event occurence (uses xml and formats ->simple object->array)
* @public method getEvent() - Get data from serviceu event (uses xml and formats ->simple object->array)

* @public method filterEvents() - Filter events occurences by category and department id's
* @public method filterEventsByDepartment() - Filter events occurences by department id's
* @public method filterEventsByCategory() - Filter events occurences by category id's
*
* Categories 
* @public method getCategories() - Get data from serviceu categories (uses xml and formats ->simple object->array)
* @public method findCategoryNameByID() - Find and return cateogry name by id
* @public method findCategoryIdBySlug() - Find and return cateogry id by slug
*
* Departments 
* @public method getDepartments() - Get data from serviceu departments (uses xml and formats ->simple object->array)
* @public method findDepartmentNameByID() - Find and return department name by id
* @public method findDepartmentIdBySlug() - Find and return department id by slug
*
* HELPERS 
* @public method makeslug() - converts string to slug format
* @public method reencode() - converts html special characters
*
*/

class serviceuEvents{

	/*
	*  ==========================================================================
	*
	*  outputRawEvents() - Formats event occurences to match events list var from event layout #3
	* 
	*  ==========================================================================
	*  
    * @return - a variable with formated events to $rawEvents var layout #3
	*
	* @param catID - the category id
	* @param depID - the department id
	*
	* @method filterEvents() - filters event by category id and department id
	*
 	*/
	public static function outputRawEvents($catID, $depID){
		$events = self::filterEvents($catID, $depID);
		$rawEvents = '';
		foreach($events as $e){
			$predate = date('Ymd', strtotime($e['OccurrenceStartTime']));
			$eventtimes = date('l, F jS, Y, g:ia', strtotime($e['OccurrenceStartTime']));
			$slug = self::makeslug($e['Name']);
			$rawEvents .= $predate;
			$rawEvents .= "~~";
			$rawEvents .= "<article class='event'>";
			$rawEvents .= "<h3><a href='/su-event/".$e['OccurrenceId']."/".$slug."'>".$e['Name']."</a></h3>";
			$rawEvents .= "<p class='meta'>";
			$rawEvents .= "<span>Time: </span>".$eventtimes." &nbsp;&nbsp;";
			if($e['LocationName']!=''){
				$fulladdress = $e['LocationAddress'].','.$e['LocationCity'].' '.$e['LocationState'].' '.$e['LocationZip'];
				$rawEvents .= "<span>Location: </span><a href=\"http://maps.google.com/maps?q=".$fulladdress."\" title=\"map it\" target=\"_blank\">".$e['LocationName']."</a>";
			}
			$rawEvents .= "</p>";
			$rawEvents .= "</article>";
			$rawEvents .= "|~";
		}
		return $rawEvents;
	}
	
	/*
	*  ==========================================================================
	*
	*  outputSearchEvents() - Formats events to match events search from event layout #3
	* 
	*  ==========================================================================
	*  
    * @return - a variable with formated events to search var layout #3
	*
	* @param keywords - keywords array
	*
	* @method getEvents() - returns all events from feed
	*
 	*/
	public static function outputSearchEvents($keywords){
		$events = self::getEvents();
		$rawEvents = '';
		foreach($events as $e){
			foreach($keywords as $keyword){
				if (stripos($e['Name'],$keyword) !== false) {
					$predate = date('Ymd', strtotime($e['OccurrenceStartTime']));
					$eventtimes = date('l, F jS, Y, g:ia', strtotime($e['OccurrenceStartTime']));
					$slug = self::makeslug($e['Name']);
					$rawEvents .= $predate;
					$rawEvents .= "~~";
					$rawEvents .= "<article class='event'>";
					$rawEvents .= "<h3><a href='/su-event/".$e['OccurrenceId']."/".$slug."'>".$e['Name']."</a></h3>";
					$rawEvents .= "<p class='meta'>";
					$rawEvents .= "<span>Time: </span>".$eventtimes." &nbsp;&nbsp;";
					if($e['LocationName']!=''){
						$fulladdress = $e['LocationAddress'].','.$e['LocationCity'].' '.$e['LocationState'].' '.$e['LocationZip'];
						$rawEvents .= "<span>Location: </span><a href=\"http://maps.google.com/maps?q=".$fulladdress."\" title=\"map it\" target=\"_blank\">".$e['LocationName']."</a>";
					}
					$rawEvents .= "</p>";
					$rawEvents .= "</article>";
					$rawEvents .= "|~";
				}
			}
		}
		return $rawEvents;
	}
	
	
	/*
	*  ==========================================================================
	*
	*  outputCalendarEvents() - Returns event array for custom calendar json feed
	* 
	*  ==========================================================================
	*  
    * @return - an array for the events calendar view
	*
	* @param catID - the category id
	* @param depID - the department id
	*
	* @method filterEvents() - filters event by category id and department id
	*
 	*/
	public static function outputCalendarEvents($catID, $depID){
		$events = self::filterEvents($catID, $depID);
		return $events;
	}
	
	/*
	*  ==========================================================================
	*
	*  outputEventDetail() - Formats specific event occurence to match event layout #3
	* 
	*  ==========================================================================
	*  
    * @return - a variable with formated event to layout #3
	*
	* @param occID - the occurence id
	*
	* @method getEventOccurrence() - returns event occurrence
	* @method getEvent() - returns event
	*
	* NOTE - for some reason serviceu does not include attendees, event image, etc in the occurrence
	*              so need both event and event occurrence to meld
	*
 	*/
	public static function outputEventDetail($occID){
		$occ = self::getEventOccurrence($occID);
		$event = self::getEvent($occ['EventId']);
		$eventDetail = '';
		$eventtimes = date('l, F jS, Y, g:ia', strtotime($occ['OccurrenceStartTime']));
		$eventDetail .= "<article class='detail'>";
		if($event['ExternalImageUrl']!=''){//ExternalImageUrl
			$eventDetail .= "<div id='eventimg'><img src=\"".$event['ExternalImageUrl']."\" alt='".$occ['Name']."'/></div>";  
		}
        $eventDetail .= "<h1>".$occ['Name']."</h1>";
        $eventDetail .= "<p class='time'>".$eventtimes."</p>\n";
        if($occ['LocationName']!=''){
			$fulladdress = $occ['LocationAddress'].','.$occ['LocationCity'].' '.$occ['LocationState'].' '.$occ['LocationZip'];
			$eventDetail .= "<p class='meta'><em>Location: </em><a href=\"http://maps.google.com/maps?q=".$fulladdress."\" title=\"map it\" target=\"_blank\">".$occ['LocationName']."</a></p>";
		}
		if($occ['ContactName']!=''){
          	$eventDetail .= "<p class='meta'><em>Contact: </em>".$occ['ContactName']." | "; 
				if($occ['ContactPhone']!=''){$eventDetail .= "<em>p:</em> ".$occ['ContactPhone']." ";}  
				if($occ['ContactEmail']!=''){$eventDetail .= "<em>e:</em> <a href='mailto:".$occ['ContactEmail']."'>".$occ['ContactEmail']."</a>";}
			$eventDetail .= "</p>";
		}
		if($event['Attendees']!=''){
			$eventDetail .= "<p class='meta'><em>Attendance Limit:</em> ".$event['Attendees']."</p>";
		}
		if($occ['Description']!=''){
			$eventDetail .= "<p>".$occ['Description']."</p>";
		}
		if($occ['RegistrationUrl']){
			$eventDetail .= "<p class='rsvp'><a href='".$occ['RegistrationUrl']."' target='_blank'>Register</a></p>"; 	
		}
        $eventDetail .= "</article>";

		return $eventDetail;
	}
	
	/*
	*  ==========================================================================
	*
	*  outputCategorySelect() - Formats categories for campus filter
	* 
	*  ==========================================================================
	*  
    * @return - a select option list for campus filter
	*
 	*/
	public static function outputCategorySelect(){
		$categories = self::getCategories();
		$categorySelect = '';//default to no options if get is empty
		foreach($categories as $c){
			$categorySelect .= "<option value='".$c['CategorySlug']."'>".$c['CategoryName']."</option>";		
		}
		return $categorySelect;
	}
	
	/*
	*  ==========================================================================
	*
	*  outputDepartmentSelect() - Formats departments for category filter
	* 
	*  ==========================================================================
	*  
    * @return - a select option list for campus filter
	*
 	*/
	public static function outputDepartmentSelect(){
		$departments = self::getDepartments();
		$departmentSelect = '';
		foreach($departments as $d){
			$departmentSelect .= "<option value='".$d['DepartmentSlug']."'>".$d['DepartmentName']."</option>";	
		}
		return $departmentSelect;
	} 
	
	
	/*
	*  ==========================================================================
	*
	*  getEvents() - Get the serviceu event occurences.
	* 
	*  ==========================================================================
	*
	* Pulls from the serviceu rest api to format as xml
	* orgKey is provided from serviceu and the url param nextDays 
	* Formats xml to simple object , cast the variables to string and return array of event occurences 
	*
	*/
	public static function getEvents(){
		$orgKey = '21ad7710-bb02-4843-ad40-6c4bc15477dd';
		$nextDays = '180';//6 months out
		$feed = file_get_contents('http://api.serviceu.com/rest/events/occurrences?nextDays='.$nextDays.'&orgKey='.$orgKey.'&format=xml');
		$xmlData = preg_replace_callback( '/&.*?;/', 'reencode', $feed );
		$xmlString = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA);
		
		if(isset($xmlString->Occurrence)) {
			if($xmlString->Occurrence->StatusDescription == 'Approved')	{
				foreach ($xmlString->Occurrence as $e ){
					$event = array(
						'DateModified' => (string) $e->DateModified, // last modified "07/01/2013 06:29:59 PM"
						'EventId' => (string) $e->EventId, // digits "6776861"
						'Name' => (string) $e->Name, // Event name
						'DisplayTimes' => (string) $e->DisplayTimes, // true or false
						'ExternalEventUrl' => (string) $e->ExternalEventUrl, // http to external serviceu event
						'ExternalImageUrl' => (string) $e->ExternalImageUrl, // http to external serviceu event image
						'OccurrenceId' => (string) $e->OccurrenceId, // digits "258860063"
						'OccurrenceStartTime' => (string) $e->OccurrenceStartTime, // this occurence start "01/15/2014 09:30:00 AM
						'OccurrenceEndTime' => (string) $e->OccurrenceEndTime, // this occurence end "01/15/2014 11:30:00 AM"
						'PublicEventUrl' => (string) $e->PublicEventUrl, // http to public serviceu event
						'DepartmentList' => (string) $e->DepartmentList, // department list text "Department List"
						'DepartmentName' => (string) $e->DepartmentName, // department name text "Department Name"
						'CategoryList' => (string) $e->CategoryList, // category list text "Test Category"
						'ContactEmail' => (string) $e->ContactEmail, // contact email address "user@email.com"
						'ContactName' => (string) $e->ContactName, // contact name text "First Name"
						'ContactPhone' => (string) $e->ContactPhone, // contact phone number "5405399223"
						'Description' => (string) $e->Description, // description text
						'LocationAddress' => (string) $e->LocationAddress, // location address "2420 Somewhere Road"
						'LocationAddress2' => (string) $e->LocationAddress2, // location address2 "2420 Somewhere Road"
						'LocationCity' => (string) $e->LocationCity, // location city "San Diego"
						'LocationName' => (string) $e->LocationName, // location name text "Church Name"
						'LocationState' => (string) $e->LocationState, // location state "CA"
						'LocationZip' => (string) $e->LocationZip, // location zip "11111"
						'RegistrationEnabled' => (string) $e->RegistrationEnabled, // 0 or 1
						'RegistrationUrl' => (string) $e->RegistrationUrl, // http to registration url
						'ResourceEndTime' => (string) $e->ResourceEndTime, // 01/15/2014 12:15:00 PM
						'ResourceList' => (string) $e->ResourceList, // list of resources "Atrium, Elementary Classroom, Kitchen, Youth Area"
						'ResourceStartTime' => (string) $e->ResourceStartTime, // resource start time01/15/2014 08:30:00 AM
						'StatusDescription' => (string) $e->StatusDescription, // status "Approved"
						'SubmittedBy' => (string) $e->SubmittedBy, // who submitted "First Last"
					);
					$events[] = $event;
				}
			}
			return $events;
		}
	}
	
	/*
	*  ==========================================================================
	*
	*  getEventOccurrence() - Get the serviceu event occurence
	* 
	*  ==========================================================================
	*
	* Pulls from the serviceu rest api to format as xml
	* Formats xml to simple object , cast the variables to string and return array of event occurence
	*
	* @param occID - the occurence id
	*
	*/
	public static function getEventOccurrence($occID){
		$orgKey = '21ad7710-bb02-4843-ad40-6c4bc15477dd';
		$feed = file_get_contents('http://api.serviceu.com/rest/events/occurrences/'.$occID.'?orgKey='.$orgKey.'&format=xml');
		$xmlData = preg_replace_callback( '/&.*?;/', 'reencode', $feed );
		$xmlString = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA);
		if(isset($xmlString)) {
				$e = $xmlString;
					$event = array(
						'DateModified' => (string) $e->DateModified, // last modified "07/01/2013 06:29:59 PM"
						'EventId' => (string) $e->EventId, // digits "6776861"
						'Name' => (string) $e->Name, // Event name
						'OccurrenceId' => (string) $e->OccurrenceId, // digits "258860063"
						'OccurrenceStartTime' => (string) $e->OccurrenceStartTime, // this occurence start "01/15/2014 09:30:00 AM
						'OccurrenceEndTime' => (string) $e->OccurrenceEndTime, // this occurence end "01/15/2014 11:30:00 AM"
						'PublicEventUrl' => (string) $e->PublicEventUrl, // http to public serviceu event
						'DepartmentName' => (string) $e->DepartmentName, // department name text "Department Name"						
						'ContactEmail' => (string) $e->ContactEmail, // contact email address "user@email.com"
						'ContactName' => (string) $e->ContactName, // contact name text "First Name"
						'ContactPhone' => (string) $e->ContactPhone, // contact phone number "5405399223"	
						'Description' => (string) $e->Description, // description text
						'LocationAddress' => (string) $e->LocationAddress, // location address "2420 Somewhere Road"
						'LocationAddress2' => (string) $e->LocationAddress2, // location address2 "2420 Somewhere Road"
						'LocationCity' => (string) $e->LocationCity, // location city "San Diego"
						'LocationName' => (string) $e->LocationName, // location name text "Church Name"
						'LocationState' => (string) $e->LocationState, // location state "CA"
						'LocationZip' => (string) $e->LocationZip, // location zip "11111"
						'RegistrationType' => (string) $e->RegistrationEnabled, // 0 or 1
						'RegistrationUrl' => (string) $e->RegistrationUrl, // http to registration url
						'ResourceList' => (string) $e->ResourceList // list of resources "Atrium, Elementary Classroom, Kitchen, Youth Area"
					);
			return $event;
		}
	}
	
	/*
	*  ==========================================================================
	*
	*  getEvent() - Get the serviceu event.
	* 
	*  ==========================================================================
	*
	* Pulls from the serviceu rest api to format as xml
	* Formats xml to simple object , cast the variables to string and return array of event
	*
	* @param eventID - the event id
	*
	*/
	public static function getEvent($eventID){
		$orgKey = '21ad7710-bb02-4843-ad40-6c4bc15477dd';
		$feed = file_get_contents('http://api.serviceu.com/rest/events/'.$eventID.'?orgKey='.$orgKey.'&format=xml');
		$xmlData = preg_replace_callback( '/&.*?;/', 'reencode', $feed );
		$xmlString = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA);
		
		if(isset($xmlString)) {
				$e = $xmlString;
					$event = array(
						'DateModified' => (string) $e->DateModified, // last modified "07/01/2013 06:29:59 PM"
						'EventId' => (string) $e->EventId, // digits "6776861"
						'Name' => (string) $e->Name, // Event name
						'DisplayTimes' => (string) $e->DisplayTimes, // true or false
						'ExternalEventUrl' => (string) $e->ExternalEventUrl, // http to external serviceu event
						'ExternalImageUrl' => (string) $e->ExternalImageUrl, // http to external serviceu event image
						'PublicEventUrl' => (string) $e->PublicEventUrl, // http to public serviceu event
						'DepartmentList' => (string) $e->DepartmentList, // department list text "Department List"
						'CategoryList' => (string) $e->CategoryList, // category list text "Test Category"
						'ContactEmail' => (string) $e->ContactEmail, // contact email address "user@email.com"
						'ContactName' => (string) $e->ContactName, // contact name text "First Name"
						'ContactPhone' => (string) $e->ContactPhone, // contact phone number "5405399223"
						'Description' => (string) $e->Description, // description text
						'LocationAddress' => (string) $e->LocationAddress, // location address "2420 Somewhere Road"
						'LocationAddress2' => (string) $e->LocationAddress2, // location address2 "2420 Somewhere Road"
						'LocationCity' => (string) $e->LocationCity, // location city "San Diego"
						'LocationName' => (string) $e->LocationName, // location name text "Church Name"
						'LocationState' => (string) $e->LocationState, // location state "CA"
						'LocationZip' => (string) $e->LocationZip, // location zip "11111"
						'Attendees' => (string) $e->Attendees, // number of attendees "30"
						'Featured' => (string) $e->Featured, // true or false
						'RegistrantsQuestionText' => (string) $e->RegistrantsQuestionText, // registration question
						'ShowRegistrantsQuestion' => (string) $e->ShowRegistrantsQuestion, // true or false
						'RegistrationEnabled' => (string) $e->RegistrationEnabled, // 0 or 1
						'StatusDescription' => (string) $e->StatusDescription, // status "Approved"
					);
			return $event;
		}
	}

	
	/*
	*  ==========================================================================
	*
	*  filterEvents() - Return events for a specific category and department
	* 
	*  ==========================================================================
	*
	* @param catID - the category id to filter
	* @param depID - the department id to filter
	*
	* @return - the filtered events array
	* 
	* @filterEventsByCategory() - filter events by category 
	* @filterEventsByDepartment() - filter events by department 
	* @getEvents() - returns a complete list of events from feed
	*
	*/
	public static function filterEvents($catID, $depID) {
		if($catID!=''&&$depID==''){
			$filtered = self::filterEventsByCategory($catID, '');	
		}else if($depID!=''){
			if($catID!=''){
				$events = self::filterEventsByCategory($catID, '');
			}else{
				$events = self::getEvents();
			}
			$filtered = self::filterEventsByDepartment($depID, $events);		
		}else{
			$filtered = self::getEvents();
		}
		return $filtered;
	} 
	
	/*
	*  ==========================================================================
	*
	*  filterEventsByDepartment() - Return events for a specific department
	* 
	*  ==========================================================================
	*
	* @param depID - the department id to filter
	* @param events - the events list to filter.  if empty use getEvents()
	*
	* @return - the filtered events array
	* 
	* @findDepartmentNameByID() - returns the serviceu department name from id 
	* @getEvents() - returns a complete list of events from feed
	*
	*/
	public static function filterEventsByDepartment($depID, $events) {
		if($events==''){$events = self::getEvents();}
		$dname = self::findDepartmentNameByID($depID);
		foreach($events as $e){
			if (stripos($e['DepartmentList'],$dname) !== false) {
				$filtered[] = $e;
			}
		}	
		return $filtered;
	}
	
	
	/*
	*  ==========================================================================
	*
	*  filterEventsByCategory() - Return events for a specific category
	* 
	*  ==========================================================================
	*
	* @param depID - the category id to filter
	* @param events - the events list to filter.  if empty use getEvents()
	*
	* @return - the filtered events array
	* 
	* @filterEventsByCategory() - returns the serviceu category name from id 
	* @getEvents() - returns a complete list of events from feed
	*
	*/
	public static function filterEventsByCategory($catID, $events) {
		if($events==''){$events = self::getEvents();}
		$cname = self::findCategoryNameByID($catID);
		foreach($events as $e){
			if (stripos($e['CategoryList'],$cname) !== false) {
				$filtered[] = $e;
			}
		}	
		return $filtered;
	}
	
	
	/*
	*  ==========================================================================
	*
	*  getCategories() - Get the serviceu categories. (client is using categories for campus filter)
	* 
	*  ==========================================================================
	*
	* Pulls from the serviceu rest api to format as xml
	* orgKey is provided from serviceu and no url parameters
	* Formats xml to simple object , cast the variables to string and return array of categories 
	*
	*/
	public static function getCategories(){
		$orgKey = '21ad7710-bb02-4843-ad40-6c4bc15477dd';
		$feed = file_get_contents('http://api.serviceu.com/rest/categories/?&orgKey='.$orgKey.'&format=xml');
		$xmlData = preg_replace_callback( '/&.*?;/', 'reencode', $feed );
		$xmlString = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA);
		
		if(isset($xmlString->Category))	{
			foreach ($xmlString as $c ){
				$slug = self::makeslug($c->CategoryName);
				$category = array(
					'CategoryId' => (string) $c->CategoryId,
					'CategoryName' => (string) $c->CategoryName,
					'CategorySlug' => (string) $slug
				);
				$categories[] = $category;
			}
			return $categories;
		}
	}
	
	/*
	*  ==========================================================================
	*
	*  findCategoryNameByID() - Get the serviceu category name from id
	* 
	*  ==========================================================================
	*
	* @param id - the id to match and pull category name
	*
	* @return - the matching category name
	* 
	* @getCategories() - returns a complete list of categories from feed
	*
	*/
	public static function findCategoryNameByID($id) {
		$categories = self::getCategories();
		foreach($categories as $c){
			if($c['CategoryId']==$id){
				$name = $c['CategoryName'];
			}
		}
		return $name;
	}
	
	/*
	*  ==========================================================================
	*
	*  findCategoryIdBySlug() - Get the serviceu category id from slug
	* 
	*  ==========================================================================
	*
	* @param slug - the slug to match and pull category id
	*
	* @return - the matching category id
	* 
	* @getCategories() - returns a complete list of categories from feed
	*
	*/
	public static function findCategoryIdBySlug($slug) {
		$categories = self::getCategories();
		foreach($categories as $d){
			if($d['CategorySlug']==$slug){
				$id = $d['CategoryId'];
			}
		}
		return $id;
	}
	
	/*
	*  ==========================================================================
	*
	*  getDepartments() - Get the serviceu departments. (client is using departments for category filter)
	* 
	*  ==========================================================================
	*
	* Pulls from the serviceu rest api to format as xml
	* orgKey is provided from serviceu and no url parameters
	* Formats xml to simple object , cast the variables to string and return array of categories 
	*
	*/
	public static function getDepartments(){
		$orgKey = '21ad7710-bb02-4843-ad40-6c4bc15477dd';
		$feed = file_get_contents('http://api.serviceu.com/rest/departments/?&orgKey='.$orgKey.'&format=xml');
		$xmlData = preg_replace_callback( '/&.*?;/', 'reencode', $feed );
		$xmlString = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA);
		
		if(isset($xmlString->Department))	{
			foreach ($xmlString as $d ){
				if($d->DisplayOnWeb=='true' && $d->UseForEvents=='true'){
					$slug = self::makeslug($d->DepartmentName);
					$department = array(
						'DepartmentId' => (string) $d->DepartmentId,
						'DepartmentName' => (string) $d->DepartmentName,
						'DepartmentSlug' => (string) $slug,
						'DisplayOnWeb' => (string) $d->DisplayOnWeb,//true or false
						'UseForEvents' => (string) $d->UseForEvents,//true or false
						'UseForUpdates' => (string) $d->UseForUpdates//true or false
					);
					$departments[] = $department;
				}
			}
			return $departments;
		}
	}
	
	
	/*
	*  ==========================================================================
	*
	*  findDepartmentNameByID() - Get the serviceu department name from id
	* 
	*  ==========================================================================
	*
	* @param id - the id to match and pull department name
	*
	* @return - the matching department name
	* 
	* @getDepartments() - returns a complete list of departments from feed
	*
	*/
	public static function findDepartmentNameByID($id) {
		$departments = self::getDepartments();
		foreach($departments as $d){
			if($d['DepartmentId']==$id){
				$name = $d['DepartmentName'];
			}
		}
		return $name;
	}
	
	/*
	*  ==========================================================================
	*
	*  findDepartmentIdBySlug() - Get the serviceu department id from slug
	* 
	*  ==========================================================================
	*
	* @param slug - the slug to match and pull department id
	*
	* @return - the matching department id
	* 
	* @getDepartments() - returns a complete list of departments from feed
	*
	*/
	public static function findDepartmentIdBySlug($slug) {
		$departments = self::getDepartments();
		foreach($departments as $d){
			if($d['DepartmentSlug']==$slug){
				$id = $d['DepartmentId'];
			}
		}
		return $id;
	}
	
	
	/*
	*  ==========================================================================
	*
	*  makeslug() - Get string and return as a slug
	* 
	*  ==========================================================================
	*
	* @param string - the string to "slugify"
	*
	* @return - a slug version of the string
	*
	*/
	public static function makeslug($string) {
      // remove spaces, tabs, linebreaks from the begining and the end
      $string = trim($string);
      // array of patters to change characters with the following symbols
      $pattern = array('$(à|á|â|ã|ä|å|À|Á|Â|Ã|Ä|Å|æ|Æ)$',
                       '$(è|é|é|ê|ë|È|É|Ê|Ë)$',
                       '$(ì|í|î|ï|Ì|Í|Î|Ï)$',
                       '$(ò|ó|ô|õ|ö|ø|Ò|Ó|Ô|Õ|Ö|Ø|œ|Œ)$',
                       '$(ù|ú|û|ü|Ù|Ú|Û|Ü)$',
                       '$(ñ|Ñ)$',
                       '$(ý|ÿ|Ý|Ÿ)$',
                       '$(ç|Ç)$',
                       '$(ð|Ð)$',
                       '$(ß)$');
      // array of replacements
      $replacement = array('a',
                       'e',
                       'i',
                       'o',
                       'u',
                       'n',
                       'y',
                       'c',
                       'd',
                       's');
      // Convert special chars to ascii and remove them
      $string = htmlentities($string, ENT_QUOTES, "UTF-8");
      $search = array (
        '@(&(#?))[a-zA-Z0-9]{1,7}(;)@'   // Strip out any ascii
      );
      $replace = array (
        ''
      );
      $string = preg_replace($search, $replace, $string);
      // all funny characters in the string get replaced with the
      // equivalent in ascii.
      $string =  preg_replace($pattern, $replacement, $string);
      $search = array (
        '@<script[^>]*?>.*?</script>@si', // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si',          // Strip out HTML tags
        '@(\s-\s)@',                      // Strip out space dash space
        '@[\s]{1,99}@',                   // Strip out white space
        '@—@',                            // Replace em dash with regular one
        '@[\\\/)({}^\@\[\]|!#$%*+=~`?.,_;:]@' // Things not covered by htmlentities
      );
      $replace = array (
        '',
        '',
        '-',
        '-',
        '-',
        ''
      );
      $string = preg_replace($search, $replace, $string);
      $string = strtolower($string);
      return $string;
    }


	/*
	*  ==========================================================================
	*
	*  reencode() - Formats xml file html special characters
	* 
	*  ==========================================================================
	*
	* @param file - the file to format
	*
	* @return - a formatted file
	*
	*/
	public function reencode($file) {
		return htmlspecialchars( html_entity_decode( $file[0], ENT_NOQUOTES, 'UTF-8' ), ENT_NOQUOTES, 'UTF-8' );
	}
	
}
?>