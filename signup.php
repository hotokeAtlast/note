<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Content-Type: application/json');

  $username = trim($_POST['user'] ?? '');
  $password = $_POST['pass'] ?? '';
  $confirm  = $_POST['cpass'] ?? '';

  include 'session_starter.php';
  require_once "./db_conn.php";

  $stmt = $pdo->prepare("SELECT * FROM login WHERE user=?");
  $stmt->execute([$username]);

  $user = $stmt->fetch();

  if ($user) {
    echo json_encode(['success' => false, 'message' => 'User already exists...']);
    exit;
  }

  if (!preg_match('/^[a-zA-Z0-9_.]+$/', $username)) {
    echo json_encode([
      'success' => false,
      'message' => 'Username can only contain letters, numbers, and underscore. No spaces or special characters allowed.'
    ]);
    exit;
  }

  if (empty($username) || empty($password) || empty($confirm)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
  }

  $strongRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

  if (!preg_match($strongRegex, $password)) {
    echo json_encode([
      'success' => false,
      'message' => 'Password too weak. Use at least 8 characters with uppercase, lowercase, number, and special symbol.'
    ]);
    exit;
  }

  if ($password !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
    exit;
  }

  if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters.']);
    exit;
  }

  // Hash the password
  $hashed = password_hash($password, PASSWORD_DEFAULT);

  // adding the new entry into database,

  $uid = random_int(111111, 999999);
  $new_column = $pdo->prepare("INSERT INTO login (`sno`, `uid`, `user`, `passid`, `phase`, `date`, `time`) VAlUES (NULL, :uid, :user, :passid, '0', :date, :time)");
  $new_column->execute([
    ':uid' => $uid,
    ':user' => trim($_POST['user'] ?? ''),
    ':passid' => $hashed,
    ':date' => $date,
    ':time' => $time
  ]);

  if ($new_column) {
    echo json_encode(['success' => true]);
    exit;
  }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
  <title>Signup</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    .signup-container {
      background: #333;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    .signup-container h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .signup-container form input {
      width: 100%;
      padding: 10px;
      margin-top: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .signup-container form button {
      width: 100%;
      padding: 12px;
      background-color: #333;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1rem;
    }

    .signup-container form button:hover {
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
  <div class="signup-container">
    <h2>Create an Account</h2>

    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="" onsubmit="return validatePassword();" id="signupForm">
      <input type="text" name="user" placeholder="Username" required>

      <input type="password" id="password" name="pass" placeholder="Password" required>
      <div id="password-msg" class="warning"></div>

      <input type="password" id="confirm_password" name="cpass" placeholder="Confirm Password" required>
      <div id="confirm-msg" class="warning"></div>

      <button type="submit">Sign Up</button>
    </form>
    <!-- Toast -->
    <div id="toast" class="toast"></div>
  </div>
  <script>
    function validatePassword() {
      const pwd = document.getElementById('pass').value;
      const confirm = document.getElementById('cpass').value;
      const pwdMsg = document.getElementById('password-msg');
      const confirmMsg = document.getElementById('confirm-msg');

      pwdMsg.textContent = '';
      confirmMsg.textContent = '';

      const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

      if (!strongRegex.test(pwd)) {
        pwdMsg.textContent = "Password too weak. Use at least 8 characters with uppercase, lowercase, number, and special symbol.";
        return false;
      }

      if (pwd !== confirm) {
        confirmMsg.textContent = "Passwords do not match.";
        return false;
      }

      return true;
    }
  </script>

  <script>
    document.getElementById("signupForm").addEventListener("submit", async function(e) {
      e.preventDefault();

      const form = e.target;
      const formData = new FormData(form);

      const password = formData.get("password");
      const confirm = formData.get("confirm_password");

      try {
        const response = await fetch("signup.php", {
          method: "POST",
          body: formData
        });

        const result = await response.json();

        if (result.success) {
          form.reset();
          showToast("✅ Account created successfully!");
        } else {
          showToast(result.message, true);
        }

      } catch (err) {
        showToast("Something went wrong.", true);
      }
    });

    function showToast(message, isError = false) {
      const toast = document.getElementById("toast");
      toast.textContent = message;
      toast.style.background = isError ? "#d32f2f" : "#4CAF50";
      toast.classList.add("show");

      setTimeout(() => {
        toast.classList.remove("show");
      }, 6000);
    }
  </script>

</body>

</html>