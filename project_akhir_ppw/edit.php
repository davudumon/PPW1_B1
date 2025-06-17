<?php
include_once("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = $_POST['id'];
    $nama   = $_POST['nama'];
    $harga  = $_POST['harga'];
    $stok   = $_POST['stok'];
    $diskon = $_POST['diskon'];
    $gender = $_POST['gender'];
    $gambar_lama = $_POST['gambar_lama'];

    // Cek apakah user upload gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $upload_result = uploadFile($_FILES['gambar'], "uploads/");
        if (!$upload_result['success']) {
            die("Upload gagal: " . $upload_result['message']);
        }
        $gambar = $upload_result['filename'];
    } else {
        $gambar = $gambar_lama;
    }


    $stmt = $conn->prepare("UPDATE products SET name=?, image=?, price=?, stock=?, discount=?, gender=? WHERE id=?");
    $stmt->bind_param("ssdidsi", $nama, $gambar, $harga, $stok, $diskon, $gender, $id);
    $stmt->execute();


    header("Location: dashboard.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        die("Produk tidak ditemukan.");
    }
} else {
    die("ID produk tidak diberikan.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CAV | Edit Produk</title>
    <link rel="icon" type="image/png" href="assets/logo.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .form-wrapper {
            background-color: #f9e6e6;
            border: 1px solid #800000;
            border-radius: 10px;
            padding: 25px;
            width: 100%;
            max-width: 600px;
            margin: auto;
            box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2);
        }

        .form-wrapper h4 {
            font-weight: bold;
            border-bottom: 1px solid #800000;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .btn-simpan {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        .btn-simpan:hover {
            background-color: #449d48;
        }

        @media (max-width: 576px) {
            .form-wrapper {
                padding: 20px;
                margin: 0 15px;
                width: 75%;
                height: 75%;
            }

            nav {
                padding: 1.5rem !important;
            }
        }
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>

    <div class="container d-flex flex-column align-items-center">
        <nav class="d-flex justify-content-between align-items-center p-5 w-100">
            <div class="nav-item">
                <a href="javascript:history.back()"><img src="assets/back.svg" alt="Back" /></a>
            </div>
            <div class="nav-item text-center">
                <h5 class="fw-bold">EDIT PRODUK</h5>
            </div>
            <div class="nav-item" style="width: 22px;"></div>
        </nav>

        <div class="form-wrapper mt-2">
            <h4>Edit Produk</h4>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="nama" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" name="nama" id="nama"
                            value="<?= htmlspecialchars($product['name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="gambar" class="form-label">Gambar</label>
                        <input type="file" class="form-control" name="gambar" id="gambar" accept="image/*">
                        <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($product['image']) ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="harga" class="form-label">Harga (IDR)</label>
                        <input type="number" class="form-control" name="harga" id="harga"
                            value="<?= $product['price'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" name="stok" id="stok"
                            value="<?= $product['stock'] ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="tipe" class="form-label">Diskon</label>
                        <input type="text" class="form-control" name="diskon" id="diskon" value="<?= htmlspecialchars($product['discount']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" name="gender" id="gender" required>
                            <option value="">Pilih Gender</option>
                            <option value="men">Men</option>
                            <option value="women">Women</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-simpan">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
