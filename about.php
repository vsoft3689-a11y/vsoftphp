<?php include './config/database.php';

session_start();

$conn = (new Database())->connect();

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => $conn->connect_error]));
}
$sql = "SELECT * FROM team_members WHERE is_active = 1 ORDER BY display_order ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>VsoftsSolutions</title>
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

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/about.css" rel="stylesheet">

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

    <div class="container-fluid bg-primary mb-5 about-header d-flex align-items-center" style="min-height: 60vh;">
        <div class="container text-center">
            <h1 class="display-3 text-white animated slideInDown mb-3">About Us</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item">
                        <a class="text-white" href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="text-white" href="about.php">about</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="text-white" href="services.php">Services</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="img-fluid position-absolute w-100 h-100" src="img/about1.jpg" alt="" style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <h6 class="section-title bg-white text-start text-primary pe-3">About Us</h6>
                    <h1 class="mb-4">Welcome to vsoftsolutions</h1>
                    <p class="mb-4">Vsoft Solutions began with the mission of delivering advanced IT services to businesses around the globe. What started as a small venture has evolved into a reliable technology partner supporting organizations in diverse industries. Our commitment to innovation, excellence, and client satisfaction has been the foundation of our growth and will continue to guide us ahead.</p>
                    <p class="mb-4">In addition, we specialize in offering academic as well as industrial project solutions, complemented with training and guidance to help learners and professionals achieve their goals.</p>
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
                        </div>
                    </div>
                    <a class="btn btn-primary py-3 px-5 mt-2" href="services.php">Read More</a>
                </div>
            </div>
        </div>
    </div>

    <!-- About End -->
    <!-- Company History, Mission & Vision Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Company History -->
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title bg-white text-start text-primary pe-3">Our History</h6>
                    <h2 class="mb-3">How We Started</h2>
                    <p class="justify" style="text-align: justify;">V SOFTS SOLUTIONS established in 2003 with the vision to give best-in-class programming improvement and quality confirmation administrations to a various arrangement of clients. In today's market, Companies who depend on seaward improvement as major to their business achievement must secure the correct blend of mastery and experience from its accomplices.</p>
                </div>

                <!-- Mission -->
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <h6 class="section-title bg-white text-start text-primary pe-3">Our Mission</h6>
                    <h2 class="mb-3">What Drives Us</h2>
                    <p class="justify" style="text-align: justify;">We are committed to providing outstanding software solutions tailored to our clients' specific needs. Our goal is to help them excel in a competitive market and reach their strategic goals. By understanding their unique challenges, we deliver innovative and effective solutions. We prioritize quality, efficiency, and client satisfaction in every project. Partner with us to stay ahead and succeed.</p>
                </div>

                <!-- Vision -->
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <h6 class="section-title bg-white text-start text-primary pe-3">Our Vision</h6>
                    <h2 class="mb-3">Looking Ahead</h2>
                    <p class="justify" style="text-align: justify;">Our mission is to revolutionize industries with disruptive software technologies. We aim to redefine possibilities and inspire innovation, driving forward the future of digital transformation. By pushing the boundaries, we create solutions that set new standards. Our commitment is to lead the way in technological advancements. Join us in shaping the future.</p>

                </div>
            </div>
        </div>
    </div>
    <!-- Company History, Mission & Vision End -->
    <!-- Achievements Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <!-- Left Side: Text Content -->
                <div class="col-lg-7 wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title bg-white text-start text-primary pe-3">Our Achievements</h6>
                    <h2 class="mb-3">Milestones We Are Proud Of</h2>
                    <p class="mb-4">
                        At Vsofts Solutions, we take pride in the accomplishments we have achieved over the years.
                        Each milestone reflects our dedication to innovation, quality, and client success.
                        These achievements strengthen our vision to keep growing and delivering value to our partners worldwide.
                    </p>

                    <div class="row gy-3">
                        <div class="col-12">
                            <p class="mb-0 text-nowrap"><i class="fa fa-check-circle text-primary me-2"></i> Successfully completed <b>1500+ academic & industrial projects</b> across multiple domains.</p>
                        </div>
                        <div class="col-12">
                            <p class="mb-0 text-nowrap"><i class="fa fa-check-circle text-primary me-2"></i> Trusted by <b>200+ corporate clients</b> for IT solutions and services.</p>
                        </div>
                        <div class="col-12">
                            <p class="mb-0 text-nowrap"><i class="fa fa-check-circle text-primary me-2"></i> Trained and guided <b>5000+ students & professionals</b> in advanced technologies.</p>
                        </div>
                        <div class="col-12">
                            <p class="mb-0 text-nowrap"><i class="fa fa-check-circle text-primary me-2"></i> Delivered <b>20+ years</b> of consistent excellence since our establishment in 2003.</p>
                        </div>
                        <div class="col-12">
                            <p class="mb-0 text-nowrap"><i class="fa fa-check-circle text-primary me-2"></i> Recognized for <b>cutting-edge solutions</b> in AI, IoT, Cloud, and Enterprise Software.</p>
                        </div>
                        <div class="col-12">
                            <p class="mb-0 text-nowrap"><i class="fa fa-check-circle text-primary me-2"></i> Expanded presence with <b>global clients</b> in education, healthcare, finance, and IT sectors.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Image -->
                <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="position-relative h-100">
                        <img class="img-fluid rounded shadow" src="img/achivements.jpg.jpg" alt="Achievements" style="object-fit: cover; width:100%;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Achievements End -->

    <!-- Team Section Start -->
    <div class="container-xxl py-5">
        <div class="container">

            <!-- Section Heading -->
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Team Members</h6>
                <h2 class="mb-5">Meet Our Professional Team</h2>
            </div>

            <div class="row g-4">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="team-item bg-light">
                                <div class="overflow-hidden">
                                    <img class="img-fluid" src="<?php echo htmlspecialchars($row['profile_picture_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                </div>
                                <div class="position-relative d-flex justify-content-center" style="margin-top: -23px;">
                                    <div class="bg-light d-flex justify-content-center pt-2 px-1">
                                        <?php if (!empty($row['social_facebook'])) { ?>
                                            <a class="btn btn-sm-square btn-primary mx-1" href="<?php echo $row['social_facebook']; ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                        <?php } ?>
                                        <?php if (!empty($row['social_twitter'])) { ?>
                                            <a class="btn btn-sm-square btn-primary mx-1" href="<?php echo $row['social_twitter']; ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                                        <?php } ?>
                                        <?php if (!empty($row['social_linkedin'])) { ?>
                                            <a class="btn btn-sm-square btn-primary mx-1" href="<?php echo $row['social_linkedin']; ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="text-center p-4">
                                    <h5 class="mb-0"><?php echo htmlspecialchars($row['name']); ?></h5>
                                    <small><?php echo htmlspecialchars($row['designation']); ?></small>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p>No team members found.</p>";
                }
                ?>

            </div>
        </div>
    </div>
    </div>
    </div>
    <!-- Team Section End -->

    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
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
</body>

</html>