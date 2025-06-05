<?php
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
        <a href="home.php">home</a>
        <a href="features.php">features</a>
        <a href="products.php">products</a>
        <a href="categories.php">categories</a>
        <a href="review.php">review</a>
        <a href="about.php">about</a>
    </nav>
</header>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<header>
   <nav>
      <a href="index.php">Home</a>

      <?php if (isset($_SESSION['user_id'])): ?>
         <a href="userprofile.php" class="btn">My Profile</a>
         <a href="logout.php" class="btn">Logout</a>
      <?php elseif (isset($_SESSION['admin_id'])): ?>
         <a href="admin_page.php" class="btn">Admin Panel</a>
         <a href="logout.php" class="btn">Logout</a>
      <?php else: ?>
         <a href="login.php" class="btn">Login</a>
         <a href="register.php" class="btn">Register</a>
      <?php endif; ?>
   </nav>
</header>

