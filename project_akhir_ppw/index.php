<?php
include_once("config.php");

$query_men = "SELECT *
                FROM products p 
                WHERE gender = 'men'";
$result_men = mysqli_query($conn, $query_men);

$query_women = "SELECT *
                    FROM products p 
                    WHERE gender = 'women'";
$result_women = mysqli_query($conn, $query_women);

$query_top = "SELECT p.*, SUM(oi.quantity) AS total_sold
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        GROUP BY p.id
        ORDER BY total_sold DESC
        LIMIT 4";

$result_top = mysqli_query($conn, $query_top);

$query_offer = "SELECT * FROM products WHERE discount IS NOT NULL and discount != 0 ORDER BY discount DESC";

$result_offer = mysqli_query($conn, $query_offer);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAV | Fashion Store</title>
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

        .hero-section button{
            box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.5);
        }

        .hero-section button:hover{
            box-shadow: 0 0 0 0;
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

                <a href="index.php" class="navbar-brand">
                    <img src="assets/logo.svg" alt="Logo" class="brand-image">
                </a>

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

                <div class="d-none d-lg-flex gap-4">
                    <a href="cart.php"><img src="assets/cart.svg" alt="Cart" width="25"></a>
                    <a href="<?= ($_SESSION['role'] ?? '') === 'admin' ? 'adminprofile.php' : 'userprofile.php' ?>"><img
                            src="assets/profile.svg" alt="Profile" width="25"></a>
                </div>

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
    <section class="hero-section container py-5 mt-5">
        <div class="row align-items-center">
            <div class="col-12 col-lg-6 order-1 order-lg-1 mb-4 mb-lg-0">
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="assets/carousel-1.png" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/carousel-2.png" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/carousel-3.png" class="d-block w-100" alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev d-none" type="button" data-bs-target="#carouselExample"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next d-none" type="button" data-bs-target="#carouselExample"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="col-12 col-lg-6 order-2 order-lg-2 
                d-flex flex-column gap-3 
                align-items-center align-items-lg-start 
                text-center text-lg-start">
                <h1>Discover Our <span class="fw-bold" style="color: #800000;">Loveable</span> Modern Fashion</h1>
                <p>Welcome to CAV — your go-to spot for all things fashion! Whether you’re hunting for the latest trends or looking to sell your stylish pieces, we make it super easy and fun. Shop cool clothes and accessories from trusted sellers or turn your closet into cash in just a few clicks. Ready to refresh your wardrobe and show off your unique style? <span class="fw-bold"> Let’s begin your style with CAV! </span> </p>
                <a href="product.php"><button type="button" class="btn btn-dark align-self-xl-start align-self-center">Take a Look!</button></a>
            </div>
        </div>
    </section>

    <section class="mt-2">
        <div
            class="block-container d-flex flex-column flex-md-row gap-5 justify-content-center align-items-center align-items-md-start p-5">
            <div class="three-box d-flex flex-column justify-content-center align-items-center gap-3 p-4">
                <img src="assets/trusted.svg" alt="">
                <h6>100% Trusted</h6>
            </div>
            <div class="three-box d-flex flex-column justify-content-center align-items-center gap-3 p-4">
                <img src="assets/discount.svg" alt="">
                <h6>Offer Sale</h6>
            </div>
            <div class="three-box d-flex flex-column justify-content-center align-items-center gap-3 p-4">
                <img src="assets/truck.svg" alt="">
                <h6>Free Shipping</h6>
            </div>
        </div>
    </section>
    <section class="best-seller-section d-flex flex-column align-items-center justify-content-center mt-5">
        <h1 class="fw-bold">OUR BEST SELLER</h1>
        <div class="container mt-3">
            <div class="row justify-content-center gx-4 gy-4 gap-3 gap-sm-0">
                <?php while ($row = mysqli_fetch_assoc($result_top)):
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
    <section class="offer-sale-section d-flex flex-column align-items-center mt-5">
        <h1 class="fw-bold">OFFER SALE</h1>
        <div class="container mt-3">
            <div class="row justify-content-center gx-4 gy-4 gap-3">
                <?php
                while ($row = mysqli_fetch_assoc($result_offer)) {
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 product-container">
                        <a href="orderpage.php?product_id=<?= $row['id'] ?>" class="text-start text-dark d-block">
                            <img src="uploads/<?= $row['image'] ?>" class="w-100" alt="<?= $row['name'] ?>">
                            <h6 class="mb-0 mt-2"><?= $row['name'] ?></h6>
                            <p class="mb-0">
                                <span class="text-muted text-decoration-line-through">
                                    Rp <?= number_format($row['price'], 0, ',', '.') ?>
                                </span><br>
                                <span class="fw-bold">
                                    Rp <?= number_format($row['price_after_discount'], 0, ',', '.') ?>
                                </span><br>
                                <small class="text-danger"><?= $row['discount'] * 100 ?>% OFF</small>
                            </p>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <section class="product-section" id="product">
        <div class="container py-5">
            <ul class="nav nav-tabs justify-content-center mb-4" id="produkTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="men-tab" data-bs-toggle="tab" data-bs-target="#men"
                        type="button" role="tab" aria-controls="men" aria-selected="true">
                        Men
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="women-tab" data-bs-toggle="tab" data-bs-target="#women" type="button"
                        role="tab" aria-controls="women" aria-selected="false">
                        Women
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="produkTabContent">
                <div class="tab-pane fade show active" id="men" role="tabpanel" aria-labelledby="men-tab">
                    <div class="row g-4 mt-3 justify-content-center gap-3">
                        <?php while ($row = mysqli_fetch_assoc($result_men)):
                            ?>
                            <div class="col-6 col-md-3 col-lg-2 product-container">
                                <a href="orderpage.php?product_id=<?= $row['id'] ?>" class="text-start text-dark d-block">
                                    <?php if (!empty($row['image']) && file_exists("uploads/" . $row['image'])): ?>
                                        <img src="uploads/<?php echo $row['image']; ?>" class="w-100" alt="">
                                    <?php else: ?>
                                        <img src="assets/product-image.png" class="w-100" alt="">
                                    <?php endif; ?>
                                    <h6 class="mb-0 mt-2"><?php echo htmlspecialchars($row['name']); ?></h6>
                                    <p>Rp <?= number_format($row['price_after_discount'], 0, ',', '.') ?></p>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="women" role="tabpanel" aria-labelledby="women-tab">
                    <div class="row g-4 mt-3 justify-content-center gap-3">
                        <?php while ($row = mysqli_fetch_assoc($result_women)):
                            ?>
                            <div class="col-6 col-md-3 col-lg-2 product-container">
                                <a href="orderpage.php?product_id=<?= $row['id'] ?>" class="text-start text-dark d-block">
                                    <?php if (!empty($row['image']) && file_exists("uploads/" . $row['image'])): ?>
                                        <img src="uploads/<?php echo $row['image']; ?>" class="w-100" alt="">
                                    <?php else: ?>
                                        <img src="uploads/product-image.png" class="w-100" alt="">
                                    <?php endif; ?>
                                    <h6 class="mb-0 mt-2"><?php echo htmlspecialchars($row['name']); ?></h6>
                                    <p>Rp <?= number_format($row['price_after_discount'], 0, ',', '.'); ?></p>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer id="footer">
        <div class="container py-5">
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