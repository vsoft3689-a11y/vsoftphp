<style>
    .page-wrapper {
        min-height: 60vh;
        display: flex;
        flex-direction: column;
    }

    .footer {
        background: #222;
        color: #fff;
        padding: 10px 0;
        text-align: center;
        margin-top: auto;
    }

    body {
        margin: 0;
    }

    .back-to-top {
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: none;
        z-index: 999;
    }
</style>

<body>
    <div class="page-wrapper">
        <div class="footer container-fluid bg-dark text-light wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-3">
                <div class="row">
                    <div class="col-md-12">
                        &copy; <a class="border-bottom" href="#">VSOFTSSolutions</a>, All Right Reserved.
                        Designed By <a class="border-bottom" target="_blank" href="https://westechnologies.in/">westechnologies.in</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
        <i class="bi bi-arrow-up"></i>
    </a>

    <script>
        // Show/hide button on scroll
        window.addEventListener("scroll", function() {
            const backToTop = document.querySelector(".back-to-top");
            if (window.scrollY > 100) {
                backToTop.style.display = "block";
            } else {
                backToTop.style.display = "none";
            }
        });

        // Smooth scroll to top
        document.querySelector(".back-to-top").addEventListener("click", function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    </script>
</body>