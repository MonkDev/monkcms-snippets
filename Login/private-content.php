<?php

/* ===================================================================== //

	CUSTOM LOGIN SYSTEM FOR PRIVATE PAGES

	author: Chris Ullyott

	Adds the ability to display content based on whether the logged-in
	user is a group member. There are three scenarios:

	1.	The user is not logged in
	2.	The user is logged in, but not a member
	3.	The user is logged in and a member

	*Remember that the Site Group cannot be present in a Page's groups
	for it to be private.
	
	Here are some instructions for the user on using a template with these additions:
	
	1. Create a Group in People > Groups, and set the privacy of the Group to Private.
	2. Add yourself and/or a few Members to this Group.
	3. Open the Page you want to make private. Change the Template to Subpage Layout (Private). 
	4. Assign your private Group to the Page on the Publish screen. Be sure to remove the Site Group.
	5. In Content > Sections > Access Denied Message, set the message you'd like users to see if they log in, but do not yet have access to the private Group (a CMS user will need to add them).

	With the private Group set to this page, the user will be prompted to log in. If they log in and are not in the private Group, the custom message will display. If they log in and are in the Group, the content will display.

// ===================================================================== */

?>



<?php

	$group_access = false;
	
	$get_groups = getContent("page", "display:detail", "find:" . $_GET['nav'], "show:__groupslugs__", "noecho"); // this API call will be different for sermons, blogs, etc.
	$get_groups = trim(preg_replace('/\s+/', '', $get_groups), ',');
	
	$group_members = getContent("member", "display:list", "find_group:" . $get_groups, "howmany:9999", "show:__username__,", "noecho");
	$group_members = explode(',', trim($group_members, ','));
	$group_members = array_values(array_unique($group_members));
		
	if (in_array($MCMS_USERNAME, $group_members)) {
		$group_access = true;
	}
	
?>




<?php

	// If not logged in, show the default private page message.

	if (!$MCMS_LOGGEDIN) {

		getContent(
	   "page",
	   "find:" . $_GET['nav'],
	   "show:__text__"
	   );

	}

?>




<?php

	// If logged in and *not* a group member, show the access denied message.

	if ( $MCMS_LOGGEDIN && !$group_access ):

?>


		<p>Your account does not have access to this page. Please contact the site administrator.</p>

		<p><a href="/">&laquo; home</a></p>


<?php endif; ?>




<?php

	// If logged in *and* a group member, show everything.

	if( $MCMS_LOGGEDIN && $group_access ):

?>


		<?php

			getContent(
			"page",
			"find:" . $_GET['nav'],
			"show:__text__"
			);

		?>


<?php endif; ?>
