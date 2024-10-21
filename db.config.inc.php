<?php
global $getCurrentHost, $getCurrentURL;
$getCurrentHost = $_SERVER['HTTP_HOST'];
$getCurrentURL = "";

// Determine credentials based on host
$host = "localhost";
if ($getCurrentHost == 'localhost') {
    $username = "root";
    $password = "vertrigo";
} else {
    $username = "dhanwantarikarm_newsite";
    $password = "sW5y2WO0x7";
}

$dbname = "itr_db";
global $DB_LINK_PDO;

try {
    // Initialize the PDO connection
    $DB_LINK_PDO = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set error mode to exceptions
    $DB_LINK_PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle the connection error
    die("Server busy, please try again later: " . $e->getMessage());
}
