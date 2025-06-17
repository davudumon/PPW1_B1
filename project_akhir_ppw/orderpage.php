<?php
include_once("config.php");

if (!isset($_SESSION['user_id'])) {
  $_SESSION['error'] = "Silakan login terlebih dahulu untuk mengakses produk.";
  header("Location: login.php");
  exit();
}

$error = null;
if (isset($_SESSION['error'])) {
  $error = $_SESSION['error'];
  unset($_SESSION['error']);
}

$product_id = isset($_GET['product_id']) ? (int) $_GET['product_id'] : 0;

// ambil data produk dari database
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($product_id <= 0 || !$product) {
  $_SESSION['error'] = "Produk tidak ditemukan.";
  header("Location: product.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CAV | Order Page - <?= htmlspecialchars($product['name']) ?></title>
  <link rel="icon" type="image/png" href="assets/logo.svg">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    a {
      text-decoration: none;
      color: black;
    }

    nav a {
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

    .custom-radio {
      background-color: #f2e6e6;
      width: 30px;
      height: 30px;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 4px;
      cursor: pointer;
      box-sizing: border-box;
      transition: 0.2s ease-in-out;
    }

    .custom-radio input[type="radio"] {
      display: none;
    }

    .custom-radio span {
      font-size: 14px;
      font-weight: 500;
      color: #333;
      display: inline-block;
      line-height: 1;
    }

    .custom-radio input[type="radio"]:checked+span {
      color: #f2e6e6;
    }

    .custom-radio input[type="radio"]:checked+span {
      background-color: transparent;
    }

    .custom-radio:hover,
    .custom-radio:hover span {
      background-color: #800000;
      color: #f2e6e6;
    }

    .custom-radio input[type="radio"]:checked~span,
    .custom-radio:hover span {
      background-color: transparent;
    }

    .custom-radio input[type="radio"]:checked+span,
    .custom-radio:has(input[type="radio"]:checked) {
      background-color: #800000;
    }

    .counter img {
      width: 30px;
    }

    .button-container button {
      background-color: #800000;
      color: #f2e6e6;
      border-radius: 0;
      min-width: 150px;
      min-height: 50px;
    }

    .button-container button:hover {
      background-color: #f2e6e6;
      color: #800000;
    }

    .button-container a {
      display: inline-block;
      margin: 5px;
    }

    @media (max-width: 768px) {
      .right-form {
        margin-left: 50px !important;
      }
    }

    @media (max-width: 576px) {
      .button-container {
        display: flex;
        flex-direction: column;
        align-items: center;
      }

      .right-form {
        margin-left: 75px !important;
      }
    }
  </style>
</head>

<body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

  <!-- NAVBAR -->
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

  <!-- MAIN CONTENT -->
  <section class="pt-5" style="margin-top: 100px;">
    <div class="container">
      <div class="row align-items-center justify-content-center g-5">
        <!-- LEFT: IMAGE -->
        <div class="col-lg-6 col-md-12 text-center">
          <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"
            class="img-fluid" />
        </div>

        <!-- RIGHT: DETAIL -->
        <div class="col-lg-6 col-md-12 d-flex flex-column p-0 align-items-start gap-4 right-form">
          <div class="product-info">
            <h6><?= htmlspecialchars($product['name']) ?></h6>
            <h6>Rp <?= number_format($product['price_after_discount'], 0, ',', '.') ?></h6>
            <h6>Stok: <?= $product['stock'] ?></h6>
          </div>

          <!-- Form untuk Add to Cart -->
          <form action="add_to_cart.php" method="POST" class="w-100 d-flex flex-column gap-4" id="cart-form">
            <div class="size-container">
              <h6>Pilih Ukuran</h6>
              <div class="size-wrapper d-flex flex-row gap-3 text-center" role="radiogroup" aria-label="Pilih Ukuran">
                <label class="custom-radio"><input type="radio" name="size" value="S" required /><span>S</span></label>
                <label class="custom-radio"><input type="radio" name="size" value="M" /><span>M</span></label>
                <label class="custom-radio"><input type="radio" name="size" value="L" /><span>L</span></label>
                <label class="custom-radio"><input type="radio" name="size" value="XL" /><span>XL</span></label>
              </div>
            </div>

            <div class="quantity-container">
              <h6>Kuantitas</h6>
              <div class="counter d-flex flex-row align-items-center gap-2">
                <a href="#" id="btn-minus"><img src="assets/minus.svg" alt="Minus" /></a>
                <h5 class="mb-0" id="quantity">1</h5>
                <a href="#" id="btn-plus"><img src="assets/plus.svg" alt="Plus" /></a>
              </div>
            </div>

            <input type="hidden" name="product_id" value="<?= $product['id'] ?>" />
            <input type="hidden" name="quantity" id="quantity-input" value="1" />
            <input type="hidden" name="action" value="add_to_cart" />

            <div class="button-container d-flex flex-row justify-content-lg-start text-center gap-3 mb-5  ">
              <button type="submit" class="btn" id="cart-btn">+ Keranjang</button>
              <button type="button" class="btn" id="checkout-btn">Checkout</button>
            </div>
          </form>

          <!-- Form tersembunyi untuk Checkout -->
          <form action="checkout.php" method="POST" id="checkout-form" style="display: none;">
            <input type="hidden" name="products[0][product_id]" value="<?= $product['id'] ?>" />
            <input type="hidden" name="products[0][size]" id="checkout-size" />
            <input type="hidden" name="products[0][quantity]" id="checkout-quantity" value="1" />
          </form>

          <script>
            document.addEventListener('DOMContentLoaded', function () {
              const checkoutBtn = document.getElementById('checkout-btn');
              const cartBtn = document.getElementById('cart-btn');
              const cartForm = document.getElementById('cart-form');
              const checkoutForm = document.getElementById('checkout-form');
              const quantityDisplay = document.getElementById('quantity');
              const quantityInput = document.getElementById('quantity-input');
              const checkoutQuantity = document.getElementById('checkout-quantity');
              const checkoutSize = document.getElementById('checkout-size');
              const btnMinus = document.getElementById('btn-minus');
              const btnPlus = document.getElementById('btn-plus');

              let quantity = 1;

              function updateQuantity() {
                quantityDisplay.textContent = quantity;
                quantityInput.value = quantity;
                checkoutQuantity.value = quantity;
              }

              // Quantity buttons
              btnMinus.addEventListener('click', function (e) {
                e.preventDefault();
                if (quantity > 1) {
                  quantity--;
                  updateQuantity();
                }
              });

              btnPlus.addEventListener('click', function (e) {
                e.preventDefault();
                quantity++;
                updateQuantity();
              });

              // Tombol Checkout
              checkoutBtn.addEventListener('click', function (e) {
                const selectedSize = cartForm.querySelector('input[name="size"]:checked');
                if (!selectedSize) {
                  alert('Silakan pilih ukuran terlebih dahulu!');
                  return;
                }

                checkoutSize.value = selectedSize.value;
                checkoutForm.submit();
              });

              // Tombol + Keranjang
              cartBtn.addEventListener('click', function (e) {
                const selectedSize = cartForm.querySelector('input[name="size"]:checked');
                if (!selectedSize) {
                  alert('Silakan pilih ukuran terlebih dahulu!');
                  return;
                }

                checkoutSize.value = selectedSize.value;
                cartForm.submit();
              });

              // Set awal
              updateQuantity();
            });
          </script>

</body>

</html>