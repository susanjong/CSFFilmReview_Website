<?php
session_start(); // Pastikan session sudah dimulai
include 'database.php'; // Pastikan file ini mengembalikan instance PDO
include 'delete_annabelle.php';

// Proses pengiriman review
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $komentar = isset($_POST['komentar']) ? trim($_POST['komentar']) : '';
    $bintang = isset($_POST['bintang']) ? (int)$_POST['bintang'] : 0;
    
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Validasi input
        if (!empty($komentar)) {
            // Gunakan prepared statement untuk keamanan
            $query = "INSERT INTO annabelle_review (komentar, bintang, user_id) VALUES (:komentar, :bintang, :user_id)";
            $stmt = $conn->prepare($query);
            
            // Execute the query with all parameters
            $result = $stmt->execute([':komentar' => $komentar, ':bintang' => $bintang, ':user_id' => $user_id]);

        } else {
            echo "<p>Komentar tidak boleh kosong.</p>";
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;  
}

// Query untuk mengambil data dari tabel review
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT er.*, u.username FROM annabelle_review er JOIN users u ON er.user_id = u.id ORDER BY er.tanggal DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$average = ['rata_rata_bintang' => 0]; // Default value

// Fetch the average rating
try {
    $avgQuery = "SELECT AVG(bintang) AS rata_rata_bintang FROM annabelle_review";
    $avgStmt = $conn->prepare($avgQuery);
    $avgStmt->execute();
    $average = $avgStmt->fetch(PDO::FETCH_ASSOC);

    // If no ratings exist, set default
    if ($average === false || $average['rata_rata_bintang'] === null) {
        $average['rata_rata_bintang'] = 0; // Set default if no average
    }
} catch (Exception $e) {
    // Log the error message for debugging
    error_log($e->getMessage());
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
    <link rel="stylesheet" href="/Film/Annabelle/annabelle.css">
</head>

<body>
<header>
    <section class="header-center">
        <div id="sidebar" class="sidebar">
            <label>
                <input type="checkbox" class="checkbox" id="toggle">
                <div class="toggle">
                    <span class="top_line common"></span>
                    <span class="middle_line common"></span>
                    <span class="bottom_line common"></span>
                </div>
                <div class="slide">
                    <p class="Close">Close</p>
                    <a href="/PROJEK AKHIR_PEMWEB/PROJEK PEMWEB AKHIR/tampilan awal/film.html"><i class="fas fa-home"></i>&nbsp Homepage</a>
                    <a href="/Genre/Genre.html"><i class="fas fa-film"></i>&nbsp Genre</a>
                    <a href="/Genre/MovieList.html"><i class="fas fa-list"></i>&nbsp Movie List</a>
                    <a href="/PROJEK AKHIR_PEMWEB/PROJEK PEMWEB AKHIR/ContactUs.html"><i class="fas fa-envelope"></i>&nbsp Contact Us</a>
                </div>
            </label>
        </div>
    

        <div class="box">
            <input type="text" placeholder="Search Movie...">
            <i class="fas fa-search" style = "color: #45525f;"></i>
        </div>                
    </section>

    <div class="logo">CSFFilmReview</div>
</header>

    <div class="main-content">
        <div class="poster">

        </div>

        <div class="details">
            <h1>ANNABELLE</h1>
            <div class="year">2014, John R. Leonetti</div>
            <div class="starreview-container">
                <div class="bintang_review">&#9733;</div>
                <div class="starreview" id="averageRating"><?php echo number_format($average['rata_rata_bintang'], 1); ?></div>
            </div>
            <br>
            <p class="synopsis">
                John Form thinks he's found the perfect gift for his expectant wife, Mia: a vintage doll in a beautiful white dress. However, the couple's delight doesn't last long: One terrible night, devil worshippers invade their home and launch a violent attack against the couple. When the cultists try to summon a demon, they smear a bloody rune on the nursery wall and drip blood on Mia's doll, thereby turning the former object of beauty into a conduit for ultimate evil.
            </p>
            <div class="genre-tags">
                <a class="btn btn-primary" href="/Genre/Horror.html" role="button">Horror</a>
                <a class="btn btn-primary" href="/Genre/Thriller.html" role="button">Thriller</a>
                <a class="btn btn-primary" href="/Genre/Mystery.html" role="button">Mystery</a>
            </div>
        </div>
    </div>

    <div class="reviews">
        <h2 class="judul-review">RECENT REVIEWS</h2><button onclick="toggleForm()" class="addreview">+ Add Reviews</button>
        <hr>

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
                        
                        <?php if (isset($_SESSION['user_id']) && (int)$row['user_id'] === (int)$_SESSION['user_id']): ?>
                            <svg width="24" height="24" class="editreview_button">
                                <circle cx="12" cy="6" r="1.5"/>
                                <circle cx="12" cy="12" r="1.5"/>
                                <circle cx="12" cy="18" r="1.5"/>
                            </svg>
                            
                            <div class="dropdown" style="display: none;">
                                <button onclick="deleteReview(<?php echo $row['id']; ?>)">Delete</button>
                            </div>
                        <?php endif; ?>
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
                <img src="/Photos/AgeofUltron.webp" style="width:220px;border-radius:20px;margin-top:30px;">
            </section>

            <section class="right-form">
                <h2 style="margin-top: 20px;">I've watched..</h2>
                <h1 style="font-family:Oswald;font-size:35px;font-weight: 700;text-shadow: 1px 1px 1px black;">AVENGERS: AGE OF ULTRON
                <span style="font-size:25px;font-weight:400;font-family:Oswald;text-shadow: 1px 1px 1px black;color:#b8dbff">&nbsp2015</span>
                </h1>
                <form action="#" method="post">

                    <textarea id="komentar" name="komentar" placeholder="Add your review.." required></textarea><br>

                    <div class="rating_">
                        <label for="bintang" style="font-size: 18px; margin-right: 15px;">Rating</label>
                        <div class="stars">
                            <span class="star" data-value="1">&#9733;</span>
                            <span class="star" data-value="2">&#9733;</span>
                            <span class="star" data-value="3">&#9733;</span>
                            <span class="star" data-value="4">&#9733;</span>
                            <span class="star" data-value="5">&#9733;</span>
                        </div>
                        <input type="hidden" id="bintang" name="bintang" required>
                        <input type="hidden" name="reviewId" value="<?= $review ? $review['id'] : '' ?>">
                    </div>
                    <br><br>      

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

        const starReviewElement = document.getElementById('averageRating');

        const averageRating = <?php echo json_encode(number_format($average['rata_rata_bintang'], 1)); ?>;
        starReviewElement.innerText = averageRating;

        document.querySelectorAll('.editreview_button').forEach(button => {
            button.addEventListener('click', toggleDropdown);
        });

        function toggleDropdown(event) {
            const dropdown = event.target.nextElementSibling; 
            const isVisible = dropdown.style.display === 'block';
            
            document.querySelectorAll('.dropdown').forEach(d => {
                d.style.display = 'none';
            });
            
            dropdown.style.display = isVisible ? 'none' : 'block';
            event.stopPropagation(); 
        }

        function deleteReview(reviewId) {
                if (confirm("Apakah Anda yakin ingin menghapus review ini?")) {
                const params = new URLSearchParams();
                params.append('reviewId', reviewId);

                fetch('delete_annabelle.php', {
                    method: 'POST',
                    body: params
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload(); // Refresh halaman setelah penghapusan
                })
               
                .catch(error => {
                    console.error('Error:', error);
                    alert("Terjadi kesalahan saat menghapus review.");
                });
            }
        }
    </script>
</body>
</html>