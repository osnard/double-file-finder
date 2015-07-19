<?php

//http://www.if-not-true-then-false.com/2012/php-pdo-sqlite3-example/
try {
	$file_db = new PDO('sqlite:files.sqlite3');
	$file_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

	$file_db->exec(
	"CREATE TABLE IF NOT EXISTS files (
			file_sha1 TEXT,
			file_path TEXT
		)"
	);

	$insert = "INSERT INTO files (file_sha1, file_path) VALUES (:file_sha1, :file_path)";
	$stmt = $file_db->prepare( $insert );

	$stmt->bindParam( ':file_sha1', $sha1 );
	$stmt->bindParam( ':file_path', $path );

	$targetPath = realpath( $argv[1] );

	$files = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $targetPath ),
		RecursiveIteratorIterator::SELF_FIRST
	);

	$count = 0;
	$start = new DateTime();

	foreach( $files as $name => $file ){
		if( $file->isDir() ) {
			continue;
		}

		$path = $file->getPathname();
		$sha1 = sha1_file( $path );

		$stmt->execute();

		echo "$path\n";
		$count++;
	}

	echo "Scanned files: $count\n";

	$end = new DateTime();
	$timespan = $end->diff($start);
	echo $timespan->format('%H:%i:%s');

	$file_db = null;
}
catch( PDOException $e ) {
	echo $e->getMessage();
}