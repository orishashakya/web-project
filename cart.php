<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit;
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];

   $delete_cart_item = mysqli_prepare($conn, "DELETE FROM `cart` WHERE id = ?");
   mysqli_stmt_bind_param($delete_cart_item, "i", $delete_id);
   mysqli_stmt_execute($delete_cart_item);

   header('location:cart.php');
   exit;
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = mysqli_prepare($conn, "DELETE FROM `cart` WHERE user_id = ?");
   mysqli_stmt_bind_param($delete_cart_item, "i", $user_id);
   mysqli_stmt_execute($delete_cart_item);

   header('location:cart.php');
   exit;
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $p_qty = filter_var($_POST['p_qty'], FILTER_SANITIZE_NUMBER_INT);

   $update_qty = mysqli_prepare($conn, "UPDATE `cart` SET quantity = ? WHERE id = ?");
   mysqli_stmt_bind_param($update_qty, "ii", $p_qty, $cart_id);
   mysqli_stmt_execute($update_qty);

   $message[] = 'cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>shopping cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/all.min.css" />

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<?php include 'header.php'; ?>

<section class="shopping-cart">

   <h1 class="title">products added</h1>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_cart = mysqli_prepare($conn, "SELECT * FROM `cart` WHERE user_id = ?");
      mysqli_stmt_bind_param($select_cart, "i", $user_id);
      mysqli_stmt_execute($select_cart);
      $result = mysqli_stmt_get_result($select_cart);

      if(mysqli_num_rows($result) > 0){
         while($fetch_cart = mysqli_fetch_assoc($result)){ 
   ?>
   <form action="" method="POST" class="box">
      <a href="cart.php?delete=<?= $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
      <a href="view_page.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= htmlspecialchars($fetch_cart['image']); ?>" alt="" />
      <div class="name"><?= htmlspecialchars($fetch_cart['name']); ?></div>
      <div class="price">$<?= htmlspecialchars($fetch_cart['price']); ?>/-</div>
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>" />
      <div class="flex-btn">
         <input type="number" min="1" value="<?= $fetch_cart['quantity']; ?>" class="qty" name="p_qty" />
         <input type="submit" value="update" name="update_qty" class="option-btn" />
      </div>
      <div class="sub-total"> sub total : <span>$<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
   </form>
   <?php
      $grand_total += $sub_total;
      }
   }else{
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   </div>

   <div class="cart-total">
      <p>grand total : <span>$<?= $grand_total; ?>/-</span></p>
      <a href="shop.php" class="option-btn">continue shopping</a>
      <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>">delete all</a>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a>
   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
