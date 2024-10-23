<?php
session_start(); // Make sure session is started
include 'database.php'; // Ensure this file returns a PDO instance

// Proses pengiriman review
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $komentar = isset($_POST['komentar']) ? trim($_POST['komentar']) : '';
    $bintang = isset($_POST['bintang']) ? (int)$_POST['bintang'] : 0;

    // Check if user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Validasi input
        if (!empty($komentar)) {
            // Gunakan prepared statement untuk keamanan
            $query = "INSERT INTO endgame_review (komentar, bintang, user_id) VALUES (:komentar, :bintang, :user_id)";
            $stmt = $conn->prepare($query);
            
            // Execute the query with all parameters
            $result = $stmt->execute([':komentar' => $komentar, ':bintang' => $bintang, ':user_id' => $user_id]);

        } else {
            echo "<p>Komentar tidak boleh kosong.</p>";
        }
    } else {
        echo "<p>Silakan login untuk menambahkan review.</p>";
    }
}

// Query untuk mengambil data dari tabel review
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT er.*, u.username FROM endgame_review er JOIN users u ON er.user_id = u.id WHERE er.user_id = :id ORDER BY er.tanggal DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampilan Review</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="tes.css">
</head>

<body>
    <div id="sidebar" class="sidebar">
        <label>
            <input type="checkbox" class="checkbox">
            <div class="toggle">
                <span class="top_line common"></span>
                <span class="middle_line common"></span>
                <span class="bottom_line common"></span>
            </div>
            <div class="slide">
                <p class="Close">Close</p>
                    <a href="#">Homepage</a>
                    <a href="#">Genre</a>
                    <a href="#">Movie List</a>
                    <a href="#">Reviews</a>
                    <a href="#">Updates</a>
                    <a href="#">Contact Us</a>
            </div>
        </label>
    </div>
    <div class="search-bar">
        <input type="text" placeholder="Search Movie..." style="color: white;">
    </div>
    <div class="main-content">
        <div class="poster">

        </div>

        <div class="details">
            <h1>AVENGERS: ENDGAME</h1>
            <div class="year">2019, Anthony Russo & Joe Russo</div>
            <div class="starreview-container">
                <div class="bintang_review">&#9733;</div>
                <div class="starreview">5.0</div>
            </div>
            <br>
            <p class="synopsis">
                After the devastating events of Avengers: Infinity War, the universe is in ruins due to the efforts of the Mad Titan, Thanos. With the help of remaining allies, the Avengers must assemble once more in order to undo Thanos’ actions and restore order to the universe once and for all, no matter what consequences may be in store.
            </p>
            <div class="genre-tags">
                <a class="btn btn-primary" href="#" role="button">Adventure</a>
                <a class="btn btn-primary" href="#" role="button">Action</a>
                <a class="btn btn-primary" href="#" role="button">Drama</a>
                <a class="btn btn-primary" href="#" role="button">Fantasy</a>
            </div>
        </div>
    </div>

    <div class="reviews">
        <h2 class="judul-review">RECENT REVIEWS</h2><button onclick="toggleForm()" class="addreview">+ Add Reviews</button>
        <hr>
        <div class="review-item">
            <h3 class="reviewer">Asep Capek</h3><p class="date">01 Jan 2024</p>
            <p class="review">This movie is a DOPE! I've watched it 100x times a day.</p>
            <div class="rating">Rating: ★ ★ ★ ★ ★ 5.0</div>
        </div>

        <div>
            <?php foreach ($results as $row): ?>
                <div class="review-item">
                    <h3 class="reviewer"><?php echo htmlspecialchars($row['username']); ?></h3>
                    <p class="date"><?php echo htmlspecialchars(date('d M Y', strtotime($row['tanggal']))); ?></p>
                    <p class="review"><?php echo htmlspecialchars($row['komentar']); ?></p>
                    <div class="rating">
                        Rating: <?php 
                            echo str_repeat('★ ', $row['bintang']) . str_repeat('☆ ', 5 - $row['bintang']); 
                            echo ' ' . number_format($row['bintang'], 1); 
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <br><br><br>
        
        <!-- Upload Review Form -->
        <div id="overlay" style="display: none;"></div>
        <div id="reviewForm">
            <div class="reviewForm-container">
            <section class="left-form" style="width:35%;">
                <img src="endgame.webp" style="width:220px;border-radius:20px;margin-top:30px;">
            </section>

            <section class="right-form">
                <h2 style="margin-top: 20px;">I've watched..</h2>
                <h1 style="font-family:Oswald;font-size:35px;font-weight: 700;text-shadow: 1px 1px 1px black;">AVENGERS: ENDGAME
                <span style="font-size:25px;font-weight:400;font-family:Oswald;text-shadow: 1px 1px 1px black;color:#b8dbff">&nbsp2019</span>
                </h1>
                <form action="#" method="post">

                    <textarea id="komentar" name="komentar" placeholder="Add your review.." required></textarea><br>

                    <div class="rating">
                        <label for="bintang" style="font-size: 18px; margin-bottom: 0px">Rating</label>
                        <div class="stars">
                            <span class="star" data-value="1">&#9733;</span>
                            <span class="star" data-value="2">&#9733;</span>
                            <span class="star" data-value="3">&#9733;</span>
                            <span class="star" data-value="4">&#9733;</span>
                            <span class="star" data-value="5">&#9733;</span>
                        </div>
                        <input type="hidden" id="bintang" name="bintang" required><br><br>
                    </div>

                    <div id="closeFormButton" onclick="toggleForm()" class="close-button">×</div>
                    <input type="submit" value="Submit" class="submitbutton">
                </form>
            </section>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById("sidebar");
            var mainContent = document.getElementById("main-content");
            if (sidebar.style.width === "250px") {
                sidebar.style.width = "0";
                mainContent.style.marginLeft = "0";
            } else {
                sidebar.style.width = "250px";
                mainContent.style.marginLeft = "250px";
            }
        }

        function toggleForm() {
            const overlay = document.getElementById('overlay');
            const form = document.getElementById('reviewForm');
            
            if (overlay.style.display === "none") {
                overlay.style.display = "block";
                form.style.display = "block"; 
            } else {
                overlay.style.display = "none";
                form.style.display = "none"; 
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('bintang');

        stars.forEach(star => {
            star.addEventListener('mouseover', selectStars);
            star.addEventListener('mouseout', unselectStars);
            star.addEventListener('click', setRating);
        });

        function selectStars(e) {
            const selectedValue = e.target.dataset.value;
            stars.forEach(star => {
            star.classList.toggle('active', star.dataset.value <= selectedValue);
            });
        }

        function unselectStars() {
            stars.forEach(star => {
            star.classList.remove('active');
            });
            const savedRating = ratingInput.value;
            if (savedRating) {
            stars.forEach(star => {
                star.classList.toggle('active', star.dataset.value <= savedRating);
            });
            }
        }

        function setRating(e) {
            const ratingValue = e.target.dataset.value;
            ratingInput.value = ratingValue;
            selectStars(e);
        }
        });
    </script>
</body>
</html>