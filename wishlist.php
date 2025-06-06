<?php
@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}

if (isset($_POST['add_to_cart'])) {
    $pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
    $p_name = isset($_POST['p_name']) ? trim($_POST['p_name']) : '';
    $p_image = isset($_POST['p_image']) ? trim($_POST['p_image']) : '';
    $p_qty = isset($_POST['p_qty']) ? max(1, intval($_POST['p_qty'])) : 1;

    // Fetch actual product price to avoid tampering
    $price_stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $price_stmt->bind_param("i", $pid);
    $price_stmt->execute();
    $price_result = $price_stmt->get_result();
    if ($price_result->num_rows > 0) {
        $product_data = $price_result->fetch_assoc();
        $p_price = floatval($product_data['price']);
    } else {
        $message[] = 'Invalid product ID!';
    }

    if (!isset($p_price)) {
        $message[] = 'Product not found.';
    } else {
        // Check if already in cart
        $check_cart = $conn->prepare("SELECT * FROM cart WHERE name = ? AND user_id = ?");
        $check_cart->bind_param("si", $p_name, $user_id);
        $check_cart->execute();
        $cart_result = $check_cart->get_result();

        if ($cart_result->num_rows > 0) {
            $message[] = 'Already added to cart!';
        } else {
            // Remove from wishlist if present
            $delete_wishlist = $conn->prepare("DELETE FROM wishlist WHERE name = ? AND user_id = ?");
            $delete_wishlist->bind_param("si", $p_name, $user_id);
            $delete_wishlist->execute();

            // Insert into cart
            $insert_cart = $conn->prepare("INSERT INTO cart (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_cart->bind_param("iisdss", $user_id, $pid, $p_name, $p_price, $p_qty, $p_image);
            $insert_cart->execute();
            $message[] = 'Added to cart!';
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $delete_item = $conn->prepare("DELETE FROM wishlist WHERE id = ?");
    $delete_item->bind_param("i", $delete_id);
    $delete_item->execute();
    header('location:wishlist.php');
    exit;
}

if (isset($_GET['delete_all'])) {
    $delete_all = $conn->prepare("DELETE FROM wishlist WHERE user_id = ?");
    $delete_all->bind_param("i", $user_id);
    $delete_all->execute();
    header('location:wishlist.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Wishlist</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/all.min.css" />
   <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<?php include 'header.php'; ?>

<section class="wishlist">
   <h1 class="title">Products Added</h1>

   <div class="box-container">
   <?php
      $grand_total = 0;
      $wishlist = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ?");
      $wishlist->bind_param("i", $user_id);
      $wishlist->execute();
      $result = $wishlist->get_result();

      if ($result->num_rows > 0) {
         while ($row = $result->fetch_assoc()) {
   ?>
   <form action="" method="POST" class="box">
      <a href="wishlist.php?delete=<?= $row['id']; ?>" class="fa fa-times" onclick="return confirm('Delete this from wishlist?');"></a>
      <a href="view_page.php?pid=<?= $row['pid']; ?>" class="fa fa-eye"></a>
      <img src="uploaded_img/<?= htmlspecialchars($row['image']); ?>" alt="" />
      <div class="name"><?= htmlspecialchars($row['name']); ?></div>
      <div class="price">$<?= number_format($row['price'], 2); ?>/-</div>
      <input type="number" min="1" value="1" class="qty" name="p_qty" />
      <input type="hidden" name="pid" value="<?= intval($row['pid']); ?>" />
      <input type="hidden" name="p_name" value="<?= htmlspecialchars($row['name']); ?>" />
      <input type="hidden" name="p_image" value="<?= htmlspecialchars($row['image']); ?>" />
      <input type="submit" value="Add to Cart" name="add_to_cart" class="btn" />
   </form>
   <?php
            $grand_total += $row['price'];
         }
      } else {
         echo '<p class="empty">Your wishlist is empty</p>';
      }
   ?>
   </div>

   <div class="wishlist-total">
      <p>Grand Total: <span>$<?= number_format($grand_total, 2); ?>/-</span></p>
      <a href="shop.php" class="option-btn">Continue Shopping</a>
      <a href="wishlist.php?delete_all" class="delete-btn <?= ($grand_total > 0) ? '' : 'disabled'; ?>">Delete All</a>
   </div>
</section>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
