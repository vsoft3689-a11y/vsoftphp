<?php
include_once "../config/database.php";

$conn = (new Database())->connect();

if (!$conn || $conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch all site configurations into an array
$configs = [];
$result = $conn->query("SELECT config_key, config_value FROM site_configurations");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $configs[$row['config_key']] = $row['config_value'];
    }
}
?>

<!-- Footer Start -->
<div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">

            <!-- Quick Links -->
            <div class="col-lg-3 col-md-6">
                <h4 class="text-white mb-3">Quick Link</h4>
                <a class="btn btn-link" href="./about.php">About Us</a>
                <a class="btn btn-link" href="./contact.php">Contact Us</a>
                <a class="btn btn-link" href="./privacypolicy.php">Privacy Policy</a>
                <a class="btn btn-link" href="./terms.php">Terms & Condition</a>
                <a class="btn btn-link" href="./faq.php">FAQs & Help</a>
            </div>

            <!-- Contact -->
            <div class="col-lg-3 col-md-6">
                <h4 class="text-white mb-3">Contact</h4>
                <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>
                    <?php echo htmlspecialchars($configs['address'] ?? 'Default Address'); ?>
                </p>
                <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>
                    <?php echo htmlspecialchars($configs['landline'] ?? 'Default Phone'); ?>
                </p>
                <p class="mb-2"><i class="fa fa-envelope me-3"></i>
                    <?php echo htmlspecialchars($configs['email'] ?? 'Default Email'); ?>
                </p>
                <div class="d-flex pt-2">
                    <a class="btn btn-outline-light btn-social" href="<?php echo htmlspecialchars($configs['twitter'] ?? '#'); ?>"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-outline-light btn-social" href="<?php echo htmlspecialchars($configs['facebook'] ?? '#'); ?>"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-social" href="<?php echo htmlspecialchars($configs['youtube'] ?? '#'); ?>"><i class="fab fa-youtube"></i></a>
                    <a class="btn btn-outline-light btn-social" href="<?php echo htmlspecialchars($configs['linkedin'] ?? '#'); ?>"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Gallery -->
            <div class="col-lg-3 col-md-6">
                <h4 class="text-white mb-3">Gallery</h4>
                <div class="row g-2 pt-2">
                    <div class="col-4"><a href="../img/major1.jpg"><img class="img-fluid bg-light p-1" src="../img/major1.jpg" alt="Gallery Image 1"></a></div>
                    <div class="col-4"><a href="../img/mini1.jpg"><img class="img-fluid bg-light p-1" src="../img/mini1.jpg" alt="Gallery Image 2"></a></div>
                    <div class="col-4"><a href="../img/internship.jpg"><img class="img-fluid bg-light p-1" src="../img/internship.jpg" alt="Gallery Image 3"></a></div>
                    <div class="col-4"><a href="../img/cyber.jpg"><img class="img-fluid bg-light p-1" src="../img/cyber.jpg" alt="Gallery Image 4"></a></div>
                    <div class="col-4"><a href="../img/structural.jpg"><img class="img-fluid bg-light p-1" src="../img/structural.jpg" alt="Gallery Image 5"></a></div>
                    <div class="col-4"><a href="../img/mini1.jpg"><img class="img-fluid bg-light p-1" src="../img/mini1.jpg" alt="Gallery Image 6"></a></div>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="col-lg-3 col-md-6">
                <h4 class="text-white mb-3">Subscribe to our Newsletter</h4>
                <form action="subscribe.php" method="POST">
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input name="email" class="form-control border-0 w-100 py-3 ps-4 pe-5" type="email" placeholder="Your email" required>
                        <button type="submit" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">
                            Subscribe
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Copyright -->
    <div class="container">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">VSOFTSSolutions</a>, All Rights Reserved.
                    Designed By <a class="border-bottom" href="./">westechnologies.in</a>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-menu">
                        <a href="./index.php">Home</a>
                        <a href="./contact.php">Help</a>
                        <a href="./faq.php">FAQs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

<?php $conn->close(); ?>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>