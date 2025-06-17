<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Silakan login terlebih dahulu!";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_id = $_POST['cart_id'] ?? null;

if (!$cart_id) {
    $_SESSION['error'] = "Data tidak valid.";
    header("Location: cart.php");
    exit;
}

// Hapus item dari keranjang berdasarkan cart_id dan user_id
$stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $cart_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION['success'] = "Item berhasil dihapus dari keranjang.";
} else {
    $_SESSION['error'] = "Item tidak ditemukan atau gagal dihapus.";
}

header("Location: cart.php");
exit;
?>
