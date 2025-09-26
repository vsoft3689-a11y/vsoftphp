<?php
include '../config/database.php';

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
  header("Location: ../login.php");
  exit();
}

$conn = (new Database())->connect();
if ($conn->connect_error) {
  die(json_encode(["status" => "error", "message" => $conn->connect_error]));
}

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

  $_SESSION['project_results'] = $result->fetch_all(MYSQLI_ASSOC);

  header("Location: userprojects.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Project Selection</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link href="../css/projects.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

  <?php include 'user_navbar.php'; ?>

  <main class="m-5">
    <div class="container project-selection">
      <div class="text-center">
        <h2>Project Selection</h2>
      </div>

      <form method="POST" id="projectForm">
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
      <?php
      if (isset($_SESSION['project_results'])) {
        $results = $_SESSION['project_results'];

        echo "<div class='mt-4'>";
        if (count($results) > 0) {
          echo "<table class='table table-bordered table-hover'>
              <thead class='table-dark'>
                <tr>
                  <th>Title</th>
                  <th>Description</th>
                  <th>Technologies</th>
                  <th>Price</th>
                  <th>Demo Video</th>
                  <th>Abstract</th>
                  <th>Base Paper</th>
                  <th>Interested</th>
                </tr>
              </thead>
              <tbody>";

          foreach ($results as $row) {
            $projectId = htmlspecialchars($row['id']);
            $title = htmlspecialchars($row['title']);
            $description = htmlspecialchars($row['description']);
            $technologies = htmlspecialchars($row['technologies']);
            $price = htmlspecialchars($row['price']);
            $youtubeUrl = htmlspecialchars($row['youtube_url']);
            $abstractPath = htmlspecialchars($row['file_path_abstract']);
            $basePaperPath = htmlspecialchars($row['file_path_basepaper']);

            echo "<tr>
                <td>{$title}</td>
                <td>{$description}</td>
                <td>{$technologies}</td>
                <td>₹{$price}</td>
                <td><a href='{$youtubeUrl}' target='_blank'><i class='fab fa-youtube'></i> Watch</a></td>
                <td><a href='{$abstractPath}' class='btn btn-sm btn-primary' download>Download</a></td>
                <td><a href='{$basePaperPath}' class='btn btn-sm btn-primary' download>Download</a></td>
                <td><a href='projecthistory.php?project_id={$projectId}' class='btn btn-sm btn-success bi bi-hand-thumbs-up' title='Interested'></a></td>
              </tr>";
          }

          echo "</tbody></table>";
        } else {
          echo "<p class='text-center mt-3'>No projects found for your selection.</p>";
        }
        echo "</div>";

        unset($_SESSION['project_results']);
      }
      ?>
    </div>

  </main>

  <?php include './footer.php'; ?>

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