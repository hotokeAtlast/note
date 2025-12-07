<?php
require_once "./db_conn.php";
include 'session_starter.php';

$uid = $_SESSION['uid'] ?? null;

$stmt = $pdo->prepare("SELECT user, pfp FROM login WHERE uid = ?");
$stmt->execute([$uid]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: login.php");
    exit;
}

// 🔁 Handle AJAX profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    header('Content-Type: application/json');

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $picPath = null;

    // Validate username
    if (!preg_match('/^[a-zA-Z0-9_.]{3,}$/', $username)) {
        echo json_encode(['success' => false, 'message' => 'Invalid username format.']);
        exit;
    }

    // Check if username is taken by another user
    $stmt = $pdo->prepare("SELECT uid FROM login WHERE user = ? AND uid != ?");
    $stmt->execute([$username, $uid]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Username already taken.']);
        exit;
    }


    $cooldownDays = 30;
    $stmt = $pdo->prepare("SELECT last_profile_edit FROM login WHERE uid = ?");
    $stmt->execute([$uid]);
    $lastEdit = $stmt->fetchColumn();

    if ($lastEdit) {
        $lastEditTime = strtotime($lastEdit);
        $now = time();
        $remaining = $lastEditTime + ($cooldownDays * 86400) - $now;

        if ($remaining > 0) {
            $daysLeft = ceil($remaining / 86400);
            echo json_encode([
                'success' => false,
                'message' => "You can update your profile again in $daysLeft day(s)."
            ]);
            exit;
        }
    }

    // Handle profile picture upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $uid . '_' . time() . '.' . $ext;
        $target = 'uploads/' . $filename;

        // Optional: Validate file type/size
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target)) {
            $picPath = $target;
        }
    }

    // Build dynamic update SQL
    $fields = ['user' => $username];
    $sql = "UPDATE login SET user = :user";

    if (!empty($password)) {
        $fields['passid'] = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", passid = :passid";
    }

    if ($picPath) {
        $fields['pfp'] = $picPath; // ✅ renamed from profile_pic to pic
        $sql .= ", pfp = :pfp";
    }

    $sql .= " WHERE uid = :uid";
    $fields['uid'] = $uid;


    $stmt = $pdo->prepare($sql);
    $stmt->execute($fields);

    $_SESSION['user'] = $username;
    $pdo->prepare("UPDATE login SET last_profile_edit = NOW() WHERE uid = ?")->execute([$uid]);

    echo json_encode(['success' => true, 'message' => 'Profile updated successfully!']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .profile-container {
            background: #333;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: 50px auto;
            color: white;
        }

        .profile-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-container form input,
        .profile-container form button {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
        }

        .profile-container form input {
            border: 1px solid #ccc;
        }

        .profile-container form button {
            background-color: #444;
            color: white;
            cursor: pointer;
        }

        .profile-container form button:hover {
            background-color: #555;
        }

        .profile-picture {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-picture img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #888;
        }

        .toast {
            visibility: hidden;
            min-width: 200px;
            background-color: #d32f2f;
            color: white;
            text-align: center;
            padding: 10px 20px;
            border-radius: 8px;
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            font-size: 16px;
            opacity: 0;
            transition: visibility 0s, opacity 0.5s ease;
        }

        .toast.show {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="profile-container">
        <h2>Your Profile</h2>

        <div class="profile-picture">
            <img src="<?= $user['pfp'] ?: 'default.jpg' ?>" alt="Profile Picture">
        </div>

        <form id="profileForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['user']) ?>" required>
            </div>
            <div id="user-status-msg" style="margin-top: -10px; font-size: 0.85rem; margin-bottom: 12px;"></div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" placeholder="Leave blank to keep current">
            </div>

            <div class="form-group">
                <label for="profile_pic">Profile Picture</label>
                <input type="file" name="profile_pic" accept="image/*">
            </div>

            <button type="submit">Update Profile</button>
        </form>
    </div>

    <div id="toast" class="toast"></div>


    <script>
        function showToast(message, isError = false) {
            const toast = document.getElementById("toast");
            toast.textContent = message;
            toast.style.backgroundColor = isError ? "#d32f2f" : "#4CAF50";
            toast.classList.add("show");
            setTimeout(() => toast.classList.remove("show"), 5000);
        }


        document.getElementById("profileForm").addEventListener("submit", async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            formData.append("action", "update_profile");

            const submitBtn = form.querySelector("button[type=submit]");
            submitBtn.disabled = true;

            try {
                const res = await fetch("profile.php", {
                    method: "POST",
                    body: formData
                });

                const result = await res.json();
                showToast(result.message, !result.success);

                if (result.success) {
                    setTimeout(() => location.reload(), 1500); // Optional refresh
                }

            } catch (err) {
                showToast("Something went wrong while updating.", true);
            }

            submitBtn.disabled = false;
        });
    </script>

</body>

</html>