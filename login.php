<?php
session_start();
include 'includes/config.php';


// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric_no = $_POST['matric_no'];
    $password = $_POST['password'];

    // Check if matric number exists in students table
    $sql = "SELECT * FROM students WHERE matric_no = '$matric_no'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['student_id'] = $row['student_id'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['matric_no'] = $row['matric_no'];
            $_SESSION['department'] = $row['department'];
            $_SESSION['level'] = $row['level'];
            $_SESSION['semester'] = $row['semester'];

            header("Location: students/index.php"); // Redirect to student dashboard
        } else {
            echo "<p style='color:red;'>Invalid password.</p>";
        }
    } else {
        echo "<p style='color:red;'>Student not found. Please check your Matric Number.</p>";
    }
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }

        h2 {
            background: #2c3e50;
            color: white;
            padding: 20px;
            margin: 0;
        }

        nav {
            background: #34495e;
            padding: 10px 0;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 15px;
            display: block;
            transition: 0.3s;
            cursor: pointer;
        }
        nav ul li a:hover {
            background: #1abc9c;
            border-radius: 5px;
        }

        .swiper {
            width: 80%;
            max-width: 900px;
            height: 400px;
            margin: 20px auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            background: white;
        }
        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            text-align: left;
            width: 50%;
        }
        .close {
            float: right;
            font-size: 24px;
            cursor: pointer;
        }
        .close:hover {
            color: red;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #1abc9c;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            transition: 0.3s;
        }
        button:hover {
            background: #16a085;
        }
        footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2> <img src="includes/aries-logo.png" alt="" style="width: 40px; height: 40px;"> <br> ARIES POLYTECHNIC, IBADAN</h2>
    <nav>
        <ul>
            <li><a href="login.php">Home</a></li>
            <li><a id="loginBtn">Login</a></li>
        </ul>
    </nav>

    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img src="includes/aries-logo.png" alt="POLYTECHNIC LOGO"></div>
            <div class="swiper-slide"><img src="includes/aries-poly.jpg" alt="POLYTECHNIC ENVIRONMENT"></div>
            <div class="swiper-slide"><img src="includes/aries-poly2.jpg" alt="ICT LAB"></div>
            <div class="swiper-slide"><img src="includes/aries-poly3.jpg" alt="SLT LABORATORY"></div>
            <div class="swiper-slide"><img src="includes/aries-poly4.jpeg" alt="STUDENTS IN AN EXAM HALL"></div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>

            <!-- Student Login Form -->
            <form method="POST">
                <h2>STUDENT LOGIN</h2>
                <input type="text" name="matric_no" placeholder="Matric Number" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit" name="login">Login</button> <br> <br>
                 <button type="submit" class="reg"><a href="register.php">Register</a></button>
                 <h3><B>ARE YOU AN ADMIN? <a href="admin/login.php"><i>Login Here</i></a></B></h3>
            </form>

        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Aries Polytechnic Admin Dashboard</p>
    </footer>

    <script>
        var modal = document.getElementById("loginModal");
        var btn = document.getElementById("loginBtn");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "flex";
        }
        span.onclick = function() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        var swiper = new Swiper(".mySwiper", {
            loop: true,
            autoplay: { delay: 3000 },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            pagination: { el: ".swiper-pagination", clickable: true }
        });
    </script>
</body>
</html>

