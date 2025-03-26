<?php
include '../includes/config.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$department = $_SESSION['department'];
$level = $_SESSION['level'];

$sql = "SELECT * FROM courses WHERE department='$department' AND level='$level'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <!-- Swiper.js CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <style>
        /* General Page Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }

        /* Header Styling */
        h2 {
            background: #2c3e50;
            color: white;
            padding: 20px;
            margin: 0;
        }

        /* Navigation Menu Styling */
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
            position: relative;
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
        }
        nav ul li a:hover {
            background: #1abc9c;
            border-radius: 5px;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #2c3e50;
            min-width: 200px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            z-index: 1000;
        }
        .dropdown-menu a {
            padding: 10px 15px;
            color: white;
            text-align: left;
        }
        .dropdown-menu a:hover {
            background: #1abc9c;
        }
        nav ul li:hover .dropdown-menu {
            display: block;
        }

        /* Swiper Container */
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

        /* Swiper Navigation */
        .swiper-button-next, .swiper-button-prev {
            color: #fff;
        }
        .swiper-pagination {
            bottom: 10px;
        }

        /* Footer Styling */
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
<h2> ARIES POLYTECHNIC, IBADAN</h2>
<h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>(Student)</h2>

    <!-- Navigation Menu -->
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>

            <li>
                <a href="#">Courses ▾</a>
                <div class="dropdown-menu">
                <a href="print_slip.php">View Course Slip</a>
                <a href="dashboard.php">Course Registration</a>
                </div>
            </li>

            <li>
                <a href="#">Results ▾</a>
                <div class="dropdown-menu">
                <a href="view.php">Check Results</a>
                </div>
            </li>

            <li><a href="../login.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Swiper Slider -->
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img src="../includes/aries-logo.png" alt="POLYTECHNIC LOGO"></div>
            <div class="swiper-slide"><img src="../includes/aries-poly.jpg" alt="POLYTECHNIC ENVIRONMENT"></div>
            <div class="swiper-slide"><img src="../includes/aries-poly2.jpg" alt="ICT LAB"></div>
            <div class="swiper-slide"><img src="../includes/aries-poly3.jpg" alt="SLT LABORATORY"></div>
            <div class="swiper-slide"><img src="../includes/aries-poly4.jpeg" alt="STUDENTS IN AN EXAM HALL"></div>
        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Aries Polytechnic Admin Dashboard</p>
    </footer>

    <!-- Swiper JS -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            autoplay: { delay: 3000 },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            pagination: { el: ".swiper-pagination", clickable: true }
        });
    </script>
</body>
</html>
