<?php

include('functions.php');

// Parse

$parse = array('channels', 'video');

foreach ($parse as $dir) {
	$files = array_diff(scandir('src/'.$dir), array('..', '.'));

	// Parse files
	
	foreach ($files as $file) {
		$tags = file_read('src/'.$dir . '/' .$file);
	
		// Result
		$result = implode(', ', $tags);

		// Save in Result folder
		file_put_contents('result/'.$dir.'/'.$file, implode(', ', $tags));
	}
}



