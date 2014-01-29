<?php


	// Outputs all child categories from a parent.
	
	function child_categories($module,$parent_category){
		$categories = 
		getContent(
			$module,
			"display:categories",
			"parent_category:" . $parent_category,
			"level1:__slug__,",
			"level2:__slug__,",
			"level3:__slug__,",
			"level4:__slug__,",
			"level5:__slug__,",
			"noecho",
			"noedit"
		);
		$categories = trim($categories,',');
		return $categories;
	}
	

?>