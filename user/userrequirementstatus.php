<?php
include '../config/database.php';

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
  header("Location: ../login.php");
  exit();
}

$conn = (new Database())->connect();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (empty($_SESSION['user'])) {
  die("Please login first.");
}
$user_id = (int)$_SESSION['user']['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Submissions</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <?php include 'user_navbar.php'; ?>

  <div class="container mt-4">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h4>My Submissions</h4>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table mb-0">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Technologies</th>
                <th>Status</th>
                <th>Document</th>
                <th>Created</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $stmt = $conn->prepare("SELECT id, title, technologies, status, document_path, created_at 
                                    FROM custom_requirements 
                                    WHERE user_id = ? ORDER BY id DESC");
              $stmt->bind_param("i", $user_id);
              $stmt->execute();
              $result = $stmt->get_result();
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                            <td>{$row['id']}</td>
                            <td>" . htmlspecialchars($row['title']) . "</td>
                            <td>" . htmlspecialchars($row['technologies']) . "</td>
                            <td>" . htmlspecialchars($row['status']) . "</td>
                            <td>";
                  if ($row['document_path']) {
                    echo "<a href='" . htmlspecialchars($row['document_path']) . "' target='_blank'>Download</a>";
                  } else {
                    echo "-";
                  }
                  echo "</td>
                          <td>{$row['created_at']}</td>
                          </tr>";
                }
              } else {
                echo "<tr><td colspan='6' class='text-center text-muted'>No submissions found.</td></tr>";
              }
              $stmt->close();
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer Start -->
  <?php include '../footer.php'; ?>
  <!-- Footer End -->


  <!-- Back to Top -->
  <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


  <!-- JavaScript Libraries -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/waypoints/waypoints.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>

  <!-- Template Javascript -->
  <script src="js/main.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Owl Carousel JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>