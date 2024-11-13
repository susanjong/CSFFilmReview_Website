<?php
include 'database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reviewId = isset($_POST['reviewId']) ? (int)$_POST['reviewId'] : null;
    if ($reviewId !== null) {
        // Siapkan statement untuk menghapus record
        $stmt = $conn->prepare("DELETE FROM eeaao_review WHERE id = :id");
        $stmt->execute(['id' => $reviewId]);
        // Cek apakah ada baris yang terhapus
        if ($stmt->rowCount() > 0) {
            echo "Review successfully deleted.";
        } else {
            echo "Review not found or has already been deleted.";
        }
    } else {
        echo "ID review is not valid.";
    }
}
?>