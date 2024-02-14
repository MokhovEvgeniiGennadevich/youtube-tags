<?php
function file_read($file) {
	$lines = file($file);

	$new_array = [];

	foreach ($lines as $line) {
		// Trim spaces
		$line = trim($line);
	
		// Comment
		if (substr($line,0,1) === '#') 
			continue;
	
		// Empty String
		if (strlen($line) === 0) 
			continue;
	
		// Add new name
		$new_array[] = trim($line);
	}

	$new_array = array_unique($new_array);

	return $new_array;
}
function parse_variations($string) {
	$exp = "|\[(.*)\]|Usi";
	preg_match_all($exp, $string, $matches);

	if (isset($matches[1][0]) === false) {
		die("Error: " . $string);
	}

	// Генерируем строки 
	foreach ( $matches[1] as $key => $val ) {
		$ex = explode("|", $val);

		foreach ( $ex as $k => $v ) {
			$replace [$key][$k] = $v;
		}

	}

	$array = [];
	$final = [];

	// Генерируем строки 
	regular($array, $replace, 0, $matches, $string, $final);

	$final = array_unique($final);

	// Проверяем финальный результат
	foreach ($final as $key => $val) {
			// Check if we have [] macros
			if (strpos($val, '[') !== false) {
				throw new Exception("Error parsing message" . $val, 1);
			}

			if (strpos($val, ']') !== false) {
				throw new Exception("Error parsing message" . $val, 1);
			}
	}

	return $final;
}

function regular(&$array, $replace, $key, $matches, $string, &$final) {
	// Добавляем новые строки в массив
	foreach ($replace[$key] as $k => $v) {
		$array [] = str_replace($matches[0][$key], $v, $string);

		// У нас есть ещё один уровень вложенности
		if (isset($replace[$key+1]) !== false) {
			regular($array, $replace, $key + 1, $matches, $array[count($array) -1], $final);
		} else {

			$final []= $array[count($array) -1];
		}
	}
}