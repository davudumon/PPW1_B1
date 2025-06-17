<?php
include_once("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = false;

// Ambil data user
$query = "SELECT * FROM users WHERE id = $user_id LIMIT 1";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    die("User tidak ditemukan");
}
$user = mysqli_fetch_assoc($result);

// HANDLE EDIT PROFILE
if (isset($_POST['submit_profile'])) {
    $new_username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $new_email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $new_password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($new_username)) $errors[] = "Username tidak boleh kosong.";
    if (empty($new_email) || !filter_var($new_email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Email tidak valid.";

    $fields = ['username = ?', 'email = ?'];
    $params = [$new_username, $new_email];
    $types = 'ss';

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $fields[] = 'password = ?';
        $params[] = $hashed_password;
        $types .= 's';
    }

    $params[] = $user_id;
    $types .= 'i';

    if (count($errors) === 0) {
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            mysqli_stmt_execute($stmt);
            if (mysqli_stmt_affected_rows($stmt) >= 0) {
                $success = true;
                $_SESSION['username'] = $new_username;
                $user['username'] = $new_username;
                $user['email'] = $new_email;
                if (!empty($new_password)) {
                    $user['password'] = $hashed_password;
                }
            } else {
                $errors[] = "Tidak ada perubahan data atau gagal update.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Query gagal: " . mysqli_error($conn);
        }
    }
}

// HANDLE UPLOAD FOTO
if (isset($_POST['submit_photo'])) {
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_name = basename($_FILES['photo']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_ext, $allowed)) {
            $errors[] = "Format foto harus JPG, JPEG, PNG, atau GIF.";
        } else {
            $new_file_name = "user_" . $user_id . "_" . time() . "." . $file_ext;
            $upload_dir = "uploads/";
            $upload_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Hapus foto lama jika ada
                if (!empty($user['photo']) && file_exists($upload_dir . $user['photo'])) {
                    unlink($upload_dir . $user['photo']);
                }

                // Simpan nama file baru ke database
                $sql = "UPDATE users SET photo = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "si", $new_file_name, $user_id);
                mysqli_stmt_execute($stmt);
                if (mysqli_stmt_affected_rows($stmt) >= 0) {
                    $user['photo'] = $new_file_name;
                    $success = true;
                } else {
                    $errors[] = "Gagal menyimpan foto ke database.";
                }
                mysqli_stmt_close($stmt);
            } else {
                $errors[] = "Gagal mengupload foto.";
            }
        }
    } else {
        $errors[] = "Tidak ada file yang dipilih.";
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAV | Edit Profile</title>
    <link rel="icon" type="image/png" href="assets/logo.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .photo-container {
            width: 250px;
            height: 250px;
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

        .background-input {
            background-color: #F2E6E6;
        }

        .btn-custom {
            background-color: #F2E6E6;
            color: #800000;
        }

        .btn-custom:hover {
            background-color: #800000;
            color: #fff;
        }

        @media (max-width: 576px) {
            .photo-container {
                width: 180px;
                height: 180px;
            }
        }
    </style>
</head>

<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<nav class="d-flex justify-content-between align-items-center p-3">
    <div class="nav-item">
        <a href="javascript:history.back()"><img src="assets/back.svg" alt="Back"></a>
    </div>
    <div class="nav-item text-center flex-grow-1">
        <h5 class="fw-bold mb-0">EDIT PROFILE</h5>
    </div>
    <div class="nav-item" style="width: 24px;"></div>
</nav>

<div class="container my-5">
    <div class="row justify-content-center align-items-center g-4">
        <div class="col-lg-4 col-md-5 col-sm-8 text-center">
            <div class="photo-container">
                <img src="uploads/<?= htmlspecialchars($user['photo'] ?: 'photo-profile.svg') ?>" alt="Foto Profil">
            </div>
            <form action="" method="post" enctype="multipart/form-data" class="mt-3" style="max-width: 70%; margin: auto;">
                <input type="file" name="photo" accept="image/*" class="form-control">
                <button type="submit" name="submit_photo" class="btn btn-secondary mt-3">Upload Foto</button>
            </form>
        </div>

        <div class="col-lg-6 col-md-7 col-sm-10">
            <?php if ($success): ?>
                <div class="alert alert-success">Berhasil diperbarui!</div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="" method="post" class="d-flex flex-column gap-3" autocomplete="off">
                <div>
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control background-input"
                        value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control background-input"
                        value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div>
                    <label for="password" class="form-label">Password <small>(kosongkan jika tidak ingin diubah)</small></label>
                    <input type="password" name="password" id="password" class="form-control background-input"
                        placeholder="********">
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <input type="submit" name="submit_profile" value="Edit" class="btn btn-success px-4">
                </div>
            </form>
        </div>
    </div>
</div>
</body>

</html>
