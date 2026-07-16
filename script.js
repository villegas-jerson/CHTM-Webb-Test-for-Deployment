(function () {
  // Hardcoded admin credentials (client-side demo only — not secure)
  var ADMIN_USERNAME = "admin";
  var ADMIN_PASSWORD = "chtm2026";

  var loginForm = document.querySelector(".login-card form");
  var usernameInput = document.getElementById("Uname");
  var passwordInput = document.getElementById("Pass");

  if (loginForm) {
    // Create an error message element (hidden by default)
    var errorMsg = document.createElement("p");
    errorMsg.className = "login-error";
    errorMsg.style.display = "none";
    errorMsg.textContent = "Invalid username or password. Please try again.";
    loginForm.appendChild(errorMsg);

    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();

      var enteredUsername = usernameInput.value.trim();
      var enteredPassword = passwordInput.value;

      if (enteredUsername === ADMIN_USERNAME && enteredPassword === ADMIN_PASSWORD) {
        errorMsg.style.display = "none";
        // Successful login - redirect to the admin dashboard
        window.location.href = "AdminDashboard.html";
      } else {
        errorMsg.style.display = "block";
        passwordInput.value = "";
        passwordInput.focus();
      }
    });
  }
})();