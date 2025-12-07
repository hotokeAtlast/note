<?php
$host = 'localhost';
$db   = 'mynotes';
$user = 'root';
$pass = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);

    // Set error mode to throw exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// $query = "SELECT * FROM `login`";
// $sql = mysqli_query($pdo, $query);
// $echo = mysqli_fetch_all($sql);
// echo "<pre>";
// 		print_r($a);	
// echo "</pre>";

$sub_pdo = $pdo->query("SELECT * FROM `notes`");
$stf = $sub_pdo->fetchAll();

foreach($stf as $note){
    echo "<h2>" . htmlspecialchars($note['title'], ENT_QUOTES, 'UTF-8') . "</h2>";
    echo $note['content'];
}
?>