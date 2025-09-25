<?php
require_once __DIR__ . "/config/database.php";

// Connect to DB
$conn = (new Database())->connect();

session_start();

// Fetch banners
$bannerResult = $conn->query("SELECT * FROM banners WHERE is_active = 1 ORDER BY display_order ASC");

$result = $conn->query("SELECT * FROM pricing_packages ORDER BY created_at DESC");
$packages = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];


$result = $conn->query("SELECT * FROM testimonals WHERE is_approved = 1 ORDER BY display_order ASC");
$testimonials = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];


$packages = [];
$result = $conn->query("SELECT * FROM pricing_packages ORDER BY created_at DESC");

while ($row = $result->fetch_assoc()) {
  $package_id = $row['id'];

  // Fetch bulk offers for this package
  $offers_sql = "SELECT quantity, price FROM bulk_offers WHERE package_id = $package_id";
  $offers_result = $conn->query($offers_sql);

  $bulk_offers = [];
  if ($offers_result) {
    while ($offer = $offers_result->fetch_assoc()) {
      $bulk_offers[] = $offer;
    }
  }

  // Make sure bulk_offers key always exists
  $row['bulk_offers'] = $bulk_offers;

  $packages[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>VsoftSolutions</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicon -->
  <link href="img/favicon.ico" rel="icon">

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Icon Font Stylesheet -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />


  <!-- Libraries Stylesheet -->
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

  <!-- Customized Bootstrap Stylesheet -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- Template Stylesheet -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/zoom.css" rel="stylesheet">
  <link href="css/offer.css" rel="stylesheet">

  <!-- Owl Carousel CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

  <style>
    .testimonial-item {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .testimonial-text {
      max-width: 280px;
      /* smaller review box */
      margin: 0 auto;
      font-size: 14px;
      line-height: 1.5;
      background: #f8f9fa;
      border: 1px solid #eee;
      padding: 15px;
      border-radius: 8px;
    }

    .owl-carousel .owl-nav button.owl-prev,
    .owl-carousel .owl-nav button.owl-next {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0, 0, 0, 0.5);
      color: #fff;
      border: none;
      padding: 10px 15px;
      border-radius: 50%;
    }

    .owl-carousel .owl-nav button.owl-prev {
      left: 15px;
    }

    .owl-carousel .owl-nav button.owl-next {
      right: 15px;
    }

    .owl-carousel .owl-dots {
      text-align: center;
      margin-top: 15px;
    }

    .owl-carousel .owl-dots .owl-dot span {
      background: #007bff;
    }
  </style>

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



  <!-- Carousel Start -->
  <!-- ================= Banner Carousel ================= -->
  <div class="container-fluid p-0 mb-5">
    <div class="owl-carousel header-carousel position-relative">

      <?php if ($bannerResult && $bannerResult->num_rows > 0): ?>
        <?php while ($row = $bannerResult->fetch_assoc()): ?>
          <?php if (!empty($row['image_path'])): ?>
            <div class="owl-carousel-item position-relative">

              <!-- Banner Image -->
              <img class="img-fluid w-100"
                src="<?php echo htmlspecialchars($row['image_path']); ?>"
                alt="Banner"
                style="object-fit: cover; height: 600px;">

              <!-- Dark Overlay -->
              <div class="position-absolute top-0 start-0 w-100 h-100"
                style="background: rgba(24, 29, 56, .6);">
                <div class="d-flex align-items-center h-100">
                  <div class="container">
                    <div class="row justify-content-start">
                      <div class="col-sm-10 col-lg-8 text-start">

                        <!-- Tagline -->
                        <?php if (!empty($row['tagline'])): ?>
                          <h5 class="text-primary text-uppercase mb-3 animated slideInDown"
                            style="font-size: 22px; letter-spacing: 2px;">
                            <?php echo htmlspecialchars($row['tagline']); ?>
                          </h5>
                        <?php endif; ?>

                        <!-- Sub Text -->
                        <?php if (!empty($row['sub_text'])): ?>
                          <h1 class="text-white fw-bold animated slideInDown"
                            style="font-size: 40px; line-height: 1.2;">
                            <?php echo htmlspecialchars($row['sub_text']); ?>
                          </h1>
                        <?php endif; ?>

                        <!-- CTA Button -->
                        <?php if (!empty($row['cta_button_text']) && !empty($row['cta_button_link'])): ?>
                          <a href="<?php echo htmlspecialchars($row['cta_button_link']); ?>"
                            class="btn btn-primary py-md-3 px-md-5 me-3 mt-3 animated slideInLeft">
                            <?php echo htmlspecialchars($row['cta_button_text']); ?>
                          </a>
                        <?php endif; ?>

                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          <?php endif; ?>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center text-white bg-dark py-5">No banners found!</p>
      <?php endif; ?>

    </div>
  </div>



  <!-- Carousel End -->



  <!-- Owl Carousel CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" rel="stylesheet">

  <!-- jQuery + Owl Carousel JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>


  <!-- Service End -->


  <div class="container-xxl py-5">
    <div class="container">

      <!-- First Row -->
      <div class="row g-4">
        <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
          <div class="service-item text-center pt-3 h-100">
            <div class="p-4 h-100 d-flex flex-column">
              <i class="fa fa-3x fa-graduation-cap text-primary mb-4"></i>
              <h5 class="mb-3">B.TECH Projects</h5>
              <p>B.Tech projects showcase practical applications of engineering concepts in fields like software, civil, mechanical, and electronics engineering.</p>
              <a href="./projects.php" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Know More</a>



            </div>
          </div>
        </div>

        <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
          <div class="service-item text-center pt-3 h-100">
            <div class="p-4 h-100 d-flex flex-column">
              <i class="fa-solid fa-microchip fa-3x text-primary mb-4"></i>
              <h5 class="mb-3">M.TECH Projects</h5>
              <p>M.Tech projects provide students with in-depth research or industry exposure in specialized engineering fields such as computer science, electronics and etc.</p>
              <!-- <a href="./internship.php" class="btn btn-primary mt-auto">Click Here</a> -->
              <a href="./projects.php" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Know More</a>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
          <div class="service-item text-center pt-3 h-100">
            <div class="p-4 h-100 d-flex flex-column">
              <i class="fa-solid fa-briefcase fa-3x text-info mb-4"></i>
              <h5 class="mb-3">MBA Projects</h5>
              <p>MBA projects focus on providing students with real-world business experience in areas such as management, finance, marketing and operations.</p>
              <!-- <a href="./internship.php" class="btn btn-primary mt-auto">Click Here</a> -->
              <a href="./projects.php" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Know More</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Second Row -->
      <div class="row g-4 mt-3">
        <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
          <div class="service-item text-center pt-3 h-100">
            <div class="p-4 h-100 d-flex flex-column">
              <i class="fa-solid fa-laptop-code fa-3x text-info mb-4"></i>
              <h5 class="mb-3">MCA Projects</h5>
              <p>MCA projects provide practical exposure to software development, IT services, and application design, helping students build technical expertise and industry-ready.</p>
              <!-- <a href="./internship.php" class="btn btn-primary mt-auto">Click Here</a> -->
              <a href="./projects.php" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Know More</a>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="0.9s">
          <div class="service-item text-center pt-3 h-100">
            <div class="p-4 h-100 d-flex flex-column">
              <i class="fa-solid fa-chalkboard-teacher fa-3x text-info mb-4"></i>
              <h5 class="mb-3">Internship</h5>
              <p>Internships provide students with hands-on industry exposure, allowing them to apply academic knowledge in practical situations and gain professional experience.</p>
              <!-- <a href="./internship.php" class="btn btn-primary mt-auto">Click Here</a> -->
              <a href="./internship.php" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Know More</a>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="1.1s">
          <div class="service-item text-center pt-3 h-100">
            <div class="p-4 h-100 d-flex flex-column">
              <i class="fa-solid fa-users fa-3x text-info mb-4"></i>
              <!-- <i class="fa-solid fa-users fa-3x text-warning mb-4"></i> -->
              <h5 class="mb-3">Corporate</h5>
              <p>Corporate training equips employees and graduates with the latest industry skills, bridging the gap between education and real-world professional demands.</p>
              <!-- <a href="./internship.php" class="btn btn-primary mt-auto">Click Here</a> -->
              <a href="./internship.php" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Know More</a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>





  <!-- Service End -->

  <!-- About Start -->
  <div class="container-xxl py-5">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 400px;">
          <div class="position-relative h-100">
            <img class="img-fluid position-absolute w-100 h-100" src="./img/index_about.jpg" alt="" style="object-fit: cover;">
          </div>
        </div>
        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
          <h6 class="section-title bg-white text-start text-primary pe-3">About Us</h6>
          <h1 class="mb-4">Welcome to VsoftSolutions</h1>
          <p class="mb-4">VSoft Solutions offers branch-wise B.Tech projects along with M.Tech, MBA, and MCA internships, providing students with practical industry exposure.</p>
          <p class="mb-4">We deliver innovative and tailored solutions on a beautiful, user-friendly platform designed to support academic and professional growth.</p>
          <p class="mb-4">Our goal is to empower students with real-world skills and project experience for a successful career. </p>
          </p>
          <div class="row gy-2 gx-4 mb-4">
            <div class="col-sm-6">
              <a href="./projects.php">
                <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>BTECH PROJECTS</p>
              </a>
            </div>
            <div class="col-sm-6">
              <a href="./projects.php">
                <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>MTECH PROJECTS</p>
              </a>
            </div>
            <div class="col-sm-6">
              <a href="./projects.php">
                <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>MBA PROJECTS</p>
              </a>
            </div>
            <div class="col-sm-6">
              <a href="./projects.php">
                <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>MCA PROJECTS</p>
              </a>
            </div>
            <div class="col-sm-6">
              <a href="./internship.php">
                <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>INTERNSHIP & CORPORATE</p>
              </a>
            </div>
            <!-- <div class="col-sm-6">
                            <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>International Certificate</p>
                        </div> -->
          </div>
          <a class="btn btn-primary py-3 px-5 mt-2" href="./about.php">Read More</a>
        </div>
      </div>
    </div>
  </div>
  <!-- About End -->


  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="section-title bg-white text-center text-primary px-3">Categories</h6>
        <h1 class="mb-5">Courses Categories</h1>
      </div>

      <!-- First row (2 images) -->
      <div class="col-md-4 wow zoomIn" data-wow-delay="0.7s">
        <a href="./projects.php" class="zoom-img">
          <img src="./img/ai1.jpg" class="img-fluid rounded" alt="Artificial Intelligence">
        </a>
        <h5 class="mt-3">Artificial Intelligence</h5>
      </div>

      <div class="col-md-4 wow zoomIn" data-wow-delay="0.7s">
        <a href="./projects.php" class="zoom-img">
          <img src="img/cyber.jpg" class="img-fluid rounded" alt="Cyber Security">
        </a>
        <h5 class="mt-3">Cyber Security</h5>
      </div>
    </div>

    <!-- Second row (2 images) -->
    <div class="row justify-content-center mt-4">
      <div class="col-md-4 wow zoomIn" data-wow-delay="0.7s">
        <a href="./projects.php" class="zoom-img">
          <img src="img/structural.jpg" class="img-fluid rounded" alt="Structural Engineering">
        </a>
        <h5 class="mt-3">Structural Engineering</h5>
      </div>

      <div class="col-md-4 wow zoomIn" data-wow-delay="0.7s">
        <a href="./projects.php" class="zoom-img">
          <img src="img/marketing3.jpg" class="img-fluid rounded" alt="Digital Marketing">
        </a>
        <h5 class="mt-3">Marketing</h5>
      </div>
    </div>
  </div>





  <!-- Categories Start -->


  <div class="container-xxl py-5">
    <div class="container">
      <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="section-title bg-white text-center text-primary px-3">Courses</h6>
        <h1 class="mb-5">Project Prices</h1>
      </div>

      <div class="row g-4">
        <?php foreach ($packages as $package): ?>
          <div class="col-lg-4 col-md-6 wow fadeInUp">
            <div class="course-item bg-light">
              <div class="position-relative overflow-hidden">
                <?php if ($package['service_type'] === 'project'): ?>
                  <img class="img-fluid" src="img/mini1.jpg" alt="">
                <?php elseif ($package['service_type'] === 'internship'): ?>
                  <img class="img-fluid" src="img/internship.jpg" alt="">
                <?php else: ?>
                  <img class="img-fluid" src="img/major1.jpg" alt="">
                <?php endif; ?>
              </div>


              <h5 class="text-center mt-3 mb-2">
                <?php echo ucfirst($package['package_name']); ?>
                (<?php echo ucfirst($package['service_type']); ?>)
              </h5>


              <div class="price-box text-center mt-3">
                <span class="old-price">₹<?php echo number_format($package['original_price'], 2); ?></span>

                <?php if (!empty($package['discounted_price']) && $package['discounted_price'] < $package['original_price']): ?>
                  <?php
                  $discountPercent = round((($package['original_price'] - $package['discounted_price']) / $package['original_price']) * 100);
                  ?>
                  <span class="discount"><?php echo $discountPercent; ?>% OFF</span>
                  <br>
                  <span class="new-price">₹<?php echo number_format($package['discounted_price'], 2); ?></span>
                <?php else: ?>
                  <span class="new-price">₹<?php echo number_format($package['original_price'], 2); ?></span>
                <?php endif; ?>

                <details class="bulk-offer mt-2">
                  <summary>View Offers 🎉</summary>
                  <div class="offers">
                    <h6>🎉 Bulk Offers</h6>
                    <ul style="list-style:none; padding:0; margin:0;" class="offer-list">
                      <?php foreach ($package['bulk_offers'] as $offer): ?>
                        <li>
                          <a href="<?php echo $package['button_link']; ?>" class="zoom-link" style="color:black;">
                            ✅ Buy <b><?php echo $offer['quantity']; ?></b> → ₹<?php echo number_format($offer['price'], 0); ?> per project
                          </a>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </details>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

      </div>
    </div>
  </div>
  <!-- Courses End -->



  <!-- Testimonial Start -->

  <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
      <div class="text-center mb-5">
        <h6 class="section-title bg-white text-center text-primary px-3">Testimonials</h6>
        <h1>What Our Students Say</h1>
      </div>

      <div class="owl-carousel testimonial-carousel position-relative">

        <?php if (!empty($testimonials)): ?>
          <?php foreach ($testimonials as $row): ?>
            <div class="testimonial-item text-center">

              <!-- Student Photo -->
              <img class="border rounded-circle p-2 mx-auto mb-3"
                src="<?php echo htmlspecialchars($row['customer_photo_path'] ?: 'img/default-user.png'); ?>"
                alt="<?php echo htmlspecialchars($row['customer_name']); ?>"
                style="width: 80px; height: 80px; object-fit: cover;">

              <!-- Student Name -->
              <h5 class="mb-1"><?php echo htmlspecialchars($row['customer_name']); ?></h5>

              <!-- College / Designation -->
              <?php if (!empty($row['designation'])): ?>
                <p class="text-muted small"><?php echo htmlspecialchars($row['designation']); ?></p>
              <?php else: ?>
                <p class="text-muted small">Final Year Student</p>
              <?php endif; ?>

              <!-- Review -->
              <div class="testimonial-text bg-light p-4 rounded review-box">
                <p class="mb-0">
                  <?php echo htmlspecialchars($row['review_text']); ?>
                </p>
              </div>

            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-center text-muted">No student reviews available yet.</p>
        <?php endif; ?>

      </div>
    </div>
  </div>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

  <!-- Testimonial End -->





  <!-- Footer Start -->

  <?php require_once __DIR__ . "/footer.php"; ?>

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