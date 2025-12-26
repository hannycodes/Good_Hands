document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registerForm");
  const btn = form.querySelector('button'); btn.disabled = true; btn.textContent = "Creating Account...";
  if (!form) {
    console.error("Register form not found");
    return;
  }
 const errorDiv = document.getElementById("error-msg");
if (errorDiv) errorDiv.style.display = "none";
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    if (formData.get('password') !== formData.get('confirm_password')) {
    const errorDiv = document.getElementById("error-msg");
    errorDiv.textContent = "Passwords do not match!";
    errorDiv.style.display = "block";
    return; // Stop the function here
}

    try {
      const res = await fetch("../php/register.php", {
        method: "POST",
        body: formData
      });
      
      btn.disabled = false; btn.textContent = "Sign Up";
      const data = await res.json();

      if (data.success) {
        alert("Account created successfully");
        window.location.href = "login.html";
      } else {
        const errorDiv = document.getElementById("error-msg");
errorDiv.textContent = data.error || "Registration failed";
errorDiv.style.display = "block";
      }
    } catch (err) {
      console.error(err);
      alert("Server error. Please try again.");
    }

  });
});
