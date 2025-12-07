<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $username = trim($_POST['user'] ?? '');
    $password = $_POST['pass'] ?? '';



    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    if (!preg_match('/^[a-zA-Z0-9_.]+$/', $username)) {
        echo json_encode([
            'success' => false,
            'message' => 'Username can only contain letters, numbers, and underscore. No spaces or special characters allowed.'
        ]);
        exit;
    }

    include 'session_starter.php';
    require_once "./db_conn.php";

    $check = $pdo->prepare("SELECT * FROM `login` WHERE `user`=?");
    $check->execute([$username]);
    $user = $check->fetch();

    if (!$user || !password_verify($password, $user['passid'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
        exit;
    }
    $uid = $user['uid'];
    $_SESSION['pfp'] = $user['pfp'] ?: "default.jpg";
    $_SESSION['login'] = true;
    $_SESSION['user'] = $username;
    $_SESSION['uid'] = $uid;

    echo json_encode(['success' => true]);
    exit;
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .login-container {
            background: #333;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-container form input {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .login-container form button {
            width: 100%;
            padding: 12px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
        }

        .login-container form button:hover {
            background-color: #444;
        }

        .error {
            color: red;
            font-size: 0.9rem;
            margin-bottom: 10px;
            text-align: center;
        }

        .warning {
            color: #c0392b;
            font-size: 0.85rem;
            margin: -15px 0 10px 0;
        }
    </style>
</head>

<body>
    <?php
    include 'header.php';
    ?>
    <div class="login-container">
        <h2>Log into your account</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="" onsubmit="return validatePassword();" id="loginForm">
            <input type="text" name="user" placeholder="Username" required>

            <input type="password" id="password" name="pass" placeholder="Password" required>
            <div id="password-msg" class="warning"></div>

            <button type="submit">Login</button>
        </form>
        <!-- Toast -->
        <div id="toast" class="toast"></div>
    </div>
    <script>
        function validatePassword() {
            const pwd = document.getElementById('pass').value;
            const pwdMsg = document.getElementById('password-msg');

            pwdMsg.textContent = '';

            return true;
        }
    </script>

    <script src="script.js"></script>

    <script>
        document.getElementById("loginForm").addEventListener("submit", async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            const password = formData.get("password");

            try {
                const response = await fetch("login.php", {
                    method: "POST",
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    form.reset();
                    startRedirectCountdown(5, "index.php");
                    showToast("Login successful! Redirecting in 5 seconds...");
                } else {
                    showToast(result.message, true);
                }

            } catch (err) {
                showToast("Something went wrong.", true);
            }
        });
    </script>

</body>

</html>