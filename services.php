<?php include './config/database.php';
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
    <title>Vsofts Solutions - Services</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Vsofts Services" name="keywords">
    <meta content="Our services include Academic Project Guidance, Internship Programs, and Corporate Training." name="description">

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
    <!-- Navbar Start -->
    <?php include 'navbar.php'; ?>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-primary mb-5 services-header d-flex align-items-center" style="min-height: 60vh;">
        <div class="container text-center">
            <h1 class="display-3 text-white animated slideInDown mb-3">Our Services</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item">
                        <a class="text-white" href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="text-white" href="about.php">about</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="text-white" href="services.php">services</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Header End -->

    <!-- Services Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-4">
                <!-- B.Tech Projects -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item text-center pt-3 h-100 shadow-sm">
                        <div class="p-4">
                            <i class="fa fa-3x fa-laptop-code text-primary mb-4"></i>
                            <h5 class="mb-3">B.Tech Projects</h5>
                            <p class="justify" style="text-align: justify;">
                                B.Tech projects enable students to apply engineering concepts to the real-world challenges, fostering innovation and technical expertise.
                                They build problem-solving, teamwork and research skills essential for professional growth.
                                These projects serve as a bridge between academic learning and industry readiness.
                            </p>
                        </div>
                        <a class="btn btn-primary py-3 px-5 mt-2" href="projects.php">Know More</a>
                    </div>
                    </a>
                </div>

                <!-- M.Tech Projects -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="service-item text-center pt-3 h-100 shadow-sm">
                        <div class="p-4">
                            <i class="fa fa-3x fa-microchip text-primary mb-4"></i>
                            <h5 class="mb-3">M.Tech Projects</h5>
                            <p class="justify" style="text-align: justify;">
                                M.Tech projects mainly focus on advanced research and specialized technical skills and problem-solving Skills in chosen domains.They enhance innovation,critical analysis,and deep subject expertise.
                                These projects prepare students for careers in R&D, academia or leadership roles in industry.
                            </p>
                        </div>
                        <a class="btn btn-primary py-3 px-5 mt-2" href="projects.php">Know More</a>
                    </div>
                    </a>
                </div>

                <!-- MBA Projects -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="service-item text-center pt-3 h-100 shadow-sm">
                        <div class="p-4">
                            <i class="fa fa-3x fa-briefcase text-primary mb-4"></i>
                            <h5 class="mb-3">MBA Projects</h5>
                            <p class="justify" style="text-align: justify;">
                                MBA projects provide practical exposure to the business management, finance and marketing. They develop leadership, analytical and decision-making skills through the industry-based case studies. These projects prepare students for strategic roles and entrepreneurial ventures.
                            </p>
                        </div>
                        <a class="btn btn-primary py-3 px-5 mt-2" href="projects.php">Know More</a>
                    </div>
                    </a>
                </div>

                <!-- MCA Projects -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="service-item text-center pt-3 h-100 shadow-sm">
                        <div class="p-4">
                            <i class="fa fa-3x fa-desktop text-primary mb-4"></i>
                            <h5 class="mb-3">MCA Projects</h5>
                            <p class="justify" style="text-align: justify;">
                                MCA projects mainly focus on software development, application design, and IT solutions. They enhance programming, database, system integration, problem-solving, and adaptability skills, fostering innovation, creativity, efficiency and technology expertise for a smooth transition into the IT industry.
                            </p>
                        </div>
                        <a class="btn btn-primary py-3 px-5 mt-2" href="projects.php">Know More</a>
                    </div>
                    </a>
                </div>

                <!-- Internship Programs -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="service-item text-center pt-3 h-100 shadow-sm">
                        <div class="p-4">
                            <i class="fa fa-3x fa-users text-primary mb-4"></i>
                            <h5 class="mb-3">Internship Programs</h5>
                            <p class="justify" style="text-align: justify;">
                                Summer, Winter and Virtual Internships offer hands-on industry experience under expert guidance.
                                They help students apply academic learning to practical scenarios and they build professional networks.
                                Internships mainly improve employability and prepare candidates for corporate environments.
                            </p class="justify" style="text-align: justify;">
                        </div>
                        <a class="btn btn-primary py-3 px-5 mt-2" href="internship.php">Know More</a>
                    </div>
                    </a>
                </div>

                <!-- Corporate Training -->
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
                    <div class="service-item text-center pt-3 h-100 shadow-sm">
                        <div class="p-4">
                            <i class="fa fa-3x fa-chalkboard-teacher text-primary mb-4"></i>
                            <h5 class="mb-3">Corporate Training</h5>
                            <p class="justify" style="text-align: justify;">
                                Customized Corporate training programs are designed to upskill professionals with the latest technologies and strategies.
                                They enhance productivity, leadership, and problem-solving abilities within the organizations.
                                These programs mainly bridge skill gaps and drive sustainable business growth.
                            </p>
                        </div>
                        <a class="btn btn-primary py-3 px-5 mt-2" href="internship.php">Know More</a>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Services End -->

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