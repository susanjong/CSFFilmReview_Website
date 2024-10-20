<?php
// database.php
$host = "localhost";    // Change this if you are hosting remotely
$dbname = "CSFFilmReview";  // Replace with your database name
$user = "postgres";    // Replace with your PostgreSQL username
$pass = "shinichi"; // Replace with your PostgreSQL password

try {
    // Create a new PDO instance
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
     $e->getMessage();
}
?>