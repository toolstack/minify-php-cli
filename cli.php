<?php

	include( dirname( __FILE__ ) . '/jsmin-php/src/JSMin/JSMin.php' );

	GLOBAL $argc, $argv;

	// If we have less than two parameters ( [0] is always the script name itself ), bail.
	if( $argc < 2 ) {
		echo 'Error, you must provide at least one js file!' . PHP_EOL;
		exit;
	}
	
	clearstatcache();

	$file_list = array();

	for( $i = 1; $i < $argc; $i++ ) {
		// Check to see if the command line parameter is a directory, if so, look for all the .js/.css files in it.
		if( is_dir( $argv[$i] ) ) {
			$files = scandir( $argv[$i] );

			foreach( $files as $file ) {
				// Only pick up the .js files that aren't already minified.
				if( substr( $file, -3 ) === '.js' && substr( $file, -7 ) !== '.min.js' ) {
					$file_list[] = $argv[$i] . '/' . $file;
				}

				// Only pick up the .css files that aren't already minified.
				if( substr( $file, -4 ) === '.css' && substr( $file, -8 ) !== '.min.css' ) {
					$file_list[] = $argv[$i] . '/' . $file;
				}
			}
		} else if( file_exists( $argv[$i] ) ) {
			// Only add the file if it has a .js extension.
			if( substr( $argv[$i], -3 ) === '.js' && substr( $file, -7 ) !== '.min.js' ) {
				$file_list[] = $argv[$i];
			}

			if( substr( $argv[$i], -4 ) === '.css' && substr( $file, -8 ) !== '.min.css' ) {
				$file_list[] = $argv[$i];
			}
		}
	}

	$files_processed = 0;
	
	// Now minify all the files we have selected.
	foreach( $file_list as $file ) {
		// Create the new filename.
		if( substr( $file, -2 ) === 'js' ) {
			$new_file = substr( $file, 0, -2 ) . 'min.js';
		} else {
			$new_file = substr( $file, 0, -3 ) . 'min.css';
		}

		$update_min_file = true;

		if( file_exists( $new_file ) ) {
			$file_mtime = filemtime( $file );
			$newfile_mtime = filemtime( $new_file );
			
			if( $file_mtime <= $newfile_mtime ) {
				$update_min_file = false;
			}
		}
		
		// If the minified version news updating or doesn't exist, do it.
		if( $update_min_file ) {
			// Read the contents.
			$javascriptCode = file_get_contents( $file );
			
			// Minify the script.
			$javascriptCode = JSMin\JSMin::minify( $javascriptCode );

			// Write the minified script back out.
			file_put_contents( $new_file, $javascriptCode );
			$files_processed ++;
		}
	}

	echo PHP_EOL;
	echo '    Files found: ' . count( $file_list ) . PHP_EOL;
	echo 'Files processed: ' . $files_processed . PHP_EOL;
	echo '  Files skipped: ' . ( count( $file_list ) - $files_processed ) . PHP_EOL;