<?php
include 'config.php';

if (!isset($_POST['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_POST['id'];

// Ambil nama file gambar untuk dihapus
$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Produk tidak ditemukan.";
    exit;
}

if (!empty($product['image']) && file_exists("uploads/" . $product['image'])) {
    unlink("uploads/" . $product['image']);
}

$stmt = $conn->prepare("DELETE FROM order_items WHERE product_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    echo "Error hapus produk: " . $stmt->error;
    exit;
}

header("Location: dashboard.php");
exit;
?>
