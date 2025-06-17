<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT o.id AS order_id, o.order_date, p.name, p.image, p.price, p.discount, oi.size, oi.quantity, oi.price_at_order, (oi.price_at_order * oi.quantity) AS total_price
FROM orders o
JOIN order_items oi ON o.id = oi.order_id
JOIN products p ON oi.product_id = p.id
WHERE o.user_id = ?
ORDER BY o.order_date DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
foreach ($result as $row) {
    $orders[$row['order_id']]['order_date'] = $row['order_date'];
    $orders[$row['order_id']]['items'][] = $row;
}

$all_orders_total = 0;
foreach ($orders as $order) {
    foreach ($order['items'] as $item) {
        $all_orders_total += $item['total_price'];
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CAV | History Pesanan</title>
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
        .button-container button {
            min-width: 200px;
            padding: 12px 25px;
            border: none;
            background-color: #f2e6e6;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .button-container button:hover {
            background-color: #e6d6d6;
        }

        @media (max-width: 576px) {

            .table thead th:nth-child(3),
            .table tbody td:nth-child(3) {
                display: none;
            }

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
            <h5 class="fw-bold m-0">History Pesanan</h5>
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
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="5" class="text-center">Belum ada pesanan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order_id => $order): ?>
                            <tr>
                                <td colspan="5" class="fw-bold bg-light">
                                    Tanggal: <?= date("d-m-Y H:i", strtotime($order['order_date'])) ?>
                                </td>
                            </tr>
                            <?php
                            $grand_total = 0;
                            foreach ($order['items'] as $item):
                                $grand_total += $item['total_price'];
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="uploads/<?= htmlspecialchars($item['image']) ?>"
                                                alt="<?= htmlspecialchars($item['name']) ?>" class="product-img me-3" />
                                            <div>
                                                <div class="product-title"><?= htmlspecialchars($item['name']) ?></div>
                                                <div class="product-size">Size: <?= htmlspecialchars($item['size']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>Rp <?= number_format($item['price_at_order'], 0, ',', '.') ?></strong>
                                        <?php if ($item['discount'] > 0): ?>
                                            <div class="text-muted small text-decoration-line-through">
                                                Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $item['discount'] * 100 ?>%</td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td><strong>Rp <?= number_format($item['total_price'], 0, ',', '.') ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total Pesanan:</td>
                                <td class="fw-bold">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end border-top" style="padding-right: 1rem">
            <div class="total-section text-center text-md-end w-100 w-md-auto" style="padding: 0 1rem">
                <h6 class="mt-5">Grand Total: <span class="ms-2">Rp
                        <?= number_format($all_orders_total, 0, ',', '.') ?></span></h6>
            </div>
        </div>
    </div>
</body>

</html>