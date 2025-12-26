document.addEventListener('DOMContentLoaded', () => {
    // --- 1. PASSWORD TOGGLE LOGIC ---
    // This must be OUTSIDE the submit listener to work immediately
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    if (togglePassword) {
        togglePassword.addEventListener('click', (e) => {
            // Stop the button from trying to submit the form
            e.preventDefault(); 
            
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        });
    }

    // --- 2. LOGIN FORM SUBMISSION ---
    const loginForm = document.getElementById('loginForm');
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const errorDiv = document.getElementById('error-msg');
        if (errorDiv) errorDiv.style.display = 'none';

        const formData = new FormData(loginForm);

        try {
            const response = await fetch('../php/login.php', {
                method: 'POST',
                body: formData
            });

            const rawText = await response.text();
            console.log("Server Response:", rawText);

            let result;
            try {
                result = JSON.parse(rawText);
            } catch (jsonErr) {
                console.error("Invalid JSON:", rawText);
                alert("Server Error: Check Console (F12) to see PHP errors.");
                return;
            }

            if (result && result.success) {
                // Redirect based on role
                if (result.role === 'admin') {
                    window.location.href = 'admin-dashboard.html';
                } else {
                    window.location.href = 'user-dashboard.html';
                }
            } else {
                if (errorDiv) {
                    errorDiv.textContent = result.error || "Login failed.";
                    errorDiv.style.display = 'block';
                } else {
                    alert(result.error);
                }
            }

        } catch (err) {
            console.error("System Error:", err);
            alert("Could not connect to the server.");
        }
    });
});