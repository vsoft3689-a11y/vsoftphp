<?php
include './config/database.php';

session_start();
 
$conn = (new Database())->connect();

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => $conn->connect_error]));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>VsoftSolutions</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <?php include 'navbar.php'; ?>
    <!-- Navbar End -->

    <!-- Page Content -->
    <main class="page-content">
      <!-- Header Start -->
      <div class="container-fluid bg-primary py-5 mb-5 internship-header">
          <div class="container py-5">
              <div class="row justify-content-center">
                  <div class="col-lg-10 text-center">
                      <h1 class="display-3 text-white animated slideInDown">Internship & Corporate</h1>
                      <nav aria-label="breadcrumb">
                          <ol class="breadcrumb justify-content-center">
                              <li class="breadcrumb-item"><a class="text-white" href="./index.php">Home</a></li>
                              <li class="breadcrumb-item"><a class="text-white" href="./about.php">About</a></li>
                              <li class="breadcrumb-item"><a class="text-white" href="./internship.php">Internship</a></li>
                          </ol>
                      </nav>
                  </div>
              </div>
          </div>
      </div>
      <!-- Header End -->

      <!-- Intern Main Start -->
      <div class="container-xxl py-5">
          <div class="container">
              <div class="row g-2 justify-content-center align-items-stretch">
                  <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                      <div class="service-item text-center pt-3">
                          <div class="p-4">
                              <i class="fa fa-3x fa-laptop text-primary mb-4"></i>
                              <h5 class="mb-3">Internship Programing</h5>
                              <p style="text-align: center;">Web-developement, Python,<br>AI & ML, Mobile-developement,<br>Java & Springboot, etc</p>
                              <a class="btn btn-primary py-3 px-5 mt-2" href="#internship">View</a>
                          </div>
                      </div>
                  </div>
                  <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                      <div class="service-item text-center pt-3">
                          <div class="p-4">
                              <i class="fa fa-3x bi-briefcase text-primary mb-4"></i>
                              <h5 class="mb-3">Corporate Training</h5>
                              <p style="text-align: center;">Soft Skills & Communication,<br>Excel & Data Analytics,<br>Leadership & Team Building.</p>
                              <a class="btn btn-primary py-3 px-5 mt-2" href="#corporate">View</a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!-- Intern Main End -->

      <!-- Internship content Page Start -->
      <h1 class="text-black text-center animated slideInDown" id="internship">Internship Programmings</h1>
      <div class="container-xxl py-5">
          <div class="container">
              <div class="row g-4">
                  <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                      <div class="service-item text-center pt-3">
                          <div class="p-4">
                              <i class="fa fa-3x fa-laptop-code text-primary mb-4"></i>
                              <h5 class="mb-3">Web <br>Development</h5>
                              <a class="btn btn-primary py-3 px-5 mt-2" href="./corporate.php#web_development">Know More</a>
                          </div>
                      </div>
                  </div>
                  <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                      <div class="service-item text-center pt-3">
                          <div class="p-4">
                              <i class="fa fa-3x fa-leaf text-primary mb-4"></i>
                              <h5 class="mb-3">Python <br>with Django</h5>
                              <a class="btn btn-primary py-3 px-5 mt-2" href="./corporate.php#python-django">Know More</a>
                          </div>
                      </div>
                  </div>
                  <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                      <div class="service-item text-center pt-3">
                          <div class="p-4">
                              <i class="fa fa-3x fa-robot text-primary mb-4"></i>
                              <h5 class="mb-3">AI & <br>Machine Learning</h5>
                              <a class="btn btn-primary py-3 px-5 mt-2" href="./corporate.php#ai-machine-learning">Know More</a>
                          </div>
                      </div>
                  </div>
                  <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                      <div class="service-item text-center pt-3">
                          <div class="p-4">
                              <i class="fa fa-3x bi-phone text-primary mb-4"></i>
                              <h5 class="mb-3">Mobile App Development</h5>
                              <a class="btn btn-primary py-3 px-5 mt-2" href="./corporate.php#mobile-app-development">Know More</a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!-- Internship content End -->

      <!-- Corporate content Page -->
      <h1 class="text-black text-center animated slideInDown" id="corporate" style="margin-top:50px;">Corporate Training</h1>
      <div class="container-xxl py-5">
          <div class="container">
              <div class="row g-4">
                  <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                      <div class="service-item text-center pt-3">
                          <div class="p-4">
                              <i class="fa fa-3x fa-graduation-cap text-primary mb-4"></i>
                              <h5 class="mb-3">Soft Skills & Communication</h5>
                              <a class="btn btn-primary py-3 px-5 mt-2" href="./corporate.php#soft_skils">Know More</a>
                          </div>
                      </div>
                  </div>
                  <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                      <div class="service-item text-center pt-3">
                          <div class="p-4">
                              <i class="fa fa-3x fa-table text-primary mb-4"></i>
                              <h5 class="mb-3">Excel & Data <br>Analytics</h5>
                              <a class="btn btn-primary py-3 px-5 mt-2" href="./corporate.php#excel-data-analytics">Know More</a>
                          </div>
                      </div>
                  </div>
                  <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                      <div class="service-item text-center pt-3">
                          <div class="p-4">
                              <i class="fa fa-3x fa-flag text-primary mb-4"></i>
                              <h5 class="mb-3">Leadership & <br>Team Building</h5>
                              <a class="btn btn-primary py-3 px-5 mt-2" href="./corporate.php#leadership-team-building">Know More</a>
                          </div>
                      </div>
                  </div>
                  <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                      <div class="service-item text-center pt-3">
                          <div class="p-4">
                              <i class="fa fa-3x fa-handshake text-primary mb-4"></i>
                              <h5 class="mb-3">Business <br>Etiquette</h5>
                              <a class="btn btn-primary py-3 px-5 mt-2" href="./corporate.php#business-etiquette">Know More</a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </main>

    <!-- Footer Start -->    
    <?php include 'footer.php'; ?>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</body>
</html>
