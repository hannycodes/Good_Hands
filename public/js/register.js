document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registerForm");

  if (!form) {
    console.error("Register form not found");
    return;
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    try {
      const res = await fetch("../php/register.php", {
        method: "POST",
        body: formData
      });

      const data = await res.json();

      if (data.success) {
        alert("Account created successfully");
        window.location.href = "login.html";
      } else {
        alert(data.error || "Registration failed");
      }
    } catch (err) {
      console.error(err);
      alert("Server error. Please try again.");
    }
  });
});
