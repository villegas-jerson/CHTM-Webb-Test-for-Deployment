<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/helpers.php';

$events = $conn->query('SELECT * FROM events ORDER BY sort_order ASC, id DESC');
$eventList = [];
while ($row = $events->fetch_assoc()) {
    $imgStmt = $conn->prepare('SELECT image_path FROM event_images WHERE event_id = ? ORDER BY sort_order ASC, id ASC');
    $imgStmt->bind_param('i', $row['id']);
    $imgStmt->execute();
    $images = $imgStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $imgStmt->close();
    $row['images'] = $images;
    $eventList[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="style.css" />
    <title>Events - College of Hospitality & Tourism Management</title>
  </head>
  <body class="events-page">
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
              <li><a href="Events.php" class="active">Events</a></li>
              <li><a href="YoungLeaders.html">Young Leaders</a></li>
              <li><a href="Admin.html">AdminLogin</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </header>

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
      <h1 class="young-leaders-title">College of Hospitality and Tourism Management</h1>
      <h2 class="young-leaders-subtitle">Events &amp; Activities</h2>
    </div>

    <main class="events-content">
      <section class="events-gallery-section">
        <?php if (empty($eventList)): ?>
          <p class="announcement-empty">No events posted yet. Please check back soon.</p>
        <?php endif; ?>

        <?php foreach ($eventList as $index => $event): ?>
          <div class="event-hero-block">
            <div class="event-slides" data-event-slides="<?= h($event['id']) ?>">
              <?php foreach ($event['images'] as $img): ?>
                <img src="<?= h($img['image_path']) ?>" alt="<?= h($event['title']) ?>" />
              <?php endforeach; ?>
            </div>
            <div class="event-hero-overlay">
              <h2 class="event-hero-title"><?= h($event['title']) ?></h2>
              <?php if (!empty($event['subtitle'])): ?>
                <p class="event-hero-subtitle"><?= h($event['subtitle']) ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </section>
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
        function initEventSlideshow(container, intervalMs) {
          var slides = container.querySelectorAll("img");
          if (!slides.length) return;
          var current = 0;
          slides.forEach(function (img, index) {
            img.classList.toggle("is-active", index === 0);
          });
          if (slides.length > 1) {
            setInterval(function () {
              slides[current].classList.remove("is-active");
              current = (current + 1) % slides.length;
              slides[current].classList.add("is-active");
            }, intervalMs);
          }
        }

        document.querySelectorAll("[data-event-slides]").forEach(function (container) {
          initEventSlideshow(container, 3500);
        });
      })();
    </script>
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
    <script type="module">
      import Chatbot from "https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js";
      Chatbot.init({
        chatflowid: "c51a8a2e-9973-4c74-b258-0ca4db210f0d",
        apiHost: "https://cloud.flowiseai.com",
      });
    </script>
  </body>
  </html>
