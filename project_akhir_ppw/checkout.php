<?php
include_once("config.php");

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Anda harus login terlebih dahulu untuk checkout.";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$products = $_POST['products'] ?? [];

if (empty($products)) {
    $_SESSION['error'] = "Tidak ada produk untuk checkout.";
    header("Location: history.php");
    exit;
}

// Mulai transaksi (agar jika error, rollback)
$conn->begin_transaction();

try {
    // Insert ke tabel orders
    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date) VALUES (?, NOW())");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_id = $conn->insert_id;

    // Siapkan statement untuk cek stok dan insert order_items
    $stmt_check = $conn->prepare("SELECT price_after_discount, stock FROM products WHERE id = ?");
    $stmt_insert = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, size, price_at_order) VALUES (?, ?, ?, ?, ?)");
    $stmt_update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

    foreach ($products as $product) {
        $product_id = (int)$product['product_id'];
        $size = $product['size'];
        $quantity = (int)$product['quantity'];

        if (!$product_id || !$size || $quantity <= 0) {
            throw new Exception("Data produk tidak valid.");
        }

        // Cek produk dan stok
        $stmt_check->bind_param("i", $product_id);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        $prod = $result->fetch_assoc();

        if (!$prod) {
            throw new Exception("Produk dengan ID $product_id tidak ditemukan.");
        }
        if ($prod['stock'] < $quantity) {
            throw new Exception("Stok produk dengan ID $product_id tidak mencukupi.");
        }

        $price_per_item = $prod['price_after_discount'];

        // Insert ke order_items
        $stmt_insert->bind_param("iiisd", $order_id, $product_id, $quantity, $size, $price_per_item);
        $stmt_insert->execute();

        // Update stok
        $stmt_update_stock->bind_param("ii", $quantity, $product_id);
        $stmt_update_stock->execute();
    }

    $conn->commit();

    // Kosongkan keranjang user (optional)
    $stmt_delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt_delete_cart->bind_param("i", $user_id);
    $stmt_delete_cart->execute();

    header("Location: history.php");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = $e->getMessage();
    header("Location: history.php");
    exit;
}
