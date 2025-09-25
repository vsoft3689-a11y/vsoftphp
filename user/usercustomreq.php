<?php 
// header('Cache-Control: no-cache, no-store, must-revalidate');
// header('Pragma: no-cache');
// header('Expires: 0');
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

$user_id = $_SESSION['user']['id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = $_POST["title"];
  $description = $_POST["description"];
  $technologies = $_POST["technologies"];

  // File upload
  $document_path = null;
  if (isset($_FILES["document"]) && $_FILES["document"]["error"] === 0) {
    $uploadDir = "../uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $document_path = $uploadDir . time() . "_" . basename($_FILES["document"]["name"]);
    move_uploaded_file($_FILES["document"]["tmp_name"], $document_path);
  }

  $status = "pending"; // default

  // Insert record
  $stmt = $conn->prepare("INSERT INTO custom_requirements (user_id, title, description, technologies, status, document_path) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("isssss", $user_id, $title, $description, $technologies, $status, $document_path);
  $stmt->execute();
  $stmt->close();

  header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Submit Custom Project Requirement</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <?php include 'user_navbar.php'; ?>

  <div class="container mt-4 mb-5">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">

        <?php if (isset($_GET["success"])): ?>
          <div class="alert alert-success">Requirement submitted successfully!</div>
          <script>
            if (window.history.replaceState) {
              const url = new URL(window.location);
              url.searchParams.delete("success");
              window.history.replaceState({}, document.title, url);
            }
          </script>
        <?php endif; ?>

        <!-- Form -->
        <div class="card shadow-lg rounded-3 mb-4">
          <div class="card-header bg-primary text-white text-center">
            <h4>Submit Custom Project Requirement</h4>
          </div>
          <div class="card-body">
            <form action="usercustomreq.php" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label class="form-label">Project Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" placeholder="Enter project title" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Describe your project idea"></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Technologies Required</label>
                <input type="text" name="technologies" class="form-control" placeholder="e.g. Python, AI, IoT">
              </div>
              <div class="mb-3">
                <label class="form-label">Upload Reference Document</label>
                <input type="file" name="document" class="form-control">
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-success">Submit Requirement</button>
              </div>
            </form>
          </div>
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
  <script src="../lib/wow/wow.min.js"></script>
  <script src="../lib/easing/easing.min.js"></script>
  <script src="../lib/waypoints/waypoints.min.js"></script>
  <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

  <!-- Template Javascript -->
  <script src="../js/main.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Owl Carousel JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>