<?php
include __DIR__ . '/auth.php';
include '../config/database.php';
// session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../login.php");
  exit();
}
$conn = (new Database())->connect();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Initialize messages
$success_msg = '';
$error_msg = '';

$user_id = $_SESSION['user']['id'];

// Fetch current user details
$stmt = $conn->prepare("SELECT name, email, phone, college, branch, year FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST['name'] ?? '';
  $email = $_POST['email'] ?? '';
  $phone = $_POST['phone'] ?? '';
  $college = $_POST['college'] ?? '';
  $branch = $_POST['branch'] ?? '';
  $year = $_POST['year'] ?? '';

  $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, college = ?, branch = ?, year = ? WHERE id = ?");
  $stmt->bind_param("ssssssi", $name, $email, $phone, $college, $branch, $year, $user_id);

  if ($stmt->execute()) {
    $success_msg = "Profile updated successfully!";
    // Refresh user data
    $user = [
      'name' => $name,
      'email' => $email,
      'phone' => $phone,
      'college' => $college,
      'branch' => $branch,
      'year' => $year
    ];
  } else {
    $error_msg = "Error updating profile. Please try again.";
  }
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile | VSoft</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --primary: #06bbcc;
    }

    .profile-pic {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid var(--primary);
    }

    .card-header {
      background-color: var(--primary) !important;
    }

    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 10px rgba(6, 187, 204, 0.25);
    }

    .form-label {
      color: var(--primary);
      font-weight: 500;
    }

    .btn-success {
      background-color: var(--primary);
      border-color: var(--primary);
    }

    .btn-success:hover {
      background-color: #05a4b3;
      border-color: #05a4b3;
    }
  </style>
</head>

<body class="bg-light">
  <!-- Navbar Start -->
  <?php include 'user_navbar.php'; ?>
  <!-- Navbar End -->

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">

        <div class="card shadow-lg rounded-3">
          <div class="card-header text-white text-center">
            <h4>My Profile</h4>
          </div>
          <div class="card-body">

            <?php if (!empty($success_msg)): ?>
              <div class="alert alert-success"><?= htmlspecialchars($success_msg) ?></div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error_msg) ?></div>
            <?php endif; ?>

            <!-- Profile Form -->
            <form id="profileForm" method="POST" novalidate>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Full Name</label>
                  <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required readonly>
                  <div class="invalid-feedback">Full name is required.</div>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required readonly>
                  <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Phone</label>
                  <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required pattern="^[0-9]{10}$" maxlength="10">
                  <div class="invalid-feedback">Enter a valid 10-digit phone number.</div>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">College</label>
                  <input type="text" name="college" class="form-control" value="<?= htmlspecialchars($user['college'] ?? '') ?>" required>
                  <div class="invalid-feedback">College name is required.</div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Branch</label>
                  <input type="text" class="form-control" value="<?= htmlspecialchars($user['branch'] ?? '') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Year of Study</label>
                  <input type="text" class="form-control" value="<?= htmlspecialchars($user['year'] ?? '') ?>" required>
                </div>
              </div>

              <!-- Save Button -->
              <div class="d-grid">
                <button type="submit" class="btn btn-success">Save Changes</button>
              </div>
            </form>

          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Footer Start -->
<?php include '../admin/footer.php'; ?>
  <!-- Footer End -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Bootstrap form validation
    const form = document.getElementById("profileForm");
    form.addEventListener("submit", function(event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add("was-validated");
    });
  </script>

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