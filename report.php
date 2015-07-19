<?php

//http://www.if-not-true-then-false.com/2012/php-pdo-sqlite3-example/
try {
	$file_db = new PDO('sqlite:files.sqlite3');
	$file_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

	$result = $file_db->query('SELECT f1.file_path AS p1, f2.file_path AS p2 FROM files AS f1 LEFT JOIN files AS f2 ON f1.file_sha1 = f2.file_sha1 WHERE f1.file_path != f2.file_path');

	foreach( $result as $row ) {
		echo $row['p1'] . ' --> ' . $row['p2'] . "\n";
	}

	$file_db = null;
}
catch( PDOException $e ) {
	echo $e->getMessage();
}