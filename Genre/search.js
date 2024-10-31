document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchInput");
    const reviewList = document.getElementById("reviewList");
    const reviews = reviewList.getElementsByClassName("review");

    searchInput.addEventListener("input", function() {
        const filter = searchInput.value.toLowerCase();

        for (let i = 0; i < reviews.length; i++) {
            const movieTitle = reviews[i].getElementsByClassName("movie-title")[0].innerText.toLowerCase();
            if (movieTitle.includes(filter)) {
                reviews[i].style.display = ""; // Show review
            } else {
                reviews[i].style.display = "none"; // Hide review
            }
        }
    });
});