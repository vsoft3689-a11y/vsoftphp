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


  <!-- Custom CSS -->
  <style>
    body {
      background-color: #fff;
      font-family: 'Nunito', sans-serif;
    }

    .faq-section {
      max-width: 900px;
      margin: 60px auto;
      background-color: #ffffff;
      border-radius: 12px;
      padding: 40px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .faq-section h1 {
      font-size: 2.5rem;
      margin-bottom: 30px;
      font-weight: 700;
      text-align: center;
      color: #343a40;
    }

    .accordion-button {
      font-weight: 600;
      font-size: 1.1rem;
      color: #000000;
      background-color: #d0e7ff;
    }

    .accordion-button:hover {
      background-color: #0b5ed7;
      color: #ffffff;
    }

    .accordion-item {
      border: none;
      margin-bottom: 15px;
      border-radius: 10px;
      overflow: hidden;
    }

    .accordion-body {
      background-color: #f8f9fa;
      color: #333;
      font-size: 1rem;
      line-height: 1.6;
    }

    .icon {
      margin-right: 10px;
      color: #ffc107;
    }

    .accordion-button::after {
      filter: brightness(0) invert(1);
      /* Makes arrow white */
    }
  </style>
</head>

<body>
  <!-- Navbar Start -->
  <?php include 'navbar.php'; ?>
  <!-- Navbar End -->

  <div class="container faq-section">
    <h1>Frequently Asked Questions</h1>
    <div class="accordion" id="faqAccordion">
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
            <i class="fas fa-magic icon"></i> What is VSoft Solutions?
          </button>
        </h2>
        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            VSoft Solutions is a project development and training company offering academic projects and internships for students from various technical and management fields.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
            <i class="fas fa-book icon"></i> What courses does VSoft support?
          </button>
        </h2>
        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            VSoft provides project support and training for students from <strong>MBA, MCA, B.Tech, M.Tech</strong>, and other professional courses.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
            <i class="fas fa-briefcase icon"></i> Do you offer internship opportunities?
          </button>
        </h2>
        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Yes, VSoft offers internships with real-time project experience, documentation, and certification for students looking to enhance their resumes.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
            <i class="fas fa-industry icon"></i> Are the projects industry-relevant?
          </button>
        </h2>
        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Absolutely. All projects are designed based on the latest industry trends and technologies to ensure practical learning.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
            <i class="fas fa-user-plus icon"></i> How can I join or enroll for a project?
          </button>
        </h2>
        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            You can contact us directly through our website or visit our office. Once registered, our team will guide you through project selection, development, and training.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
            <i class="fas fa-graduation-cap icon"></i> Are these projects suitable for final-year students?
          </button>
        </h2>
        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Yes, VSoft specializes in final-year academic projects with documentation and viva support for university submissions.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
            <i class="fas fa-code icon"></i> Do you provide support for coding and documentation?
          </button>
        </h2>
        <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Yes, we provide complete project development, coding, testing, and documentation based on university or college guidelines.
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer Start -->
  <?php include 'footer.php'; ?>
  <!-- Footer End -->

  <!-- Bootstrap Bundle with Popper (Required for accordion) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>