<?php
include "config.php";
?> 
<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fa fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

if (session_status() === PHP_SESSION_NONE) session_start();
$user_id = $_SESSION['user_id'] ?? 0;
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Code for font awesome cdn -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Code for font awesome cdn -->
         <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
     <!-- Header Section -->
     <header class="header">
        <a href="index.php" class="logo"> 
            <i class="fa fa-cart-plus" aria-hidden="true"></i>
            <span>Grocery Plus</span>
        </a>

    <nav class="navbar" >
        <a href="index.php">home</a>
        <a href="features.php">features</a>
        <a href="products.php">products</a>
        <a href="orders.php">orders</a>
        <a href="category.php">categories</a>
        <a href="about.php">about</a>
    </nav>



    <div class="icons">
        <div class="fa fa-bars" id="menu-btn"></div>
        <div id="menu-btn" class="fa fa-bars"></div>
         
         <a href="search_page.php" class="fa fa-search"></a>
         <?php
         // Count wishlist items
         $wishlist_count = 0;
         $wishlist_sql = "SELECT COUNT(*) as count FROM wishlist WHERE user_id = ?";
         $wishlist_stmt = mysqli_prepare($conn, $wishlist_sql);
         mysqli_stmt_bind_param($wishlist_stmt, "i", $user_id);
         mysqli_stmt_execute($wishlist_stmt);
         $wishlist_result = mysqli_stmt_get_result($wishlist_stmt);
         if ($wishlist_row = mysqli_fetch_assoc($wishlist_result)) {
            $wishlist_count = $wishlist_row['count'];
         }

         // Count cart items
         $cart_count = 0;
         $cart_sql = "SELECT COUNT(*) as count FROM cart WHERE user_id = ?";
         $cart_stmt = mysqli_prepare($conn, $cart_sql);
         mysqli_stmt_bind_param($cart_stmt, "i", $user_id);
         mysqli_stmt_execute($cart_stmt);
         $cart_result = mysqli_stmt_get_result($cart_stmt);
         if ($cart_row = mysqli_fetch_assoc($cart_result)) {
            $cart_count = $cart_row['count'];
         }
         ?>

         <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $wishlist_count; ?>)</span></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $cart_count; ?>)</span></a>
      </div>

        <form class="search-form">
            <input type="search" id="search-box" placeholder="Search Here....">
            <label for="search-box" class="fa fa-search"></label>
        </form>
        <?php if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])): ?>
   <!-- User is logged in -->
   <div class="user-menu">
      <img src="image/<?= isset($fetch_profile['image']) ? htmlspecialchars($fetch_profile['image']):'default.png' ?>" class="user-icon" id="userMenuToggle" alt="User Icon">
      <div class="user-dropdown" id="userDropdown">
         <a href="userprofile.php">Update Profile</a>
         <a href="logout.php" class="logout-link">Logout</a>
      </div>
   </div>
<?php else: ?>
   <!-- User not logged in -->
   <div class="auth-buttons">
      <a href="login.php" class="log-btn">Login</a>
      <a href="register.php" class="reg-btn">Register</a>
   </div>
<?php endif; ?>

       
     </header>
   

