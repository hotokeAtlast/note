<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>signup</title>
</head>

<body>
    <form action="" method="post">
        <div>
            <label for="username">Username</label>
            <input type="text" placeholder="uesrname" name="user" id="user">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="pass" name="pass" placeholder="password">
        </div>
        <div>
            <label for="password">ConfirmPassword</label>
            <input type="password" id="cpass" name="cpass" placeholder="confirm password">
        </div>
        <button type="submit">Signup</button>
        <div class="ref_login">
            <p>already have an account?</p><a href="/login.php">login</a>
        </div>

    </form>
</body>

</html>





<?php
include 'session_starter.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST["user"];
    $pass = $_POST["pass"];
    $cpass = $_POST["cpass"];
    // echo "1: " . $user . "   ";
    if ($pass == $cpass) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        require_once "./db_conn.php";



        $stmt = $pdo->prepare("SELECT * FROM login WHERE user=?");
        // echo "2: " . $user . "   ";
        $stmt->execute([$user]);

        $user = $stmt->fetch();

        if ($user) {
            echo "User already exists!";
        } else {
            $uid = random_int(111111, 999999);
            $new_column = $pdo->prepare("INSERT INTO login (`sno`, `uid`, `user`, `passid`, `phase`, `date`, `time`) VAlUES (NULL, :uid, :user, :passid, '0', :date, :time)");
            $new_column->execute([
                ':uid' => $uid,
                ':user' => $_POST["user"],
                ':passid' => $hash,
                ':date' => $date,
                ':time' => $time
            ]);
            if($new_column){
                echo "congratulations, account created successfully!";
            }   else{
                echo "Apologies, we are currently facing some issue from our end, thank you for your understanding";
            }


            echo $user;
        }
    }   else{
        echo "password does not match!";
    }
}

?>