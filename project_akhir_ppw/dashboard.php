<?php
include 'config.php';

if (!isAdmin()) {
  header('Location: login.php');
  exit();
}

$limit = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$query = "SELECT * FROM products LIMIT $start, $limit";
$result = $conn->query($query);

$totalQuery = "SELECT COUNT(*) as total FROM products";
$totalResult = $conn->query($totalQuery);
$totalData = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalData / $limit);

$query_total_produk = "SELECT total_produk() AS total_produk";
$result_total_produk = $conn->query($query_total_produk);

$row_total_produk = $result_total_produk->fetch_assoc();
$total_produk = $row_total_produk["total_produk"];

$query_total_orderan = "SELECT total_orderan_semua_user() AS total_order";
$result_total_orderan = $conn->query($query_total_orderan);

$row_total_order = $result_total_orderan->fetch_assoc();
$total_orderan = $row_total_order["total_order"];

$query_total_user = "SELECT total_user() AS total_user";
$result_total_user = $conn->query($query_total_user);

$row_total_user = $result_total_user->fetch_assoc();
$total_user = $row_total_user["total_user"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CAV | Admin Dashboard</title>
  <link rel="icon" type="image/png" href="assets/logo.svg">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    .box {
      background-color: #f2e6e6;
      box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);
      width: 250px;
      min-height: 175px;
    }

    .logo-container {
      background-color: #800000;
    }

    .logo-container img {
      height: 40px;
    }

    .text-maroon {
      color: #800000;
    }

    a {
      text-decoration: none;
    }

    .button-container button {
      background-color: #f2e6e6;
      color: #800000;
      padding: 1rem 2rem;
      font-weight: 600;
      box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);
    }

    .actions-container img {
      height: 20px;
    }

    .actions-container button {
      width: 40px;
      height: 40px;
    }

    .pagination .page-item.active .page-link {
      background-color: #800000;
      border-color: #800000;
      color: white !important;
    }

    .pagination .page-link:hover {
      background-color: #660000;
      border-color: #660000;
      color: white !important;
    }

    @media (max-width: 768px) {
      .button-container {
        flex-direction: column;
        gap: 1rem !important;
      }

      .button-container button {
        width: 100%;
        max-width: 320px;
      }
    }

    .table-responsive {
      overflow-x: auto;
    }
  </style>
</head>

<body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
    crossorigin="anonymous"></script>

  <!-- Navbar -->
  <nav class="d-flex justify-content-between align-items-center p-4">
    <div class="nav-item">
      <a href="adminprofile.php"><img src="assets/back.svg" alt="Back" /></a>
    </div>
    <div class="nav-item text-center flex-grow-1">
      <h5 class="fw-bold m-0">ADMIN DASHBOARD</h5>
    </div>
    <div class="nav-item" style="width: 30px;"></div>
  </nav>

  <!-- Stat boxes -->
  <div
    class="container d-flex stats-container justify-content-center flex-sm-row flex-column gap-3 mt-3 align-items-center">
    <div class="box d-flex flex-column p-4 gap-3">
      <div class="top d-flex gap-4 align-items-center">
        <h5>Total Produk</h5>
        <div class="logo-container p-1 rounded-1">
          <img src="assets/box.svg" alt="Box" />
        </div>
      </div>
      <?php echo "<h1>$total_produk</h1>" ?>
    </div>
    <div class="box d-flex flex-column p-4 gap-3">
      <div class="top d-flex gap-4 align-items-center">
        <h5>Total Orders</h5>
        <div class="logo-container p-1 rounded-1">
          <img src="assets/box.svg" alt="Box" />
        </div>
      </div>
      <?php echo "<h1>$total_orderan</h1>" ?>
    </div>
    <div class="box d-flex flex-column p-4 gap-3">
      <div class="top d-flex gap-4 align-items-center">
        <h5>Total Users</h5>
        <div class="logo-container p-1 rounded-1">
          <img src="assets/box.svg" alt="Box" />
        </div>
      </div>
      <?php echo "<h1>$total_user</h1>"?>
    </div>
  </div>

  <!-- Table -->
  <div class="container mt-5">
    <div class="table-responsive shadow">
      <table class="table table-bordered text-center mb-0">
        <thead class="text-white" style="background-color: #800000;">
          <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Gambar</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Diskon</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = $start + 1;
          while ($row = $result->fetch_assoc()):
            ?>
            <tr class="text-maroon" style="background-color: #f9e6e6;">
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Gambar" width="50" /></td>
              <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
              <td><?= $row['stock'] ?></td>
              <td><?= $row['discount'] * 100 ?>%</td>
              <td>
                <div
                  class="actions-container d-flex justify-content-center align-items-center gap-2 flex-sm-row flex-column">
                  <a href="edit.php?id=<?= $row['id'] ?>"><button class="btn btn-sm"
                      style="background-color: #800000; color: white;">
                      <img src="assets/edit-white.svg" alt="Edit" />
                    </button></a>
                  <form action="hapus_produk.php" method="post"
                    onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn btn-sm" style="background-color: #800000; color: white;">
                      <img src="assets/trash.svg" alt="Delete" />
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <nav class="mt-4">
        <ul class="pagination justify-content-center text-white">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
              <a class="page-link text-black" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    </div>
  </div>

  <!-- Tambah Produk -->
  <div class="button-container d-flex justify-content-center align-items-center gap-5 mt-5 mb-5">
    <a href="tambah.php"><button class="btn fw-bold">Tambah Produk</button></a>
  </div>
</body>

</html>