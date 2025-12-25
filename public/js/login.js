document.getElementById("loginForm")?.addEventListener("submit", async (e) => {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);

 
  const res = await fetch("../php/login.php", {
    method: "POST",
    body: formData
  });

  const data = await res.json();

  if (data.success) {
    alert("Login successful");
    
    if (data.user.role === "admin") {
      window.location.href = "admin-dashboard.html";
    } else {
      window.location.href = "user-dashboard.html";
    }
  } else {
    alert(data.error);
  }
});
