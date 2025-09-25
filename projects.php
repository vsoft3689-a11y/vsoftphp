<?php
include './config/database.php';

session_start();

$conn = (new Database())->connect();
if ($conn->connect_error) {
  die(json_encode(["status" => "error", "message" => $conn->connect_error]));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $degree = $_POST['degree'];
  $branch = $_POST['branch'];
  $domain = $_POST['domain'];
  $projectType = $_POST['project_type'] ?? '';

  $sql = "SELECT * FROM projects WHERE degree = ? AND branch = ? AND domain = ?";
  $params = [$degree, $branch, $domain];

  if (!empty($projectType)) {
    $sql .= " AND type = ?";
    $params[] = strtolower($projectType);
  }

  $stmt = $conn->prepare($sql);
  $stmt->bind_param(str_repeat("s", count($params)), ...$params);
  $stmt->execute();
  $result = $stmt->get_result();

  // Store results in session
  $_SESSION['project_results'] = $result->fetch_all(MYSQLI_ASSOC);

  // Redirect to clear POST data (PRG pattern)
  header("Location: projects.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>vsofts solutions</title>
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      margin-top: 20px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
      padding: 12px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }

    th {
      background: #06BBCC;
      color: #fff;
    }

    tr:hover {
      background: #f1f1f1;
    }
  </style>

  <!-- Favicon -->
  <link href="img/favicon.ico" rel="icon">

  <!-- Google Web Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Icon Font Stylesheet -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/projects.css" rel="stylesheet">
</head>

<body>

  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <!-- Header -->
  <div class="container-fluid bg-primary py-5 mb-5 projects-header">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-10 text-center">
          <h1 class="display-3 text-white">OUR PROJECTS</h1>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
              <li class="breadcrumb-item"><a class="text-white" href="./index.php">Home</a></li>
              <li class="breadcrumb-item"><a class="text-white" href="./about.php">About</a></li>
              <li class="breadcrumb-item"><a class="text-white" href="./projects.php">Projects</a></li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Project Selection -->
  <div class="container project-selection">
    <div class="text-center">
      <h2>Project Selection</h2>
    </div>

    <form method="POST">
      <select name="degree" id="degree" required onchange="updateBranches()">
        <option value="">Select Degree</option>
        <option value="B.Tech">B.Tech</option>
        <option value="M.Tech">M.Tech</option>
        <option value="MCA">MCA</option>
        <option value="MBA">MBA</option>
      </select>

      <select name="branch" id="branch" required>
        <option value="">Select Branch</option>
      </select>

      <!-- Project Type for all degrees -->
      <div id="projectTypeDiv">
        <select name="project_type" id="project_type" required>
          <option value="">Select Project Type</option>
          <option value="mini">Mini Project</option>
          <option value="major">Major Project</option>
        </select>
      </div>

      <select name="domain" id="domain" required>
        <option value="">Select Domain</option>
      </select>

      <button type="submit">Submit</button>
    </form>

    <!-- Results Section -->
    <?php
    if (isset($_SESSION['project_results'])) {
      $results = $_SESSION['project_results'];

      echo "<div class='mt-4'>";
      if (count($results) > 0) {
        echo "<table>
            <thead>
              <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Technologies</th>
                <th>Price</th>
                <th>Demo Video</th>
                <th>Abstract</th>
                <th>Base Paper</th>
              </tr>
            </thead>
            <tbody>";

        foreach ($results as $row) {
          echo "<tr>
                <td>{$row['title']}</td>
                <td>{$row['description']}</td>
                <td>{$row['technologies']}</td>
                <td>₹{$row['price']}</td>
                <td>
                  <a href='{$row['youtube_url']}' target='_blank' class='youtube-link'>
                    <i class='fab fa-youtube'></i> Watch
                  </a>
                </td>
                <td>
                  <a href='./vsoftstaticphp/{$row['file_path_abstract']}' target='_blank' fa fa-download class='btn btn-sm btn-primary'>
                    Download
                  </a>
                </td>
                <td>
                  <a href='./vsoftstaticphp/{$row['file_path_basepaper']}' target='_blank' fa fa-download class='btn btn-sm btn-primary'>
                   Download
                  </a>
                </td>
              </tr>";
        }
        echo "</tbody></table>";
      } else {
        echo "<p class='text-center mt-3'>No projects found for your selection.</p>";
      }
      echo "</div>";

      // Clear results after showing once
      unset($_SESSION['project_results']);
    }
    ?>
  </div>

  <!-- Footer -->
  <?php include 'footer.php'; ?>

  <!-- Back to Top -->
  <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

  <!-- JS Libraries -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/waypoints/waypoints.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>

  <!-- Custom JS -->
  <script src="js/main.js"></script>

  <script>
    function updateBranches() {
      let degree = document.getElementById("degree").value;
      let branch = document.getElementById("branch");
      let domain = document.getElementById("domain");

      branch.innerHTML = "<option value=''>Select Branch</option>";
      domain.innerHTML = "<option value=''>Select Domain</option>";

      if (degree === "B.Tech") {
        ["CSE", "ECE", "EEE", "Civil", "Mech"].forEach(b => branch.innerHTML += `<option value="${b}">${b}</option>`);
      } else if (degree === "M.Tech") {
        ["CSE", "ECE", "Power Systems", "Structural Engineering"].forEach(b => branch.innerHTML += `<option value="${b}">${b}</option>`);
      } else if (degree === "MCA") {
        ["Software Engineering", "Networking", "Hardware Technologies", "Management Information Systems"].forEach(b => branch.innerHTML += `<option value="${b}">${b}</option>`);
      } else if (degree === "MBA") {
        ["Marketing", "Finance", "Hospitality & Tourism", "Banking & Insurance"].forEach(b => branch.innerHTML += `<option value="${b}">${b}</option>`);
      }
    }

    document.getElementById("branch").addEventListener("change", function() {
      let branch = this.value;
      let degree = document.getElementById("degree").value;
      let domain = document.getElementById("domain");

      domain.innerHTML = "<option value=''>Select Domain</option>";

      if (degree === "B.Tech") {
        if (branch === "CSE") {
          ["Web Development", "AI/ML", "Cloud Computing", "App Development", "Cyber Security"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        } else if (branch === "ECE") {
          ["VLSI", "Embedded Systems", "IoT", "Robotics"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        } else if (branch === "EEE") {
          ["Power Electronics", "Renewable Energy", "Smart Grids"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        } else if (branch === "Civil") {
          ["Structural Analysis", "Construction Management", "Geotechnical"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        } else if (branch === "Mech") {
          ["Thermal Engineering", "Automobile", "Manufacturing", "Mechatronics"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        }
      } else if (degree === "M.Tech") {
        if (branch === "CSE") {
          ["Data Mining", "Blockchain", "Network Security"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        } else if (branch === "ECE") {
          ["Wireless Communication", "Signal Processing", "VLSI Design"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        } else if (branch === "Power Systems") {
          ["FACTS", "Smart Energy System", "Load Flow Studies"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        } else if (branch === "Structural Engineering") {
          ["Finite Element", "Concrete Technology", "Seismic Design"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        }
      } else if (degree === "MCA") {
        if (branch === "Software Engineering") {
          ["Database Management Systems", "Software Design & Architecture", "Software Project Management"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        } else if (branch === "Networking") {
          ["Computer Networking", "Network Security", "Cloud Networking", "Data Communication"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        } else if (branch === "Hardware Technologies") {
          ["Embedded Systems", "VLSI Design", "IoT Hardware & Sensors"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        } else if (branch === "Management Information Systems") {
          ["Enterprise Systems", "E-Business & E-Commerce Systems", "Information Security"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        }
      } else if (degree === "MBA") {
        if (branch === "Marketing") {
          ["Brand Management", "Digital Marketing", "International Marketing", "Sales & Distribution Management"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        } else if (branch === "Finance") {
          ["Corporate Finance", "Investment Banking", "Risk Management"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        } else if (branch === "Hospitality & Tourism") {
          ["Hotel Management & Operations", "Housekeeping & Facility Management", "Travel & Transport Management", "Sustainable Eco-Tourism"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        } else if (branch === "Banking & Insurance") {
          ["Corporate Banking", "Investment Banking", "Retail Banking", "Insurance Management"].forEach(d => domain.innerHTML += `<option value="${d}">${d}</option>`);
        }
      }
    });
  </script>
</body>

</html>