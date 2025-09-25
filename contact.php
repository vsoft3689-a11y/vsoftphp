<?php
include './config/database.php';
session_start();

$conn = (new Database())->connect();

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$configs = [];
$result = $conn->query("SELECT config_key, config_value FROM site_configurations");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $configs[$row['config_key']] = $row['config_value'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $conn->real_escape_string($_POST['name']);
    $email   = $conn->real_escape_string($_POST['email']);
    $phone   = $conn->real_escape_string($_POST['phone']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO inquiries (name, email, phone, subject, message, type, status) 
            VALUES ('$name', '$email', '$phone', '$subject', '$message', 'contact', 'pending')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('✅ Thank you! Your message has been sent.'); window.location='contact.php';</script>";
    } else {
        echo "<script>alert('❌ Error: " . $conn->error . "');</script>";
    }
}

// Fetch map url from DB
$sql = "SELECT config_value FROM site_configurations WHERE config_key = 'map' LIMIT 1";

$result = $conn->query($sql);
$map_url = "";
if ($row = $result->fetch_assoc()) {
    $map_url = $row['config_value'];
}

// Helper function
function getEmbedMapUrl($url)
{
    if (strpos($url, "google.com/maps/embed") !== false) {
        return $url; // already embed
    }
    if (strpos($url, "google.com/maps") !== false) {
        return str_replace("/maps/", "/maps/embed?", $url);
    }
    return $url; // fallback
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>VsoftSolutions - Contact</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="img/favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Fonts -->
    <!-- Icon Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries -->
    <!-- Libraries -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- CSS -->
    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/contact.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Header -->
    <div class="container-fluid bg-primary py-5 mb-5 contact-header">
        <div class="container py-5 text-center text-white">
            <h1 class="display-3 text-white">Contact Us</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a class="text-white" href="./index.php">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="./about.php">About</a></li>
                    <li class="breadcrumb-item text-white">Contact</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="section-title bg-white text-primary px-3">Contact Us</h6>
                <h1>Contact For Any Query</h1>
            </div>
            <div class="row g-4">
                <!-- Info -->
                <div class="col-lg-4">
                    <h5 class="text-primary">Get In Touch</h5>
                    <p style="text-align:justify" class="justify">
                        Have questions about academic projects, internships, or training programs?
                        VSoft Solutions is here to help! Reach out to us for support with B.Tech, M.Tech, MBA, or MCA project development, internship details, or any general inquiries.
                        Our team will get back to you shortly.
                    </p>
                    <div style="text-align:justify">
                        <p><i class="fa fa-map-marker-alt text-primary me-2"></i> <?= $configs['address'] ?? 'Default Address'; ?></p>
                        <p><i class="fa fa-phone-alt text-primary me-2"></i> <?= $configs['landline'] ?? 'Default Phone'; ?></p>
                        <p><i class="fa fa-envelope text-primary me-2"></i> <?= $configs['email'] ?? 'Default Email'; ?></p>
                    </div>
                </div>
                <!-- Map -->

                <div class="col-lg-4">
                    <?php if (!empty($map_url)): ?>
                        <iframe
                            src="<?= htmlspecialchars(getEmbedMapUrl($map_url)); ?>"
                            style="width:100%; height:350px; border:0; border-radius:8px;"
                            allowfullscreen
                            loading="lazy">
                        </iframe>
                    <?php else: ?>
                        <p>No map found</p>
                    <?php endif; ?>
                </div>
                <!-- <div class="col-lg-4">
                    <?= str_replace('<iframe', '<iframe style="width:100%; height:350px; border-radius:8px;"', $configs['google_map'] ?? '<p>No map found</p>'); ?>
                </div> -->

                <!-- Form -->
                <div class="col-lg-4">
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="phone" placeholder="Your Phone">
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control" name="subject" placeholder="Subject" required>
                            </div>
                            <div class="col-12">
                                <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100" type="submit">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>