<?php
include_once("config.php");

$search = $_GET['search'] ?? '';

if ($search) {
    $query = "SELECT * FROM products WHERE name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
} else {
    $query = "SELECT * FROM products";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAV | Product Page</title>
    <link rel="icon" type="image/png" href="assets/logo.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            padding-top: 70px;
        }

        a {
            text-decoration: none;
            color: white;
        }

        nav {
            background-color: #800000;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }

        .brand-image {
            width: 75px;
        }

        .logo-wrapper img {
            width: 30px;
        }

        .text-content span {
            color: #800000;
            font-weight: bold;
        }

        .carousel-inner {
            width: 100%;
            max-width: 600px;
            height: 400px;
            margin: auto;
            overflow: hidden;
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .three-box {
            background-color: #f2e6e6;
            width: 200px;
            height: 200px;
            border-radius: 10px;
        }

        .three-box img {
            width: 75%;
        }

        .nav-tabs {
            border-bottom: none;
        }

        .nav-tabs .nav-link {
            font-weight: bold;
            color: black;
            border: none;
            border-bottom: 2px solid transparent;
            margin: 0 1rem;
        }

        .nav-tabs .nav-link.active {
            border-bottom: 2px solid black;
        }

        .product-container:hover {
            transform: scale(1.05);
        }

        .carousel-item,
        .product-container {
            transition: transform ease-in-out 0.3s;
        }

        form button{
            width: 100px;
            padding: 12px 25px;
            border: none;
            background-color: #800000 !important;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #e6d6d6 !important;
            color: #800000 !important;
        }

        footer {
            background-color: #800000;
            color: #f2e6e6;
        }

        footer img {
            width: 200px;
        }

        .footer-text p {
            font-weight: bold;
        }

        @media (max-width: 991.98px) {
            nav.navbar {
                position: static !important;
            }

            body {
                padding-top: 0 !important;
            }
        }

        @media(max-width: 768px) {
            .three-box {
                width: 250px;
                height: 250px;
            }
        }

        @media (max-width: 576px) {
            .product-container img {
                max-width: 300px;
                margin: 0 auto;
                display: block;
            }
        }

        .offer-sale-section .product-container,
        .best-seller-section .product-container,
        #produkTabContent .product-container {
            width: 20%;
        }

        @media (max-width: 992px) {

            .offer-sale-section .product-container,
            #produkTabContent .product-container {
                width: 45%;
            }

            .best-seller-section .product-container {
                width: 45%;
            }

            .product-section .product-container {
                width: 45%
            }
        }

        @media (max-width: 576px) {

            .offer-sale-section .product-container,
            #produkTabContent .product-container {
                width: 100% !important;
            }

            .offer-sale-section .product-container img,
            #produkTabContent .product-container img {
                max-width: 300px;
                margin: 0 auto;
                display: block;
            }

            .offer-sale-section .row,
            #produkTabContent .row {
                gap: 2rem !important;
            }
        }

        @media (max-width: 480px) {

            .offer-sale-section .product-container,
            #produkTabContent .product-container {
                max-width: 40vw;
            }

            .best-seller-section .product-container {
                max-width: 40vw;
            }

            .product-section .product-container {
                max-width: 40vw;
            }

            footer img {
                max-width: 150px;
            }
        }
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-maroon fixed-top px-3">
            <div class="container-fluid d-flex justify-content-between align-items-center">

                <!-- Logo kiri -->
                <a href="index.php" class="navbar-brand">
                    <img src="assets/logo.svg" alt="Logo" class="brand-image">
                </a>

                <!-- Menu tengah -->
                <div class="d-none d-lg-flex flex-grow-1 justify-content-center">
                    <ul class="navbar-nav mb-2 mb-lg-0 gap-4">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="product.php">Product</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php#footer">About</a>
                        </li>
                    </ul>
                </div>

                <!-- Cart & profile kanan -->
                <div class="d-none d-lg-flex gap-4">
                    <a href="cart.php"><img src="assets/cart.svg" alt="Cart" width="25"></a>
                    <a href="<?= ($_SESSION['role'] ?? '') === 'admin' ? 'adminprofile.php' : 'userprofile.php' ?>"><img
                            src="assets/profile.svg" alt="Profile" width="25"></a>
                </div>

                <!-- Hamburger toggle untuk mobile -->
                <button class="navbar-toggler ms-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

            </div>

            <!-- Collapse menu (mobile) -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav d-lg-none gap-3 text-center mt-3">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="product.php">Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#footer">About</a>
                    </li>
                    <li class="nav-item d-flex justify-content-center gap-3 mt-3">
                        <a href="cart.php"><img src="assets/cart.svg" alt="Cart" width="25"></a>
                        <a href="<?= ($_SESSION['role'] ?? '') === 'admin' ? 'adminprofile.php' : 'userprofile.php' ?>"><img
                                src="assets/profile.svg" alt="Profile" width="25"></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <section class="best-seller-section d-flex flex-column align-items-center justify-content-center mt-5">
        <h1 class="fw-bold">ALL PRODUCTS</h1>
        <form class="w-50 mt-4" method="GET" action="product.php">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari produk" name="search"
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button class="btn text-white" type="submit">Cari</button>
            </div>
        </form>
        <div class="container mt-5">
            <div class="row justify-content-center gx-4 gy-4 gap-3 gap-sm-0">
                <?php while ($row = mysqli_fetch_assoc($result)):
                    ?>
                    <div class="col-12 col-sm-6 col-md-3 col-lg-3 product-container">
                        <a href="orderpage.php?product_id=<?= $row['id'] ?>" class="text-start text-dark d-block">
                            <img src="uploads/<?= $row['image'] ?>" class="w-100" alt="<?= $row['name'] ?>">
                            <h6 class="mb-0 mt-2"><?= htmlspecialchars($row['name']) ?></h6>
                            <p>Rp <?= number_format($row['price_after_discount'], 0, ',', '.') ?></p>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <footer>
        <div class="container py-5 mt-5">
            <div class="row justify-content-between align-items-center text-center text-md-start">
                <!-- Logo -->
                <div class="col-12 col-md-3 mb-4 mb-md-0">
                    <img src="assets/logo.svg" alt="Logo" class="img-fluid">
                </div>

                <!-- Footer Content -->
                <div class="col-12 col-md-8">
                    <div class="row g-4">
                        <!-- Legal Pages -->
                        <div class="col-12 col-sm-4">
                            <h6 class="fw-bold">Legal Pages</h6>
                            <ul class="list-unstyled">
                                <li><a href="#" class="text-decoration-none">Privacy & Policy</a></li>
                                <li><a href="#" class="text-decoration-none">Terms & Conditions</a></li>
                            </ul>
                        </div>
                        <!-- Contact Us -->
                        <div class="col-12 col-sm-4">
                            <h6 class="fw-bold">Contact Us</h6>
                            <ul class="list-unstyled">
                                <li><a href="#" class="text-decoration-none">Email: info@example.com</a></li>
                                <li><a href="#" class="text-decoration-none">Phone: +62 812 3456 7890</a></li>
                            </ul>
                        </div>
                        <!-- Quick Links -->
                        <div class="col-12 col-sm-4">
                            <h6 class="fw-bold">Quick Links</h6>
                            <ul class="list-unstyled">
                                <li><a href="#" class="text-decoration-none">Home</a></li>
                                <li><a href="#" class="text-decoration-none">Shop</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="row mt-4">
                <div class="col text-center">
                    <p class="mb-0">© 2025 CAV / Nawwaf Zayyan All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>