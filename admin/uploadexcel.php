<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

include "../config/database.php";
$conn = (new Database())->connect();

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Include PHPExcel
require '../lib/PHPExcel-1.8.2/Classes/PHPExcel.php';
require '../lib/PHPExcel-1.8.2/Classes/PHPExcel/IOFactory.php';

$inserted = 0;
$updated = 0;
$skipped = 0;
$uploadSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName = basename($_FILES['excel_file']['name']);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $filePath)) {
      try {
        $objPHPExcel = PHPExcel_IOFactory::load($filePath);
        $sheet = $objPHPExcel->getActiveSheet();
        $rows = $sheet->toArray();

        foreach ($rows as $index => $row) {
          if ($index == 0) continue; // Skip header

          $degree = $conn->real_escape_string($row[0] ?? '');
          $branch = $conn->real_escape_string($row[1] ?? '');
          $type = $conn->real_escape_string($row[2] ?? '');
          $domain = $conn->real_escape_string($row[3] ?? '');
          $title = $conn->real_escape_string($row[4] ?? '');
          $description = $conn->real_escape_string($row[5] ?? '');
          $technologies = $conn->real_escape_string($row[6] ?? '');
          $price = intval($row[7] ?? 0);
          $youtube_url = $conn->real_escape_string($row[8] ?? '');
          $file_path_abstract = $conn->real_escape_string($row[9] ?? '');
          $file_path_basepaper = $conn->real_escape_string($row[10] ?? '');

          // If title is empty, skip this row
          if (empty($title)) {
            $skipped++;
            continue;
          }

          // Check if project with this title already exists
          $checkSql = "SELECT id FROM projects WHERE title = '$title'";
          $result = $conn->query($checkSql);

          if ($result && $result->num_rows > 0) {
            // UPDATE existing project
            $updateSql = "UPDATE projects SET 
              degree='$degree',
              branch='$branch',
              type='$type',
              domain='$domain',
              description='$description',
              technologies='$technologies',
              price=$price,
              youtube_url='$youtube_url',
              file_path_abstract='$file_path_abstract',
              file_path_basepaper='$file_path_basepaper'
              WHERE title='$title'";
            if ($conn->query($updateSql)) {
              $updated++;
            } else {
              $skipped++;
            }
          } else {
            // INSERT new project
            $insertSql = "INSERT INTO projects 
              (degree, branch, type, domain, title, description, technologies, price, youtube_url, file_path_abstract, file_path_basepaper) 
              VALUES 
              ('$degree', '$branch', '$type', '$domain', '$title', '$description', '$technologies', $price, '$youtube_url', '$file_path_abstract', '$file_path_basepaper')";
            if ($conn->query($insertSql)) {
              $inserted++;
            } else {
              $skipped++;
            }
          }
        }

        $uploadSuccess = true;
      } catch (Exception $e) {
        echo "<script>alert('Error reading file: " . addslashes($e->getMessage()) . "');</script>";
      }
    } else {
      echo "<script>alert('Error uploading file!');</script>";
    }
  } else {
    echo "<script>alert('Please select a valid Excel file.');</script>";
  }
}

$conn->close();

if ($uploadSuccess) {
  echo "<script>
    alert('Upload Completed:\\nInserted: $inserted\\nUpdated: $updated\\nSkipped: $skipped');
    window.location.href = 'uploadexcel.php';
  </script>";
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Upload Excel</title>
  <style>
    .main {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    #uploadHeading {
      margin: 30px 0;
      color: #333;
    }

    #uploadForm {
      margin-bottom: 30px;
      padding: 30px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }

    input,
    textarea {
      margin: 8px 0;
      padding: 8px;
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    #btn {
      width: auto;
      background: #06BBCC;
      color: #fff;
      padding: 10px;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
      border: none;
      transition: 0.3s;
    }

    #btn:hover {
      background: #0056b3;
    }

    #btn[type="submit"] {
      margin-top: 5px;
    }
  </style>
</head>

<body>
  <?php include "./adminnavbar.php" ?>

  <div class="main">
    <h2 id="uploadHeading">Bulk Upload Projects</h2>
    <form id="uploadForm" method="post" enctype="multipart/form-data">
      <input type="file" name="excel_file" accept=".xls,.xlsx" required>
      <button id="btn" type="submit">Upload</button>
    </form>
  </div>

  <?php include "./footer.php" ?>
</body>

</html>