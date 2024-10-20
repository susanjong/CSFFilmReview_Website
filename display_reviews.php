<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi ke PostgreSQL
$host = "localhost";
$dbname = "Review"; // Nama database
$user = "postgres";
$password = "password";

$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Proses pengiriman review
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $komentar = isset($_POST['komentar']) ? trim($_POST['komentar']) : '';
    $bintang = isset($_POST['bintang']) ? (int)$_POST['bintang'] : 0;

    // Validasi input
    if (!empty($komentar)) {
        
        // Gunakan prepared statement untuk keamanan
        $query = "INSERT INTO endgame_review (komentar, bintang) VALUES ($1, $2)";
        $result = pg_query_params($conn, $query, array($komentar, $bintang));
        
        if ($result) {
            header("Location: ".$_SERVER['PHP_SELF']."?success=1");
            exit;
        } else {
            echo "<p>Terjadi kesalahan saat menambahkan review.</p>";
        }
    } else {
        echo "<p>Komentar tidak boleh kosong.</p>";
    }
}

// Query untuk mengambil data dari tabel review
$query = "SELECT komentar, bintang, tanggal FROM endgame_review ORDER BY id DESC"; // Asumsi ada kolom 'id' untuk urutan
$result = pg_query($conn, $query);

if (!$result) {
    die("Error in SQL query: " . pg_last_error($conn));
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
    <style>
        #reviewForm {
            display: none; 
            position: fixed;
            width: 600px;
            height: 600px;
            top: 15%; 
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(to bottom right, #003366, #0066CC);
            box-shadow: 0 0 20px black;
            border-radius: 20px;
            border: 1px solid #ccc; 
            padding: 20px; 
            z-index: 100;
        }

        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7); 
            z-index: 1;   
        }

        form {
            width: 100%; 
            padding: 20px;
        }

        textarea {
            width: 100%; 
            width: 515px;
            min-height: 250px;
            max-height: 250px;
            margin-bottom: 5px; 
            margin-top: 8px;
        }

        label {
            margin-top: 7px;
            margin-bottom: 5px;;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 40px;
            cursor: pointer;
            color: white;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
        }

        .submitbutton {
            display: block;
            margin-left: auto;
            padding: 5px 10px;
            background: linear-gradient(to bottom right, #6e0909,#9a0909);
            box-shadow: 0 0 2px black;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            color: white;
        }

        .submitbutton:hover{
            background-color: #9a0909;
            box-shadow: 0 0 5px black;
        }
    </style>
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
        <div class="poster"></div>
        <div class="details">
            <h1>AVENGERS: ENDGAME</h1>
            <div class="year">2019, Anthony Russo & Joe Russo</div>
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
        
        <div class="review-item">
            <h3 class="reviewer">Budi Kece</h3><p class="date">25 Apr 2024</p>
            <p class="review">It's so boring ngl.</p>
            <div class="rating">Rating: ★ ☆ ☆ ☆ ☆ 1.0</div>
        </div>
        <div class="review-item">
            <h3 class="reviewer">Clar Yapping</h3><p class="date">29 Dec 2024</p>
            <p class="review">The whole character is so cool. I want to be like Ironman. He's super selfless and cool. I love him 3000...</p>
            <div class="rating">Rating: ★ ★ ★ ☆ ☆ 3.0</div>
        </div>

        <div>
        <?php while ($row = pg_fetch_assoc($result)): ?>
            <div class="review-item">
                <p class="date"><?php echo htmlspecialchars(date('d M Y', strtotime($row['tanggal']))); ?></p>
                <p class="review"><?php echo htmlspecialchars($row['komentar']); ?></p>
                <div class="rating">Rating: <?php echo str_repeat('★', $row['bintang']) . str_repeat('☆', 5 - $row['bintang']); ?> <?php echo number_format($row['bintang'], 1); ?></div>
            </div>
        <?php endwhile; ?>
        </div>
        
        <div id="overlay" style="display: none;"></div>
        <div id="reviewForm">
        <h2 style="display: flex; justify-content:center; margin-top: 25px; font-weight: 350; font-size: 33px">Add Your Personal Review</h2>
        <form action="#" method="post">

            <label for="komentar" style="font-size: 20px">Review</label><br>
            <textarea id="komentar" name="komentar" required></textarea><br><br>

            <label for="bintang" style="font-size: 20px">☆(1-5)&nbsp; </label>
            <input type="number" id="bintang" name="bintang" min="1" max="5" required style="margin-bottom:17px;"><br><br>

            <div id="closeFormButton" onclick="toggleForm()" class="close-button">×</div>
            <input type="submit" value="Submit" class="submitbutton">
        </form>
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

    </script>
</body>
</html>
