<?php

  /*  The end of this provides code on how to display a nested ul of linklists. It only supports two levels. e.g.

  parent1
    - child 1
    - child 2
    - child 3
  parent 2
    - child 1
    - child 2
    - child 3
    
    
  Nested LinkLists keep  the user interface in the CMS clean and contained. It gives the users greater 
  freedom in naming groups of links.
    
  Before I give my solution, I would like to comment on what else is available "out of the box"

  By default, the system would prints heirarchal linklists flatly. For most cases this is fine.

  //all items in the linkslist have a simple li tag
  getContent(
    "linklist",
    "display:links",
    "find:my-link-list",
    "show:<li>__name__</li>"  //show tag prints all levels
  ); 

  Result is:
  parent1
  child 1
  child 2
  child 3
  parent 2
  child 1
  child 2
  child 3


  You can also use the __level__ output option to add a new class. However, as of my last test (Feb 1, 2012), 
  the __level__ option is ignored for first level items. The Second level would appear as "1".

  //In the following example level1 links don't get an openeing li :(
   getContent(
    "linklist",
    "display:links",
    "find:my-link-list",
    "show:<li class='level__level__'>",
    "show:__name__",
    "show:</li>"
  ); 

  For "level__level__" the CMS returns (return value in parentheses):
  parent1 (blank - whole line is hidden)
  child1 ("level1")
  child2 ("level1")
  child3 ("level1")
  parent2 (blank - whole line is hidden)
  child1 ("level1")
  child2 ("level1")
  child3 ("level1")              

           
  What you might expect would be this:
  parent1 ("level1")
  child1 ("level2")
  child2 ("level2")
  child3 ("level2")
  parent2 ("level1)
  child1 ("level2")
  child2 ("level2")
  child3 ("level2")        

  While I can't get those results, something similar can be obtained with this:

  //In the following example first level links have an li with no class, second level links
  //have a class applied.
  getContent(
    "linklist",
    "display:links",
    "find:my-link-list",
    "show:<li ",
    "show: class='level__level__'",
    "show: >",
    "show:__name__",
    "show:</li>"
  ); 


  parent1 ("" - blank but present)
  child1 ("level1")
  child2 ("level1")
  child3 ("level1")
  parent2 ("" - blank but present)
  child1 ("level1")
  child2 ("level1")
  child3 ("level1") 

  MUCH CAN BE ACCOMPLISHED WITH THE ABOVE. Even so, the desired output for for many purposes is a nested ul list:

  parent1
    - child 1
    - child 2
    - child 3
  parent 2
    - child 1
    - child 2
    - child 3

  The following mix of php and api calls gives us the desired output:

  */

   $list_raw = getContent(
    "linklist",
    "display:links",
    "find:my-link-list",
    "level1:</ul></li>", //close previous list
    "level1:<li class='level1'>",
    "level1:<a ", 
    "level1: href='__url__'",
    "level1: __ifnewwindow__target='_blank'",
    "level1:>",
    "level1:__name__",
    "level1:</a>",
    "level1:<ul>",
    "level2:<li>",
    "level2:<a ", 
    "level2: href='__url__'",
    "level2: __ifnewwindow__target='_blank'",
    "level2:>",
    "level2:__name__",
    "level2:</a>",                
    "level2:</li>",
    "noecho"
  );

  $list = preg_replace("~</ul></li>~", "", $list_raw, 1);
  $list .= "</ul></li>";
  $list = "<ul>" . $list . "</ul>";
  $output = $list;  
  
  /* The above option is not very bulletproof. The list must end with a child element in order
    for it to work correctly. If that's not the case, the markup will get messed up.
    There is also the possibility of empty ULs. You could probably strip that out though.
    
    Another option is the code below. It's less efficient as it requires more post processing and requires
    multiple api calls, but it's a little less hacky and more predictable.
  */
  
  //get and store the top level links
  $list_raw = getContent(
    "linklist",
    "display:links",
    "find:my-link-list",
    "level1:__slug__",
    "level1:|~", //separator flag
    "level1:<a ", 
    "level1: href='__url__'",
    "level1: __ifnewwindow__target='_blank'",
    "level1:>",
    "level1:__name__",
    "level1:</a>",
    "level1:~~", //separator flag
    "noecho"
  );

  //make an array of the top level links. We need to store the slug to find the children later on
  //and we need to store the desired output to print out later.
  $list_raw_array = explode("~~", trim($list_raw, "~~")); 
  $level1_array = array();
  foreach($list_raw_array as $level1_raw){
    $tmp_array = explode("|~", trim($level1_raw, "|~"));
    $level1_array[$tmp_array[0]] = $tmp_array[1];
  }

  //the following could be integrated into the above array, but for clarity is set aside here.  
  //iterate through the top level links, find their children and print the results.
  
  $output = "<ul>"; //outermost ul
  foreach($level1_array as $slug => $level1_output){
    
    $output .= "<li class='level1'>"; //top level li
    $output .= $level1_output;

    $level2 = getContent(
      "linklist",
      "display:links",
      "find:my-link-list",
      "parent:".$slug, //this gives only results under this parent
      "level1:<li>",
      "level1:<a ", 
      "level1: href='__url__'",
      "level1: __ifnewwindow__target='_blank'",
      "level1:>",
      "level1:__name__",
      "level1:</a>",                  
      "level1:</li>",
      "noecho"
    );
    
    //if there is second level, print them in a ul
    if($level2){
      $output .= "<ul>";
      $output .= $level2;
      $output .= "</ul>";
    }

    $output .= "</li>"; //close top level li
  }
  $output .= "</ul>"; //close outermost ul

  echo $output;

?>
