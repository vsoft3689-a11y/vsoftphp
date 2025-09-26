<?php
include '../config/database.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
  $_SESSION['user_id'] = 1;
}

$conn = (new Database())->connect();
$userId = $_SESSION['user_id'];

// Add Interested Project
if (isset($_GET['project_id'])) {
  $projectId = intval($_GET['project_id']);
  $check = $conn->prepare("SELECT * FROM project_history WHERE user_id = ? AND project_id = ?");
  $check->bind_param("ii", $userId, $projectId);
  $check->execute();
  $exists = $check->get_result();

  if ($exists->num_rows == 0) {
    $insert = $conn->prepare("INSERT INTO project_history (user_id, project_id) VALUES (?, ?)");
    $insert->bind_param("ii", $userId, $projectId);
    $insert->execute();
  }

  header("Location: userprojects.php");
  exit;
}

// Remove Project
if (isset($_GET['remove_id'])) {
  $removeId = intval($_GET['remove_id']);
  $delete = $conn->prepare("DELETE FROM project_history WHERE user_id = ? AND project_id = ?");
  $delete->bind_param("ii", $userId, $removeId);
  $delete->execute();
  header("Location: projecthistory.php");
  exit;
}

// Fetch Interested Projects
$query = "SELECT p.* FROM projects p JOIN project_history ph ON p.id = ph.project_id WHERE ph.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$projects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Interested Projects</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

  <?php include 'user_navbar.php'; ?>

  <div class="container mt-5">
    <div class="container project-selection">
      <h2 class="text-center mb-4 ">Interested Projects</h2>

      <?php if (count($projects) > 0): ?>
        <table class="table table-bordered table-striped">
          <thead class="table-primary">
            <tr>
              <th>Title</th>
              <th>Description</th>
              <th>Technologies</th>
              <th>Price</th>
              <th>Remove</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($projects as $p): ?>
              <tr>
                <td><?= htmlspecialchars($p['title']) ?></td>
                <td><?= htmlspecialchars($p['description']) ?></td>
                <td><?= htmlspecialchars($p['technologies']) ?></td>
                <td>₹<?= htmlspecialchars($p['price']) ?></td>
                <td><a href="projecthistory.php?remove_id=<?= $p['id'] ?>" class="btn btn-danger btn-sm bi bi-trash" title="Remove"></a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="text-center">You have not marked any project as interested.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Footer Start -->
  <?php include './footer.php'; ?>
</body>

</html>