<?php

include_once("config.php");

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Silakan login terlebih dahulu!";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user dari database
$query = "SELECT * FROM users WHERE id = $user_id LIMIT 1";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "User tidak ditemukan.";
    exit;
}

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CAV | Profile</title>
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

        .photo-profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        a {
            text-decoration: none;
        }

        .button-container button {
            background-color: #800000;
            color: #f2e6e6;
            min-width: 190px;
            padding: 12px 20px;
            font-weight: 600;
            border: none;
            transition: background-color 0.3s ease;
        }

        .button-container button:hover {
            background-color: #f2e6e6;
            color: #800000;
        }

        /* Layout container: row on lg and up, column on smaller */
        .profile-main-container {
            margin-left: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 992px) {
            .profile-main-container {
                flex-direction: row;
            }
        }

        @media (max-width: 991.98px) {
            .profile-main-container {
                flex-direction: column;
                align-items: center;
                text-align: center;
                margin-left: 0;
            }
        }

        .edit-container {
            cursor: pointer;
            color: #800000;
            font-weight: 500;
        }

        .edit-container img {
            width: 18px;
        }

        .logout-container img {
            width: 18px;
        }

        .photo-container {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background-color: #F2E6E6;
            margin: 0 auto;
            overflow: hidden;
        }

        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logout-container p,
        .edit-container p {
            margin-bottom: 0;
            font-weight: 500;
        }

        /* Button container stack on small screens */
        @media (max-width: 575.98px) {
            .button-container {
                flex-direction: column;
                gap: 1rem !important;
            }

            .button-container button {
                width: 100% !important;
                max-width: 320px;
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
            <a href="index.php"><img src="assets/home.svg" alt="Home" /></a>
        </div>
        <div class="nav-item text-center flex-grow-1">
            <h5 class="fw-bold m-0">Profile</h5>
        </div>
        <div class="nav-item" style="width: 30px;"></div>
    </nav>

    <div class="container d-flex profile-main-container gap-4 justify-content-center mx-auto">
        <div class="photo-profile-container d-flex flex-column align-items-center">
            <div class="photo-container">
                <img src="uploads/<?= htmlspecialchars($user['photo'] ?: 'photo-profile.svg') ?>" alt="Foto Profil">
            </div>
            <a href="editprofile.php">
                <div class="edit-container d-flex justify-content-center align-items-center gap-2 mt-2" role="button"
                    tabindex="0">
                    <img src="assets/edit.svg" alt="Edit" />
                    <p>Edit</p>
                </div>
            </a>
        </div>

        <div
            class="text-container d-flex flex-column gap-1 justify-content-center align-items-center align-items-xl-start">
            <h5><?= htmlspecialchars($user['username']) ?></h5>
            <p class="mb-2"><?= htmlspecialchars($user['email']) ?></p>
            <a href="logout.php">
                <div class="logout-container d-flex align-items-center gap-2 text-danger" role="button" tabindex="0">
                    <img src="assets/logout.svg" alt="Log Out" />
                    <p>Log Out</p>
                </div>
            </a>
        </div>
    </div>

    <div class="button-container d-flex justify-content-center align-items-center gap-4 mt-4 mb-5 flex-wrap">
        <a href="cart.php"><button class="btn">Lihat Keranjang</button></a>
        <a href="history.php"><button class="btn">Lihat Pesanan</button></a>
    </div>
</body>

</html>
