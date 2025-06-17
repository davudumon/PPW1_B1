<?php
include_once("config.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data dari form POST
$product_id = $_POST['product_id'] ?? null;
$size = $_POST['size'] ?? null;
$quantity = intval($_POST['quantity'] ?? 1);

// Validasi sederhana
if (!$product_id || $quantity < 1) {
    // Bisa redirect dengan error atau tampilkan pesan
    die('Data tidak valid');
}

if (empty($_POST['size'])) {
    $_SESSION['error'] = "Ukuran harus dipilih!";
    // Redirect kembali ke orderpage dengan product_id supaya error muncul
    header("Location: orderpage.php?product_id=" . $_POST['product_id']);
    exit;
}

// Cek apakah produk dengan ukuran sama sudah ada di cart
$sqlCheck = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ?";
$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("iis", $user_id, $product_id, $size);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update quantity
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + $quantity;

    $sqlUpdate = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ii", $new_quantity, $row['id']);
    $stmtUpdate->execute();
} else {
    // Insert baru ke cart
    $sqlInsert = "INSERT INTO cart (user_id, product_id, quantity, size) VALUES (?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("iiis", $user_id, $product_id, $quantity, $size);
    $stmtInsert->execute();
}

// Redirect ke halaman cart atau produk
header("Location: cart.php");
exit;
?>