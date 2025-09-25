<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password</title>
  <style>
    .fp-box {
      max-width: 420px;
      margin: 40px auto;
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }

    .fp-box h2 {
      text-align: center;
      margin-bottom: 10px;
    }

    .fp-box p {
      text-align: center;
      color: #555;
    }

    .fp-box input {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .fp-box button {
      width: 100%;
      padding: 10px;
      background: #06BBCC;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
    }

    .fp-box button:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .link {
      text-align: center;
      margin-top: 10px;
    }

    .hidden {
      display: none;
    }
  </style>
</head>

<body>
  <?php include "./navbar.php"; ?>
  <div class="fp-box">
    <h2>Forgot Password</h2>
    <p>Enter your account email to receive a One-Time Password (OTP).</p>

    <div id="step-email">
      <input type="email" id="email" placeholder="Email address" />
      <button id="btnSendOtp">Send OTP</button>
    </div>

    <div id="step-otp" class="hidden">
      <p>We've sent an OTP to your email. Enter it below to verify.</p>
      <input type="text" id="otp" placeholder="Enter 6-digit OTP" maxlength="6" />
      <button id="btnVerifyOtp">Verify OTP</button>
    </div>

    <div id="step-reset" class="hidden">
      <input type="password" id="password" placeholder="New password" />
      <input type="password" id="confirm" placeholder="Confirm new password" />
      <button id="btnReset">Reset Password</button>
    </div>

    <div class="link">
      <a href="./login.php">Back to Login</a>
    </div>
  </div>
  <?php include "./footer.php"; ?>

  <script>
    const apiUrl = "./controller/UserController.php";
    let currentEmail = "";

    const stepEmail = document.getElementById("step-email");
    const stepOtp = document.getElementById("step-otp");
    const stepReset = document.getElementById("step-reset");

    function show(el) {
      el.classList.remove("hidden");
    }

    function hide(el) {
      el.classList.add("hidden");
    }

    function validEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Step 1: Request OTP
    document.getElementById("btnSendOtp").addEventListener("click", async () => {
      const email = document.getElementById("email").value.trim();
      if (!email) {
        alert("Email is required");
        return;
      }
      if (!validEmail(email)) {
        alert("Enter a valid email");
        return;
      }

      try {
        const fd = new FormData();
        fd.append("action", "request_password_reset");
        fd.append("email", email);
        const res = await fetch(apiUrl, {
          method: "POST",
          body: fd
        });

        const text = await res.text();
        let data = {};
        try {
          data = JSON.parse(text);
        } catch (parseErr) {
          console.warn("Non-JSON response from request_password_reset:", text);
          // Fallback: allow user to proceed to OTP entry to avoid blocking UX
          data = {
            success: true,
            message: "Proceed to OTP entry.",
            email_sent: false
          };
        }

        if (!data.success) {
          alert(data.message || "Failed to send OTP");
          return;
        }

        currentEmail = email;
        if (data.email_sent === false) {
          console.warn("Email sending may have failed; proceeding to OTP entry anyway.");
        }
        alert(data.message || "If the email exists, an OTP has been sent.");
        hide(stepEmail);
        show(stepOtp);
      } catch (e) {
        console.error("Error calling request_password_reset:", e);
        alert("Something went wrong. Please try again.");
      }
    });

    // Step 2: Verify OTP
    document.getElementById("btnVerifyOtp").addEventListener("click", async () => {
      const otp = document.getElementById("otp").value.trim();
      if (otp.length !== 6) {
        alert("Please enter the 6-digit OTP");
        return;
      }
      try {
        const fd = new FormData();
        fd.append("action", "verify_otp");
        fd.append("email", currentEmail);
        fd.append("otp", otp);
        const res = await fetch(apiUrl, {
          method: "POST",
          body: fd
        });
        const data = await res.json();
        if (!data.success) {
          alert(data.message || "Invalid or expired OTP");
          return;
        }
        hide(stepOtp);
        show(stepReset);
      } catch (e) {
        console.error(e);
        alert("Something went wrong. Please try again.");
      }
    });

    // Step 3: Reset Password
    document.getElementById("btnReset").addEventListener("click", async () => {
      const pass = document.getElementById("password").value.trim();
      const confirm = document.getElementById("confirm").value.trim();
      const otp = document.getElementById("otp").value.trim();
      if (pass.length < 6) {
        alert("Password must be at least 6 characters");
        return;
      }
      if (pass !== confirm) {
        alert("Passwords do not match");
        return;
      }

      try {
        const fd = new FormData();
        fd.append("action", "reset_password");
        fd.append("email", currentEmail);
        fd.append("otp", otp);
        fd.append("password", pass);
        const res = await fetch(apiUrl, {
          method: "POST",
          body: fd
        });
        const data = await res.json();
        if (!data.success) {
          alert(data.message || "Failed to reset password");
          return;
        }
        alert("Password reset successful. Please login.");
        window.location.href = "./login.php";
      } catch (e) {
        console.error(e);
        alert("Something went wrong. Please try again.");
      }
    });
  </script>
</body>

</html>