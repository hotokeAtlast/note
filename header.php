<?php
include 'session_starter.php';
$user_id = $_SESSION['user'] ?? null;
$uid = $_SESSION['uid'] ?? null;
$pfp = $_SESSION['pfp'] ?? null;

// if ($user_id) {
//     echo "hi, " . $user_id . " and UID: " . $uid;
// } else {
//     echo "user not logged in ";
// }

?>
<header>
  <?php
  $currentPage = basename($_SERVER['PHP_SELF']);
  ?>

  <nav class="navbar">
    <div class="nav-left">
      <?php if ($currentPage === 'index.php'): ?>
        <span class="nav-logo disabled-link">Home</span>
      <?php else: ?>
        <a href="index.php" class="nav-logo">Home</a>
      <?php endif; ?>
    </div>

    <div class="nav-right" id="navMenu">
      <a href="create.php">Create New Note</a>

      <?php if($user_id){
        echo '<div class="dropdown">
          <img class="nav-img" src="'. $pfp .'" alt="Profile" class="nav-pfp">
        <button class="dropbtn">'. htmlspecialchars($user_id) .' ⮟</button>
        <div class="dropdown-content">';
          if ($currentPage === "profile.php"):
            echo '
            <a class="disabled-link">Profile</a>';
          else: 
            echo '
            <a href="profile.php">Profile</a>';
          endif;

            echo '
            <button class="btn" id="logoutBtn">Logout</button>
        </div>
      </div>';
      } else{
        echo '<div class="dropdown">
        <button class="dropbtn">Account ⮟</button>
        <div class="dropdown-content">';
          if ($currentPage === "login.php"):
            echo '
            <a class="disabled-link">Login</a>';
          else: 
            echo '
            <a href="login.php">Login</a>';
          endif;

           if ($currentPage === "signup.php"):
            echo '
            <a class="disabled-link">Signup</a>';
          else:
            echo '
            <a href="signup.php">Signup</a>';
          endif;
          echo '
        </div>
      </div>';
      }
      
      
      ?>
    </div>

    <button class="menu-toggle" id="menuToggle">☰</button>
  </nav>
  <div id="alertLogin" class="toast"></div>

  <script>
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');

    menuToggle.addEventListener('click', () => {
      navMenu.classList.toggle('active');
    });
  </script>
</header>
