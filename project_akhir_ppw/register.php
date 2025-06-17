<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAV | Register Page</title>
    <link rel="icon" type="image/png" href="assets/logo.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #800000;
            font-family: 'Inter', sans-serif;
        }

        .register-container {
            background-color: #800000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: auto;
            width: 400px;
        }

        .background-input {
            background-color: #F2E6E6;
        }

        .form-container {
            width: 80%;
        }

        .color-custom {
            color: #800000;
        }

        .btn-custom {
            background-color: #F2E6E6;
            color: #800000;
        }

        .btn-custom:hover {
            background-color: #F2E6E6;
            color: #800000;
        }

        input.form-control {
            background-color: #F2E6E6;
            /* warna default */
            color: #333;
            border: 1px solid #ccc;
        }

        input.form-control:focus {
            background-color: #F2E6E6;
            color: #333;
            border-color: #aaa;
            box-shadow: none;
        }

        @media(max-width: 480px) {
            .register-container {
                width: 80%;
            }
        }
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
    <div class="d-flex flex-column register-container py-5 px-2 rounded-5 justify-content-evenly gap-4">
        <img src="assets/logo.svg" alt="">
        <form method="POST" class="d-flex flex-column text-white gap-3 form-container" autocomplete="off">
            <div class="d-flex flex-column username-input">
                <p class="m-1">Username</p>
                <input type="text" name="username" id="username" class="form-control rounded-3 background-input">
            </div>
            <div class="d-flex flex-column email-input">
                <p class="m-1">Email</p>
                <input type="email" name="email" id="email" class="form-control rounded-3 background-input">
            </div>
            <div class="d-flex flex-column password-input">
                <p class="m-1">Password</p>
                <input type="password" name="password" id="password" class="form-control rounded-3 background-input">
            </div>
            <div>
                <?php
                include_once("config.php");

                if (isLoggedIn()) {
                    header("Location: index.php");
                    exit();
                }

                if (isset($_POST['submit'])) {
                    $username = mysqli_real_escape_string($conn, $_POST['username']);
                    $email = mysqli_real_escape_string($conn, $_POST['email']);
                    $password = $_POST['password'];

                    $errors = array();

                    // Validasi
                    if (empty($username)) {
                        $errors[] = "Username tidak boleh kosong";
                    }

                    if (empty($email)) {
                        $errors[] = "Email tidak boleh kosong";
                    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "Format email tidak valid";
                    }


                    if (empty($password)) {
                        $errors[] = "Password tidak boleh kosong";
                    } elseif (strlen($password) < 6) {
                        $errors[] = "Password minimal 6 karakter";
                    }

                    $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
                    $check_result = mysqli_query($conn, $check_query);

                    if (mysqli_num_rows($check_result) > 0) {
                        $errors[] = "Username atau email sudah terdaftar";
                    }

                    if (empty($errors)) {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $insert_query = "INSERT INTO users (username, email, password) 
                               VALUES ('$username', '$email', '$hashed_password')";

                        if (mysqli_query($conn, $insert_query)) {
                            $success = "Registrasi berhasil! Silakan login.";
                            header("Location: login.php");
                        } else {
                            $errors[] = "Error: " . mysqli_error($conn);
                        }
                    }
                }
                ?>

                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul style="margin: 0; padding-left: 20px;">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
            </div>
            <div class="submit align-self-end me-5 mt-3">
                <button type="submit" name="submit" value="Register" class="btn btn-custom">Register</button>
            </div>
        </form>

    </div>
    <div class="change mt-3">
        <p>Sudah Memiliki Akun? <a href="login.php">Masuk</a></p>
    </div>
</body>

</html>