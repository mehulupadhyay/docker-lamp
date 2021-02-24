<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */

// Toggle whether in dev (true/false)

define('DEV', 1);  // or 0

define('DB_SERVER', 'localhost');

if(DEV == 1) {
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'asdf1234qwerasdf');
    define('DB_NAME', 'ec_producer');
}
else {
    define('DB_USERNAME', 'producer');
    define('DB_PASSWORD', 'W911CvuZ0sWUzZFp');
    define('DB_NAME', 'ec_producer');
}
 
/* Attempt to connect to MySQL database */
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
?>