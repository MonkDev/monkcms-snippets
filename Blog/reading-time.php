<?php

/*

	READING TIME
	Calculates the average reading time of a string of text.

*/

function get_reading_time($text){
	$wpm = 300;
	$word_count = str_word_count($text,0);
	$reading_minutes = round($word_count/$wpm);
	if($reading_minutes < 1){$reading_minutes = 1;}
	return $reading_minutes;
}


?>