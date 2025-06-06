<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit;
}

if(isset($_POST['add_to_wishlist'])){

   // Sanitize inputs manually
   $pid = trim(strip_tags($_POST['pid']));
   $p_name = trim(strip_tags($_POST['p_name']));
   $p_price = trim(strip_tags($_POST['p_price']));
   $p_image = trim(strip_tags($_POST['p_image']));

   // Check wishlist
   $check_wishlist = mysqli_prepare($conn, "SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   mysqli_stmt_bind_param($check_wishlist, "si", $p_name, $user_id);
   mysqli_stmt_execute($check_wishlist);
   $result_wishlist = mysqli_stmt_get_result($check_wishlist);

   // Check cart
   $check_cart = mysqli_prepare($conn, "SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   mysqli_stmt_bind_param($check_cart, "si", $p_name, $user_id);
   mysqli_stmt_execute($check_cart);
   $result_cart = mysqli_stmt_get_result($check_cart);

   if(mysqli_num_rows($result_wishlist) > 0){
      $message[] = 'already added to wishlist!';
   }elseif(mysqli_num_rows($result_cart) > 0){
      $message[] = 'already added to cart!';
   }else{
      $insert_wishlist = mysqli_prepare($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES (?, ?, ?, ?, ?)");
      mysqli_stmt_bind_param($insert_wishlist, "iisss", $user_id, $pid, $p_name, $p_price, $p_image);
      mysqli_stmt_execute($insert_wishlist);
      $message[] = 'added to wishlist!';
   }

}

if(isset($_POST['add_to_cart'])){

   // Sanitize inputs manually
   $pid = trim(strip_tags($_POST['pid']));
   $p_name = trim(strip_tags($_POST['p_name']));
   $p_price = trim(strip_tags($_POST['p_price']));
   $p_image = trim(strip_tags($_POST['p_image']));
   $p_qty = trim(strip_tags($_POST['p_qty']));

   // Check cart
   $check_cart = mysqli_prepare($conn, "SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   mysqli_stmt_bind_param($check_cart, "si", $p_name, $user_id);
   mysqli_stmt_execute($check_cart);
   $result_cart = mysqli_stmt_get_result($check_cart);

   if(mysqli_num_rows($result_cart) > 0){
      $message[] = 'already added to cart!';
   }else{

      // Check wishlist
      $check_wishlist = mysqli_prepare($conn, "SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      mysqli_stmt_bind_param($check_wishlist, "si", $p_name, $user_id);
      mysqli_stmt_execute($check_wishlist);
      $result_wishlist = mysqli_stmt_get_result($check_wishlist);

      if(mysqli_num_rows($result_wishlist) > 0){
         $delete_wishlist = mysqli_prepare($conn, "DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         mysqli_stmt_bind_param($delete_wishlist, "si", $p_name, $user_id);
         mysqli_stmt_execute($delete_wishlist);
      }

      // Insert to cart
      $insert_cart = mysqli_prepare($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
      mysqli_stmt_bind_param($insert_cart, "iissis", $user_id, $pid, $p_name, $p_price, $p_qty, $p_image);
      mysqli_stmt_execute($insert_cart);

      $message[] = 'added to cart!';
   }

}

?>
