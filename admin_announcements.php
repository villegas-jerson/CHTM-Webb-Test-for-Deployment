<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/helpers.php';

$errors = [];
$successMessage = null;

// ---- Handle delete ----
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $conn->prepare('SELECT image_path FROM announcements WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        $stmt = $conn->prepare('DELETE FROM announcements WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        if (!empty($row['image_path']) && strpos($row['image_path'], UPLOAD_URL_ANNOUNCEMENTS) === 0) {
            $filePath = __DIR__ . '/' . $row['image_path'];
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }
    }
    header('Location: admin_announcements.php');
    exit;
}

// ---- Handle create / update ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null;
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $sortOrder = (int) ($_POST['sort_order'] ?? 0);
    $removeImage = isset($_POST['remove_image']);

    if ($title === '' || $body === '') {
        $errors[] = 'Title and content are both required.';
    }

    $newImagePath = null;
    if (!$errors) {
        try {
            $newImagePath = handle_image_upload('image', UPLOAD_DIR_ANNOUNCEMENTS, UPLOAD_URL_ANNOUNCEMENTS);
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
        }
    }

    if (!$errors) {
        if ($id) {
            // Fetch existing image so we know what to keep/replace/delete
            $stmt = $conn->prepare('SELECT image_path FROM announcements WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $existing = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            $finalImagePath = $existing['image_path'] ?? null;
            if ($newImagePath) {
                $finalImagePath = $newImagePath;
            } elseif ($removeImage) {
                $finalImagePath = null;
            }

            $stmt = $conn->prepare('UPDATE announcements SET title = ?, body = ?, image_path = ?, sort_order = ? WHERE id = ?');
            $stmt->bind_param('sssii', $title, $body, $finalImagePath, $sortOrder, $id);
            $stmt->execute();
            $stmt->close();
            $successMessage = 'Announcement updated.';
        } else {
            $stmt = $conn->prepare('INSERT INTO announcements (title, body, image_path, sort_order) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('sssi', $title, $body, $newImagePath, $sortOrder);
            $stmt->execute();
            $stmt->close();
            $successMessage = 'Announcement added.';
        }
    }
}

// ---- Load record for editing, if requested ----
$editRow = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM announcements WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $editRow = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// ---- Load all announcements for the list ----
$announcements = $conn->query('SELECT * FROM announcements ORDER BY sort_order ASC, id DESC');
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
    <title>Manage Announcements - Admin</title>
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
          <li><a href="admin_dashboard.php">Dashboard</a></li>
          <li><a href="admin_announcements.php" class="active">Announcements</a></li>
          <li><a href="admin_events.php">Events</a></li>
          <li><a href="logout.php">Log out</a></li>
        </ul>
      </div>
    </header>

    <main class="admin-crud-content">
      <h1 class="admin-crud-title"><?= $editRow ? 'Edit Announcement' : 'Add Announcement' ?></h1>

      <?php if ($errors): ?>
        <div class="admin-alert admin-alert-error">
          <?php foreach ($errors as $e): ?><p><?= h($e) ?></p><?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ($successMessage): ?>
        <div class="admin-alert admin-alert-success"><p><?= h($successMessage) ?></p></div>
      <?php endif; ?>

      <form class="admin-form" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= h($editRow['id'] ?? '') ?>">

        <label>Title
          <input type="text" name="title" value="<?= h($editRow['title'] ?? '') ?>" required>
        </label>

        <label>Content
          <textarea name="body" rows="8" required><?= h($editRow['body'] ?? '') ?></textarea>
          <small>Basic HTML tags like &lt;p&gt;, &lt;strong&gt;, &lt;br&gt; are allowed.</small>
        </label>

        <label>Display order (lower shows first)
          <input type="number" name="sort_order" value="<?= h($editRow['sort_order'] ?? 0) ?>">
        </label>

        <label>Image
          <input type="file" name="image" accept="image/*">
        </label>

        <?php if (!empty($editRow['image_path'])): ?>
          <div class="admin-current-image">
            <img src="<?= h($editRow['image_path']) ?>" alt="Current image">
            <label class="admin-checkbox-label">
              <input type="checkbox" name="remove_image"> Remove this image
            </label>
          </div>
        <?php endif; ?>

        <div class="admin-form-actions">
          <button type="submit" class="Login"><?= $editRow ? 'Save Changes' : 'Add Announcement' ?></button>
          <?php if ($editRow): ?>
            <a class="admin-cancel-link" href="admin_announcements.php">Cancel</a>
          <?php endif; ?>
        </div>
      </form>

      <h2 class="admin-crud-subtitle">Existing Announcements</h2>
      <div class="admin-list">
        <?php while ($row = $announcements->fetch_assoc()): ?>
          <div class="admin-list-item">
            <?php if (!empty($row['image_path'])): ?>
              <img src="<?= h($row['image_path']) ?>" alt="" class="admin-list-thumb">
            <?php endif; ?>
            <div class="admin-list-info">
              <h3><?= h($row['title']) ?></h3>
              <p class="admin-list-order">Order: <?= h($row['sort_order']) ?></p>
            </div>
            <div class="admin-list-actions">
              <a href="admin_announcements.php?edit=<?= h($row['id']) ?>" class="admin-btn-edit">Edit</a>
              <a href="admin_announcements.php?delete=<?= h($row['id']) ?>" class="admin-btn-delete" onclick="return confirm('Delete this announcement?');">Delete</a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </main>

    <footer class="footer-section" id="contact">
      <div class="footer-bottom">
        <p>&copy; 2026 University of Cebu College of Hospitality & Tourism Management. All rights reserved.</p>
      </div>
    </footer>
</body>
</html>
