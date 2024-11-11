import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
import { getAuth, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";

// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyBw8ejvu5tnxg0SstTK4-IkwIeNADGfEZU",
    authDomain: "auth-3d583.firebaseapp.com",
    projectId: "auth-3d583",
    storageBucket: "auth-3d583.appspot.com",
    messagingSenderId: "783996332274",
    appId: "1:783996332274:web:be22ef49e34ee17e89086b"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

// Display user info
onAuthStateChanged(auth, (user) => {
    if (user) {
        document.getElementById("userName").textContent = user.displayName;
        document.getElementById("userEmail").textContent = user.email;
    } else {
        window.location.href = "index.html";
    }
});

// Sign-out functionality
document.getElementById("signOut").addEventListener("click", () => {
    signOut(auth).then(() => {
        window.location.href = "sign.html";
    }).catch((error) => {
        console.error("Sign-out error:", error);
    });
});