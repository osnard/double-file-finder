<?php

//http://www.if-not-true-then-false.com/2012/php-pdo-sqlite3-example/
 try {

	// Create (connect to) SQLite database in file
	$file_db = new PDO('sqlite:files.sqlite3');
	// Set errormode to exceptions
	$file_db->setAttribute(PDO::ATTR_ERRMODE, 
							PDO::ERRMODE_EXCEPTION);
	// Create table messages
	$file_db->exec("CREATE TABLE IF NOT EXISTS files (
					file_sha1 TEXT, 
					file_path TEXT)");

	// Prepare INSERT statement to SQLite3 file db
	$insert = "INSERT INTO files (file_sha1, file_path) 
				VALUES (:file_sha1, :file_path)";
	$stmt = $file_db->prepare($insert);
 
	// Bind parameters to statement variables
	$stmt->bindParam(':file_sha1', $sha1);
	$stmt->bindParam(':file_path', $path);
	
	$targetPath = realpath( __DIR__ );

	$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($targetPath), RecursiveIteratorIterator::SELF_FIRST);
	$count = 0;
	$start = new DateTime();
	foreach($objects as $name => $object){
		if( $object->isDir() ) {
			continue;
		}
		$path = $object->getPathname();
		$sha1 = sha1_file( $path );
		
		$stmt->execute();
		
		echo "$path\n";
		$count++;
	}
	echo "Eingelesene Dateien: $count\n";
	$end = new DateTime();
	$timespan = $end->diff($start);
	echo $timespan->format('%H:%i:%s');

	// Select all data from file db table 
	$result = $file_db->query('SELECT * FROM files');

	foreach($result as $row) {
	  //echo "SHA1: " . $row['file_sha1'] . "\n";
	}

	// Close file db connection
	$file_db = null;
  }
  catch(PDOException $e) {
	// Print PDOException message
	echo $e->getMessage();
  }