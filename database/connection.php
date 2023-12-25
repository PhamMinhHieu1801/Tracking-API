<?php
$databaseHost = 'localhost';
$databaseName = 'booking';
$databaseUsername = 'root';
$databasePassword = '';

try {
	$dbConn = new PDO("mysql:host={$databaseHost};dbname={$databaseName}", 
						$databaseUsername, $databasePassword);
	$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo $e->getMessage();
}

function isValidInput($data) {
    // Kiểm tra xem dữ liệu có tồn tại không
    if (isset($data) && !empty($data)) {
        return true;
    } else {
        // Dữ liệu không tồn tại
        return false;
    }
}