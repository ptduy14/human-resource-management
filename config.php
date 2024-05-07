<?php

	$conn = mysqli_connect("localhost", "root", "", "quanly_nhansu");

	if (!$conn) {
	    echo "Error: Unable to connect to MySQL." . PHP_EOL;
	    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
	    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	    exit;
	}

	
	// Set timezone 
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	// set char set
	mysqli_set_charset($conn, 'utf8');
	

?>