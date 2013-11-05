<?php

/* ===================================================================== //

	CUSTOM LOGIN FOR PRIVATE PAGES or BLOGS

	author: Chris Ullyott

	Adds the ability to display content based on whether the logged-in
	user is a group member. There are three scenarios:

	1.	The user is not logged in
	2.	The user is logged in, but not yet a member
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

	/* MEMBERSHIP */
	
	$group_access = false;
	
	// Get groups list
	$get_groups = getContent("blog", "display:auto", "howmany:1", "show_postlist:__groupslug__", "noecho"); // blogs
	$get_groups = getContent("page", "display:detail", "find:" . $_GET['nav'], "show:__groupslugs__", "noecho"); // pages

	// Sanitize groups list
	$get_groups = trim(preg_replace('/\s+/', '', $get_groups), ',');

	// Get members of all groups
	if($get_groups!=''){
		$group_members = getContent("member", "display:list", "find_group:" . $get_groups, "howmany:9999", "show:__username__,", "noecho");
		$group_members = explode(',', trim($group_members, ','));
		$group_members = array_values(array_unique($group_members));
		if (in_array($MCMS_USERNAME, $group_members)) {
			$group_access = true;
		}
	} else {
		$group_access = true;
	}

?>




<?php

	/* HELPERS */

	// If title of Blog can't be returned due to privacy, get it via wildcard.
	if(!$blogslug){ $blogslug_arr = explode('/',trim($_GET['wildcard'],'/')); $blogslug = $blogslug_arr[0]; }

	function slugToTitle($slug){
		$title = trim($slug,'/');
		$title = ucwords(str_replace('-',' ',$title));
		$title = preg_replace('/usa/i','USA',$title); // handle uniquely capitalized words
		return $title;
	}

?>




<?php

	/* NOT LOGGED IN */

	if (!$MCMS_LOGGEDIN) {

		$login_link = getContent("login","display:popup","label:login","noecho");
		echo '<p>Please ' . $login_link . ' to access this blog.</p>';

	}

?>




<?php

	/* LOGGED IN, NOT A MEMBER */

	if ( $MCMS_LOGGEDIN && !$group_access ){

		// "Access denied" message.
		getContent(
			"section",
			"display:detail",
			"find:access-denied-" . $some_slug, // some identifier (group name, etc)
			//"find:p-######",
			"show:__text__", // <h3>Your account does not have access to this page.</h3>
			"<p><a href='/logout'>&laquo; log out</a></p>",
			"<p><a href='/'>&laquo; home</a></p>"
		);

<?php } ?>




<?php

	/* LOGGED IN AND A MEMBER */

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