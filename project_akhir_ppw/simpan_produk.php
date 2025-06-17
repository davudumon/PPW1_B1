<?php
include_once("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $diskon = $_POST['diskon'];
    $gender = $_POST['gender'];

    if (
        !isset($nama, $harga, $stok, $diskon, $gender) ||
        trim($nama) === '' || trim($gender) === '' ||
        !isset($_FILES['gambar']) || $_FILES['gambar']['name'] === ''
    ) {
        die("Semua field wajib diisi.");
    }

    $upload_result = uploadFile($_FILES['gambar'], "uploads/");

    if (!$upload_result['success']) {
        die("Upload gagal: " . $upload_result['message']);
    }

    $gambar_name = $upload_result['filename'];

    $sql = "CALL tambah_produk(?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssidds", $nama, $gambar_name, $harga, $stok, $diskon, $gender);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Gagal menyimpan produk: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>