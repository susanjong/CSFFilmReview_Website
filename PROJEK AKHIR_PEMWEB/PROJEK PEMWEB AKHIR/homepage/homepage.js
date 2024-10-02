// Get the image gallery container
const imageGallery = document.querySelector('.image-gallery');

// Get all images in the gallery
const images = imageGallery.querySelectorAll('img');

// Set the current image index
let currentIndex = 0;

// Function to slide to the next image
function nextImage() {
    // Calculate the next index
    const nextIndex = (currentIndex + 1) % images.length;
    // Slide to the next image
    slideTo(nextIndex);
}

// Function to slide to the previous image
function prevImage() {
    // Calculate the previous index
    const prevIndex = (currentIndex - 1 + images.length) % images.length;
    // Slide to the previous image
    slideTo(prevIndex);
}

// Function to slide to a specific image index
function slideTo(index) {
    // Calculate the translateX value
    const translateX = index * -150; // assuming image width is 150px
    // Set the translateX value on the image gallery container
    imageGallery.style.transform = `translateX(${translateX}px)`;
    // Update the current index
    currentIndex = index;
}

// Add event listeners to the navigation buttons
document.querySelector('.next').addEventListener('click', nextImage);
document.querySelector('.prev').addEventListener('click', prevImage);

// Add automatic sliding using the cursor
let intervalId = null;
document.addEventListener('mouseover', () => {
    intervalId = setInterval(nextImage, 3000); // slide every 3 seconds
});
document.addEventListener('mouseout', () => {
    clearInterval(intervalId);
});

// Initialize the slider
slideTo(0);