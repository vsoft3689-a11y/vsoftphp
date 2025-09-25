<?php
include '../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  $_SESSION['user_id'] = 1; // Temporary for testing; replace with real login session later
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
<main class="mt-100">



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

    <select name="project_type" id="project_type" required>
      <option value="">Select Project Type</option>
      <option value="mini">Mini Project</option>
      <option value="major">Major Project</option>
    </select>

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

<!-- <div class="container project-selection">
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

    <select name="project_type" id="project_type" required>
      <option value="">Select Project Type</option>
      <option value="mini">Mini Project</option>
      <option value="major">Major Project</option>
    </select>

    <select name="domain" id="domain" required>
      <option value="">Select Domain</option>
    </select>

    <button type="submit">Submit</button>
  </form> -->


<?php include '../admin/footer.php'; ?>

<script>
const branchesByDegree = {
  "B.Tech": ["CSE", "ECE", "ME", "CE", "EE", "IT"],
  "M.Tech": ["CSE", "ECE", "ME", "CE", "EE"],
  "MCA": ["Computer Applications"],
  "MBA": ["Marketing", "Finance", "HR", "Operations"]
};

const domainsByDegree = {
  "B.Tech": ["AI", "Data Science", "Web Development", "IoT", "Embedded Systems"],
  "M.Tech": ["AI", "Robotics", "Machine Learning", "Cybersecurity"],
  "MCA": ["Web Development", "Mobile Apps", "Database Management"],
  "MBA": ["Business Analytics", "Finance", "Marketing", "Operations Management"]
};

function updateBranches() {
  const degree = document.getElementById("degree").value;
  const branchSelect = document.getElementById("branch");
  const domainSelect = document.getElementById("domain");

  branchSelect.innerHTML = "<option value=''>Select Branch</option>";
  domainSelect.innerHTML = "<option value=''>Select Domain</option>";

  if (branchesByDegree[degree]) {
    branchesByDegree[degree].forEach(branch => {
      const opt = document.createElement("option");
      opt.value = branch;
      opt.textContent = branch;
      branchSelect.appendChild(opt);
    });
  }

  if (domainsByDegree[degree]) {
    domainsByDegree[degree].forEach(domain => {
      const opt = document.createElement("option");
      opt.value = domain;
      opt.textContent = domain;
      domainSelect.appendChild(opt);
    });
  }
}
</script>
</body>
</html>