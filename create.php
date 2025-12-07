<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'session_starter.php';
require_once 'db_conn.php';

$user_id = $_SESSION['user'] ?? null;
$uid = $_SESSION['uid'] ?? null;
// if ($user_id) {
//     echo '';
// } else {
//     echo '<script>// 👇 Show toast on page load
//         window.addEventListener("DOMContentLoaded", () => {
//             showToast("🔒 You must be logged in to add notes.", 7000);
//         });</script>';
// }


if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !$user_id) {
    echo '<script>
        window.addEventListener("DOMContentLoaded", () => {
            showToast("🔒 You must be logged in to add notes.", 7000);
        });
    </script>';
}

// echo "form for " . $uid;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');

    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in to add notes.']);
        exit;
    }

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        echo json_encode(['success' => false, 'message' => 'Both title and content are required.']);
        exit;
    }

    $check_for_double = $pdo->prepare("SELECT * FROM `notes` WHERE `uid` = :uid AND `title` = :title AND `content` = :content");
    $check_for_double->execute([
        ':uid' => $uid,
        ':title' => $title,
        ':content' => $content
    ]);

    if ($check_for_double->fetch()) {
        echo json_encode(['success' => false, 'message' => 'You already have a note with the same title and content!']);
        exit;
    }

    $nof_count = str_word_count($content);
    $id = rand(1001111, 9999999);
    $ins = $pdo->prepare("INSERT INTO `notes` (uid, id, title, content, nof_char, date, time) VALUES (:uid, :id, :title, :content, :nof_char, current_timestamp(), :time)");
    $ins->execute([
        ':uid' => $uid,
        ':id' => $id,
        ':title' => $title,
        ':content' => $content,
        ':nof_char' => $nof_count,
        ':time' => $time
    ]);

    echo json_encode(['success' => true, 'message' => 'Note Saved Successfully!']);
    exit;
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Create a Note</title>
    <style>
        form {
            max-width: 600px;
            margin: 60px auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 24px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        input[type="text"],
        textarea {
            padding: 10px;
            font-size: 15px;
            width: 100%;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        button {
            /* padding: 10px; */
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .msg {
            text-align: center;
            margin-top: 20px;
            color: green;
        }

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

        .login-container form textarea {
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
        <h2>Create a New Note</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="" id="noteForm">
            <input type="text" name="title" id="title" placeholder="Note Title" maxlength="80" style="margin-bottom: 0;" oninput="updateCount(this.value.length)" required>
            <small id="charCount">0 / 80</small>
            <script>
                function updateCount(len) {
                    document.getElementById("charCount").textContent = `${len} / 80`;
                }
            </script>

            <textarea name="content" id="content" placeholder="Write your note here..." style="margin-bottom: 0;" required></textarea>
            <p id="wordCount">0 words</p>
            <script>
                document.getElementById("content").addEventListener("input", function() {
                    const count = this.value.trim().split(/\s+/).filter(Boolean).length;
                    document.getElementById("wordCount").textContent = `${count} word${count !== 1 ? 's' : ''}`;
                });
            </script>

            <button type="submit">Save Note</button>
        </form>
        <p id="noteMsg"></p>
        <!-- Toast -->
        <div id="toast" class="toast"></div>
    </div>
    <?php if (!empty($message)): ?>
        <p class="msg"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <script>
        // 🚀 AJAX Form Submission

        const form = document.getElementById("noteForm");
        const wordCountDisplay = document.getElementById("wordCount");


        document.getElementById("noteForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const form = this;
    const wordCountDisplay = document.getElementById("wordCount"); 
    const formData = new FormData(form);
    
    try {
        const response = await fetch("create.php", {
            method: "POST",
            body: formData
        });

        const result = await response.json();
        const msg = document.getElementById("noteMsg");
        msg.textContent = result.message;
        msg.style.color = result.success ? "green" : "red";

        if (result.success) {
            form.reset();
            showToast("Note Saved Successfully!");
            wordCountDisplay.textContent = "0 words";
        } else {
            showToast(result.message, true);
        }

    } catch (err) {
        showToast("Something went wrong.", true); // 🔧 changed from result.message which may not exist
    }
});
    </script>

    <script>
        function showToast(message, duration = 5000) {
            const toast = document.getElementById("toast");
            toast.textContent = message;
            toast.classList.add("show");

            setTimeout(() => {
                toast.classList.remove("show");
            }, duration);
        }
    </script>
    <script src="script.js"></script>
</body>

</html>