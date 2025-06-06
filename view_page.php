<?php
@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}

// Add to wishlist
if (isset($_POST['add_to_wishlist'])) {
    $pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;

    // Fetch product details securely
    $stmt = $conn->prepare("SELECT name, price, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $product_result = $stmt->get_result();

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
        $p_name = $product['name'];
        $p_price = floatval($product['price']);
        $p_image = $product['image'];

        // Check wishlist
        $check_wishlist = $conn->prepare("SELECT id FROM wishlist WHERE name = ? AND user_id = ?");
        $check_wishlist->bind_param("si", $p_name, $user_id);
        $check_wishlist->execute();
        $wishlist_exists = $check_wishlist->get_result()->num_rows > 0;

        // Check cart
        $check_cart = $conn->prepare("SELECT id FROM cart WHERE name = ? AND user_id = ?");
        $check_cart->bind_param("si", $p_name, $user_id);
        $check_cart->execute();
        $cart_exists = $check_cart->get_result()->num_rows > 0;

        if ($wishlist_exists) {
            $message[] = 'Already added to wishlist!';
        } elseif ($cart_exists) {
            $message[] = 'Already added to cart!';
        } else {
            $insert_wishlist = $conn->prepare("INSERT INTO wishlist (user_id, pid, name, price, image) VALUES (?, ?, ?, ?, ?)");
            $insert_wishlist->bind_param("iisss", $user_id, $pid, $p_name, $p_price, $p_image);
            $insert_wishlist->execute();
            $message[] = 'Added to wishlist!';
        }
    } else {
        $message[] = 'Product not found!';
    }
}

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
    $p_qty = isset($_POST['p_qty']) ? max(1, intval($_POST['p_qty'])) : 1;

    // Fetch product details securely
    $stmt = $conn->prepare("SELECT name, price, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $product_result = $stmt->get_result();

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
        $p_name = $product['name'];
        $p_price = floatval($product['price']);
        $p_image = $product['image'];

        // Check cart
        $check_cart = $conn->prepare("SELECT id FROM cart WHERE name = ? AND user_id = ?");
        $check_cart->bind_param("si", $p_name, $user_id);
        $check_cart->execute();
        $cart_exists = $check_cart->get_result()->num_rows > 0;

        if ($cart_exists) {
            $message[] = 'Already added to cart!';
        } else {
            // Remove from wishlist if it exists
            $delete_wishlist = $conn->prepare("DELETE FROM wishlist WHERE name = ? AND user_id = ?");
            $delete_wishlist->bind_param("si", $p_name, $user_id);
            $delete_wishlist->execute();

            // Insert into cart
            $insert_cart = $conn->prepare("INSERT INTO cart (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_cart->bind_param("iisdss", $user_id, $pid, $p_name, $p_price, $p_qty, $p_image);
            $insert_cart->execute();
            $message[] = 'Added to cart!';
        }
    } else {
        $message[] = 'Product not found!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Product View</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/all.min.css" />
   <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<?php include 'header.php'; ?>

<section class="quick-view">
   <h1 class="title">Product View</h1>

   <?php
   $pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;

   if ($pid > 0) {
      $select_product = $conn->prepare("SELECT * FROM products WHERE id = ?");
      $select_product->bind_param("i", $pid);
      $select_product->execute();
      $result = $select_product->get_result();

      if ($result->num_rows > 0) {
         $product = $result->fetch_assoc();
   ?>
   <form action="" class="box" method="POST">
      <div class="price">$<span><?= number_format($product['price'], 2); ?></span>/-</div>
      <img src="image/<?= htmlspecialchars($product['image']); ?>" alt="" />
      <div class="name"><?= htmlspecialchars($product['name']); ?></div>
      <div class="details"><?= htmlspecialchars($product['details']); ?></div>
      <input type="hidden" name="pid" value="<?= intval($product['id']); ?>" />
      <input type="number" min="1" value="1" name="p_qty" class="qty" />
      <input type="submit" value="Add to Wishlist" class="option-btn" name="add_to_wishlist" />
      <input type="submit" value="Add to Cart" class="btn" name="add_to_cart" />
   </form>
   <?php
      } else {
         echo '<p class="empty">Product not found!</p>';
      }
   } else {
      echo '<p class="empty">Invalid product ID!</p>';
   }
   ?>
</section>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
