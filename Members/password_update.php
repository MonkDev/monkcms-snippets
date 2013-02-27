<?php

	/*

	PASSWORD UPDATE FORM
	Allows users to update their member account password,
	without needing the entire profile edit screen.

	*/

	getContent(
	"member",
	"display:editprofile",
	"section:account",
	"restrict:yes",
	"show:__formopen__",
	"show:<p><label for=\"acctPassword\">Password:</label> __passwordfield__</p>",
	"show:<p><label for=\"acctPasswordConfirm\">Password Confirm:</label> __passwordconfirmfield__</p>",
	"show:<p class=\"submit\">__submitbutton__ or <a href=\"#\" onclick=\"history.go(-1)\">cancel</a></p>",
	"show:__formclose__",
	"nocache"
	);

?>