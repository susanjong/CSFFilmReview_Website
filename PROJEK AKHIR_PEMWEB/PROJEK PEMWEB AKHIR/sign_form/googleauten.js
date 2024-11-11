// Import Firebase SDK
 import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
import { getAuth, GoogleAuthProvider, signInWithPopup, signOut, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";
      
        // Firebase configuration
        const firebaseConfig = {
          apiKey: "AIzaSyBw8ejvu5tnxg0SstTK4-IkwIeNADGfEZU",
          authDomain: "auth-3d583.firebaseapp.com",
          projectId: "auth-3d583",
          storageBucket: "auth-3d583.firebasestorage.app",
          messagingSenderId: "783996332274",
          appId: "1:783996332274:web:be22ef49e34ee17e89086b"
        };
        
        let app;
        try {
          app = initializeApp(firebaseConfig);
          console.log("Firebase initialized successfully.");
        } catch (error) {
          console.error("Firebase initialization error:", error);
          alert("Failed to initialize Firebase. Check configuration.");
        }
      
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();
      
        // DOM Elements
        const signInButton = document.getElementById("signInButton");
        const signOutButton = document.getElementById("signOutButton");
        const message = document.getElementById("message");
        const userName = document.getElementById("userName");
        const userEmail = document.getElementById("userEmail");
      
        // Sign-In fungsi dengan error handling untuk cek
        signInButton.addEventListener("click", () => {
          signInWithPopup(auth, provider)
            .then((result) => {
              console.log("Signed in as:", result.user.displayName);
              window.location.href = "welcome.html";
            })
            .catch((error) => {
              console.error("Sign-in error:", error);
              alert(`Sign-in failed: ${error.message}`);
            });
        });
      
        // Sign-Out fuungsi 
        signOutButton.addEventListener("click", () => {
          signOut(auth)
            .then(() => {
              alert("You have signed out successfully!");
            })
            .catch((error) => {
              console.error("Sign-out error:", error);
              alert(`Sign-out failed: ${error.message}`);
            });
        });
      
        // Tngetrack pada saar sudah berhasil login 
        onAuthStateChanged(auth, (user) => {
          if (user) {
            signOutButton.style.display = "block";
            message.style.display = "block";
            userName.textContent = user.displayName;
            userEmail.textContent = user.email;
          } else {
            signOutButton.style.display = "none";
            message.style.display = "none";
          }
        });
