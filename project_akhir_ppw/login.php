<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAV | Login Page</title>
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
    <?php
    if (isset($_SESSION['error'])):
        ?>
        <div class="alert alert-danger" role="alert">
            <?= $_SESSION['error']; ?>
        </div>
        <?php
        unset($_SESSION['error']);
    endif;
    ?>
    <div class="d-flex flex-column register-container py-5 px-2 rounded-5 justify-content-evenly gap-4">
        <img src="assets/logo.svg" alt="">
        <form method="POST" class="d-flex flex-column text-white gap-3 form-container">
            <div class="d-flex flex-column username-input">
                <p class="m-1">Username / Email</p>
                <input type="text" name="username" id="username" class="form-control rounded-3 background-input">
            </div>
            <div class="d-flex flex-column password-input">
                <p class="m-1">Password</p>
                <input type="password" name="password" id="password" class="form-control rounded-3 background-input">
            </div>
            <div class="submit align-self-end me-5 mt-3">
                <button type="submit" name="submit" value="Login" class="btn btn-custom">Login</button>
            </div>
        </form>
        <?php
        include_once("config.php");

        if (isLoggedIn()) {
            header("Location: index.php");
            exit();
        }

        if (isset($_POST['submit'])) {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = $_POST['password'];

            $query = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Username atau password salah!";
                }
            } else {
                $error = "Username atau password salah!";
            }
        }
        ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
    </div>
    <div class="change mt-3">
        <p>Belum Memiliki Akun? <a href="register.php">Register</a></p>
    </div>
</body>

</html>