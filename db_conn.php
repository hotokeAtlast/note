<?php

$suser = "lcoalhost";
$shost = "root";
$spass = "";
$db = "mynotes";

date_default_timezone_set('Asia/Kolkata');
$time = date("h:ia");
$date = date("d/m/Y");
// $db_conn = mysqli_connect($user, $host, $pass, $db);
// INSERT INTO `notes` (`sno`, `id`, `title`, `content`, `date`, `time`) VALUES ('1', '69', 'my_gt', 'its awesome', '20/04/2025', '22:12');

$mysqli = new mysqli("localhost", "root", "", "mynotes");
if($mysqli -> connect_errno){
    echo "failed to connect to database because ". $mysqli -> connect_error;
    exit();
}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////






$host = 'localhost';
$db   = 'mynotes';
$mcuser = 'root';
$pass = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $mcuser, $pass);

    // Set error mode to throw exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>