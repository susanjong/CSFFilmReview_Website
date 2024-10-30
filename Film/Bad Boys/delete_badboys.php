<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reviewId = isset($_POST['reviewId']) ? (int)$_POST['reviewId'] : null;

    if ($reviewId !== null) {
        // Siapkan statement untuk menghapus record
        $stmt = $conn->prepare("DELETE FROM badboys_review WHERE id = :id");
        $stmt->execute(['id' => $reviewId]);

        // Cek apakah ada baris yang terhapus
        if ($stmt->rowCount() > 0) {
            echo "Review berhasil dihapus.";
        } else {
            echo "Review tidak ditemukan atau sudah dihapus.";
        }
    } else {
        echo "ID review tidak valid.";
    }
}
?>