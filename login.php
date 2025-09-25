<?php

    // At the top of your PHP file
    error_reporting(E_ALL);
    ini_set('display_errors', 0);  // Do not show errors in response
    ini_set('log_errors', 1);      // Log them instead

    session_start();
    if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
        header("Location: ./admin/admin_dashboard.php");
        exit();
    } else if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'user') {
        header("Location: ./user/user_dashboard.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>VSoft Login Page</title>
        <style>
            .login-box {
                display: flex;
                width: 100%;
                height: 100%;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .login-box h2 {
                margin-top: 30px;
            }

            #loginForm {
                width: 350px;
                height: 300px;
                text-align: center;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            }

            .login-box h2 {
                text-align: center;
                margin-bottom: 20px;
                color: #333;
            }

            .login-box input {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            .login-box button {
                width: 100%;
                padding: 10px;
                background: #06BBCC;
                margin-top: 10px;
                border: none;
                color: white;
                font-weight: bold;
                border-radius: 5px;
                cursor: pointer;
            }

            .login-box button:hover {
                background: #06BBCC;
            }
        </style>
    </head>
    <body>
        <?php include "./navbar.php" ?>
        <div class="login-box">
            <h2>Login Page</h2>
            <form id="loginForm" method="POST">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <button type="submit">Login</button>
                <br><br>
                <!-- <a href="./forgot_password.php">Forgot Password?</a> -->
                <p>New User? <a href="./register.php">Register Here</a></p>
            </form>
        </div>
        <?php include "./footer.php" ?>
        <script>
            const apiUrl = "./controller/UserController.php";

            document.getElementById("loginForm").addEventListener("submit", async function(e) {
                e.preventDefault();

                let form = e.target;
                let email = form.email.value.trim();
                let password = form.password.value.trim();
            
                // Email validation
                if (email === "") {
                    alert("Email is required");
                    return;
                } else {
                    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(email)) {
                        alert("Enter a valid email address");
                        return;
                    }
                }

                // Password validation
                if (password === "") {
                    alert("Password is required");
                    return;
                }

                try {
                    let formData = new FormData(this);
                    formData.append("action", "getUser");
                    let response = await fetch(apiUrl, {
                        method: "POST",
                        body: formData
                    });
                    let responseText = await response.text();
                    let data = JSON.parse(responseText);

                    if (!data.success) {
                        alert("Invalid email or password.");
                        return;
                    }

                    // Check role
                    if (data.user.role === "admin") {
                        alert("Welcome Admin!");
                        window.location.href = "./admin/admin_dashboard.php";
                    } else {
                        alert("Login Success.");
                        window.location.href = "./user/user_dashboard.php";
                    }

                } catch (error) {
                    console.error("Error:", error);
                    alert("Something went wrong. Please try again.");
                }
            });
        </script>
    </body>
</html>