<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/helpers.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="style.css" />
    <title>Admin Dashboard - College of Hospitality Management</title>
</head>
<body class="admin-page">
    <header>
      <div class="header">
        <a href="Home.html"
          ><img class="Hm-logo" src="Image/HM-logo-removebg.png" alt="HM logo"
        /></a>
        <button type="button" class="nav-toggle" aria-label="Toggle menu" aria-expanded="false">
          <span class="nav-toggle-bar"></span>
          <span class="nav-toggle-bar"></span>
          <span class="nav-toggle-bar"></span>
        </button>
        <ul class="header-nav">
          <li><a href="Home.html">Home</a></li>
          <li><a href="About.html">About</a></li>
          <li><a href="Home.html#campuses">Campuses</a></li>
          <li><a href="Home.html#contact">Contact</a></li>
          <li class="dropdown">
            <a href="#more" class="dropdown-toggle">More ▾</a>
            <ul class="dropdown-menu">
              <li><a href="Home.html#enrollment">Enrollment Procedures</a></li>
              <li><a href="Annoucement.php">Announcements</a></li>
              <li><a href="Events.php">Events</a></li>
              <li><a href="YoungLeaders.html">Young Leaders</a></li>
              <li><a href="admin_dashboard.php" class="active">AdminLogin</a></li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="young-leaders-hero">
        <div class="young-leaders-logos">
          <div class="yl-logo yl-logo-hm">
            <img src="Image/HM-logo.png" alt="Hospitality Management" />
          </div>
          <div class="yl-logo yl-logo-sjh">
            <img src="Image/sjh.jpg" alt="Society of Junior Hoteliers" />
          </div>
          <div class="yl-logo yl-logo-uc">
            <img src="Image/UCLOGO.png" alt="University of Cebu" />
          </div>
        </div>
        <h1 class="young-leaders-title">College of Hospitality Management</h1>
        <h2 class="young-leaders-subtitle">Admin Dashboard</h2>
        <h3 class="young-leaders-subtitle">Welcome, <?= h($_SESSION['admin_username']) ?></h3>
      </div>
    </header>

    <main class="admin-dashboard-content">
      <div class="dashboard-grid">
        <a class="dashboard-card" href="admin_announcements.php">
          <h2>Manage Announcements</h2>
          <p>Add, edit, or remove announcement cards and their images.</p>
        </a>
        <a class="dashboard-card" href="admin_events.php">
          <h2>Manage Events</h2>
          <p>Add, edit, or remove events and their photo slideshows.</p>
        </a>
      </div>
      <a class="dashboard-logout" href="logout.php">Log out</a>
    </main>

    <footer class="footer-section" id="contact">
      <div class="footer-content">
        <div class="footer-column">
          <h3>College of Hospitality & Tourism Management</h3>
          <p>
            Preparing the next generation of hospitality and tourism professionals
            through excellence in education and industry partnerships.
          </p>
        </div>
        <div class="footer-column">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="Home.html">Home</a></li>
            <li><a href="About.html">About</a></li>
            <li><a href="Home.html#campuses">Campuses</a></li>
            <li><a href="Home.html#partners">Partners</a></li>
          </ul>
        </div>
        <div class="footer-column">
          <h4>Contact Us</h4>
          <p>Email: info@uc.edu.ph</p>
          <p>Phone: (032) 255-1777</p>
          <p>Address: Sanciangko St., Cebu, Cebu City</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2026 University of Cebu College of Hospitality & Tourism Management. All rights reserved.</p>
      </div>
    </footer>

    <script>
      (function () {
        var toggle = document.querySelector(".nav-toggle");
        var nav = document.querySelector(".header-nav");
        if (toggle && nav) {
          toggle.addEventListener("click", function () {
            document.body.classList.toggle("nav-open");
            toggle.setAttribute("aria-expanded", document.body.classList.contains("nav-open"));
          });
          nav.querySelectorAll("a").forEach(function (a) {
            a.addEventListener("click", function () { document.body.classList.remove("nav-open"); if (toggle) toggle.setAttribute("aria-expanded", "false"); });
          });
          document.querySelectorAll(".dropdown-toggle").forEach(function (btn) {
            btn.addEventListener("click", function (e) { if (window.innerWidth <= 900) { e.preventDefault(); btn.closest(".dropdown").classList.toggle("open"); } });
          });
        }
      })();
    </script>
</body>
</html>
