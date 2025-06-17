<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Silakan login terlebih dahulu!";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$grand_total = 0;

$query = "SELECT * FROM view_user_cart WHERE user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $grand_total += $row['total_price'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CAV | Keranjang </title>
    <link rel="icon" type="image/png" href="assets/logo.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: Inter, sans-serif;
        }

        .nav-item img {
            width: 30px;
        }

        .product-img {
            width: 80px;
            height: auto;
        }

        .product-title {
            font-weight: bold;
        }

        .product-size {
            font-size: 0.875rem;
            color: #555;
        }

        .total-section {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .no-row-border tbody td {
            border-bottom: none !important;
        }

        /* Tombol responsif */
        .button-form button {
            min-width: 200px;
            padding: 12px 25px;
            border: none;
            background-color: #f2e6e6;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .button-form button:hover {
            background-color: #e6d6d6;
        }

        /* Responsive Table: sembunyikan kolom Diskon & Jumlah di layar kecil */
        @media (max-width: 576px) {

            .table thead th:nth-child(3),
            .table tbody td:nth-child(3) {
                display: none;
            }

            /* Agar kolom Produk bisa lebih lebar di layar kecil */
            .product-img {
                width: 60px;
            }
        }
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>

    <nav class="d-flex justify-content-between align-items-center p-4">
        <div class="nav-item">
            <a href="javascript:history.back()"><img src="assets/back.svg" alt="Home" /></a>
        </div>
        <div class="nav-item text-center flex-grow-1">
            <h5 class="fw-bold m-0">Keranjang</h5>
        </div>
        <div class="nav-item" style="width: 30px;"></div>
    </nav>

<div class="container mt-4">
    <div class="table-responsive">
        <table class="table align-middle no-row-border">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Diskon</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($cart_items) === 0): ?>
                    <tr>
                        <td colspan="6" class="text-center">Keranjang kosong</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="uploads/<?= htmlspecialchars($item['image']) ?>" 
                                         alt="<?= htmlspecialchars($item['name']) ?>" class="product-img me-3" />
                                    <div>
                                        <div class="product-title"><?= htmlspecialchars($item['name']) ?></div>
                                        <div class="product-size">Size : <?= htmlspecialchars($item['size']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>Rp <?= number_format($item['price_after_discount'], 0, ',', '.') ?></strong>
                                <?php if ($item['discount'] > 0): ?>
                                    <div class="text-muted small text-decoration-line-through">
                                        Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= $item['discount'] * 100 ?>%</td>
                            <td><?= $item['quantity'] ?></td>
                            <td><strong>Rp <?= number_format($item['total_price'], 0, ',', '.') ?></strong></td>
                            <td>
                                <form action="hapus_cart.php" method="post" onsubmit="return confirm('Yakin ingin menghapus item ini dari keranjang?')">
                                    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end border-top" style="padding-right: 1rem">
        <div class="total-section text-center text-md-end w-100 w-md-auto" style="padding: 0 1rem">
            <h6 class="mt-5">Grand Total: <span class="ms-2">Rp <?= number_format($grand_total, 0, ',', '.') ?></span></h6>
        </div>
    </div>
    
    <?php if (count($cart_items) > 0): ?>
    <form action="checkout.php" method="post" 
          class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-3 mt-4 mb-5 button-form">
        <?php foreach ($cart_items as $index => $item): ?>
            <input type="hidden" name="products[<?= $index ?>][product_id]" value="<?= $item['product_id'] ?>">
            <input type="hidden" name="products[<?= $index ?>][size]" value="<?= htmlspecialchars($item['size']) ?>">
            <input type="hidden" name="products[<?= $index ?>][quantity]" value="<?= $item['quantity'] ?>">
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Checkout</button>
    </form>
    <?php endif; ?>
</div>

</html>