<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/helpers.php';

$errors = [];
$successMessage = null;

// ---- Delete a single image ----
if (isset($_GET['delete_image'])) {
    $imgId = (int) $_GET['delete_image'];
    $stmt = $conn->prepare('SELECT image_path FROM event_images WHERE id = ?');
    $stmt->bind_param('i', $imgId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        $stmt = $conn->prepare('DELETE FROM event_images WHERE id = ?');
        $stmt->bind_param('i', $imgId);
        $stmt->execute();
        $stmt->close();

        $filePath = __DIR__ . '/' . $row['image_path'];
        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
    header('Location: admin_events.php?edit=' . (int) ($_GET['event_id'] ?? 0));
    exit;
}

// ---- Delete an entire event (and its images) ----
if (isset($_GET['delete_event'])) {
    $eventId = (int) $_GET['delete_event'];
    $imgs = $conn->prepare('SELECT image_path FROM event_images WHERE event_id = ?');
    $imgs->bind_param('i', $eventId);
    $imgs->execute();
    $imgResult = $imgs->get_result();
    while ($img = $imgResult->fetch_assoc()) {
        $filePath = __DIR__ . '/' . $img['image_path'];
        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
    $imgs->close();

    $stmt = $conn->prepare('DELETE FROM events WHERE id = ?');
    $stmt->bind_param('i', $eventId);
    $stmt->execute();
    $stmt->close();

    header('Location: admin_events.php');
    exit;
}

// ---- Handle form submissions ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'save_event';

    if ($action === 'save_event') {
        $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null;
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);

        if ($title === '') {
            $errors[] = 'Event title is required.';
        }

        if (!$errors) {
            if ($id) {
                $stmt = $conn->prepare('UPDATE events SET title = ?, subtitle = ?, sort_order = ? WHERE id = ?');
                $stmt->bind_param('ssii', $title, $subtitle, $sortOrder, $id);
                $stmt->execute();
                $stmt->close();
                $successMessage = 'Event updated.';
                $editEventId = $id;
            } else {
                $stmt = $conn->prepare('INSERT INTO events (title, subtitle, sort_order) VALUES (?, ?, ?)');
                $stmt->bind_param('ssi', $title, $subtitle, $sortOrder);
                $stmt->execute();
                $editEventId = $stmt->insert_id;
                $stmt->close();
                $successMessage = 'Event added. Now upload photos below.';
            }
        }
    }

    if ($action === 'add_images') {
        $eventId = (int) ($_POST['event_id'] ?? 0);
        $nextOrder = 0;
        $ord = $conn->prepare('SELECT COALESCE(MAX(sort_order), 0) AS m FROM event_images WHERE event_id = ?');
        $ord->bind_param('i', $eventId);
        $ord->execute();
        $nextOrder = (int) $ord->get_result()->fetch_assoc()['m'] + 1;
        $ord->close();

        $uploaded = 0;
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['name'] as $i => $name) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_NO_FILE) {
                    continue;
                }
                $singleFile = [
                    'name' => $_FILES['images']['name'][$i],
                    'type' => $_FILES['images']['type'][$i],
                    'tmp_name' => $_FILES['images']['tmp_name'][$i],
                    'error' => $_FILES['images']['error'][$i],
                    'size' => $_FILES['images']['size'][$i],
                ];
                $_FILES['__single'] = $singleFile;
                try {
                    $path = handle_image_upload('__single', UPLOAD_DIR_EVENTS, UPLOAD_URL_EVENTS);
                    if ($path) {
                        $stmt = $conn->prepare('INSERT INTO event_images (event_id, image_path, sort_order) VALUES (?, ?, ?)');
                        $order = $nextOrder++;
                        $stmt->bind_param('isi', $eventId, $path, $order);
                        $stmt->execute();
                        $stmt->close();
                        $uploaded++;
                    }
                } catch (RuntimeException $e) {
                    $errors[] = $e->getMessage();
                }
            }
        }
        if ($uploaded > 0) {
            $successMessage = $uploaded . ' image(s) added.';
        }
        $editEventId = $eventId;
    }
}

// ---- Load event for editing ----
$editEvent = null;
$editEventId = $editEventId ?? ($_GET['edit'] ?? null);
if ($editEventId) {
    $eid = (int) $editEventId;
    $stmt = $conn->prepare('SELECT * FROM events WHERE id = ?');
    $stmt->bind_param('i', $eid);
    $stmt->execute();
    $editEvent = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($editEvent) {
        $imgStmt = $conn->prepare('SELECT * FROM event_images WHERE event_id = ? ORDER BY sort_order ASC, id ASC');
        $imgStmt->bind_param('i', $eid);
        $imgStmt->execute();
        $editEventImages = $imgStmt->get_result();
        $imgStmt->close();
    }
}

$events = $conn->query('SELECT * FROM events ORDER BY sort_order ASC, id DESC');
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
    <title>Manage Events - Admin</title>
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
          <li><a href="admin_announcements.php">Announcements</a></li>
          <li><a href="admin_events.php" class="active">Events</a></li>
          <li><a href="logout.php">Log out</a></li>
        </ul>
      </div>
    </header>

    <main class="admin-crud-content">
      <h1 class="admin-crud-title"><?= $editEvent ? 'Edit Event' : 'Add Event' ?></h1>

      <?php if ($errors): ?>
        <div class="admin-alert admin-alert-error">
          <?php foreach ($errors as $e): ?><p><?= h($e) ?></p><?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ($successMessage): ?>
        <div class="admin-alert admin-alert-success"><p><?= h($successMessage) ?></p></div>
      <?php endif; ?>

      <form class="admin-form" method="POST">
        <input type="hidden" name="action" value="save_event">
        <input type="hidden" name="id" value="<?= h($editEvent['id'] ?? '') ?>">

        <label>Event Title
          <input type="text" name="title" value="<?= h($editEvent['title'] ?? '') ?>" required>
        </label>

        <label>Subtitle / Description
          <textarea name="subtitle" rows="3"><?= h($editEvent['subtitle'] ?? '') ?></textarea>
        </label>

        <label>Display order (lower shows first)
          <input type="number" name="sort_order" value="<?= h($editEvent['sort_order'] ?? 0) ?>">
        </label>

        <div class="admin-form-actions">
          <button type="submit" class="Login"><?= $editEvent ? 'Save Changes' : 'Add Event' ?></button>
          <?php if ($editEvent): ?>
            <a class="admin-cancel-link" href="admin_events.php">Cancel</a>
          <?php endif; ?>
        </div>
      </form>

      <?php if ($editEvent): ?>
        <h2 class="admin-crud-subtitle">Photos for "<?= h($editEvent['title']) ?>"</h2>

        <form class="admin-form" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="action" value="add_images">
          <input type="hidden" name="event_id" value="<?= h($editEvent['id']) ?>">
          <label>Add photos (you can select multiple)
            <input type="file" name="images[]" accept="image/*" multiple>
          </label>
          <div class="admin-form-actions">
            <button type="submit" class="Login">Upload Photos</button>
          </div>
        </form>

        <div class="admin-image-grid">
          <?php while ($img = $editEventImages->fetch_assoc()): ?>
            <div class="admin-image-tile">
              <img src="<?= h($img['image_path']) ?>" alt="">
              <a href="admin_events.php?delete_image=<?= h($img['id']) ?>&event_id=<?= h($editEvent['id']) ?>"
                 class="admin-btn-delete-tile"
                 onclick="return confirm('Delete this photo?');">Delete</a>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>

      <h2 class="admin-crud-subtitle">Existing Events</h2>
      <div class="admin-list">
        <?php while ($row = $events->fetch_assoc()): ?>
          <div class="admin-list-item">
            <div class="admin-list-info">
              <h3><?= h($row['title']) ?></h3>
              <p class="admin-list-order">Order: <?= h($row['sort_order']) ?></p>
            </div>
            <div class="admin-list-actions">
              <a href="admin_events.php?edit=<?= h($row['id']) ?>" class="admin-btn-edit">Edit / Photos</a>
              <a href="admin_events.php?delete_event=<?= h($row['id']) ?>" class="admin-btn-delete" onclick="return confirm('Delete this event and all its photos?');">Delete</a>
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
