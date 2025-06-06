<?php

@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
   header('location:login.php');
   exit;
}

$message = [];

function sanitize_input($data) {
    return trim(htmlspecialchars($data));
}

// CSRF token validation placeholder (implement your own)
function verify_csrf_token($token) {
    // Example: return $token === $_SESSION['csrf_token'];
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // You can add CSRF token verification here
    // if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    //     $message[] = 'Invalid CSRF token!';
    // } else

    if (isset($_POST['add_to_wishlist'])) {

        $pid = filter_var($_POST['pid'], FILTER_VALIDATE_INT);
        $p_name = sanitize_input($_POST['p_name'] ?? '');
        $p_price = filter_var($_POST['p_price'], FILTER_VALIDATE_FLOAT);
        $p_image = sanitize_input($_POST['p_image'] ?? '');

        if (!$pid || !$p_name || !$p_price || !$p_image) {
            $message[] = 'Invalid product data!';
        } else {
            // Check if already in wishlist
            $stmt = $conn->prepare("SELECT * FROM `wishlist` WHERE pid = ? AND user_id = ?");
            $stmt->bind_param("ii", $pid, $user_id);
            $stmt->execute();
            $wishlist_result = $stmt->get_result();
            $stmt->close();

            // Check if already in cart
            $stmt = $conn->prepare("SELECT * FROM `cart` WHERE pid = ? AND user_id = ?");
            $stmt->bind_param("ii", $pid, $user_id);
            $stmt->execute();
            $cart_result = $stmt->get_result();
            $stmt->close();

            if ($wishlist_result->num_rows > 0) {
                $message[] = 'Already added to wishlist!';
            } elseif ($cart_result->num_rows > 0) {
                $message[] = 'Already added to cart!';
            } else {
                $stmt = $conn->prepare("INSERT INTO `wishlist` (user_id, pid, name, price, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iisss", $user_id, $pid, $p_name, $p_price, $p_image);
                if ($stmt->execute()) {
                    $message[] = 'Added to wishlist!';
                } else {
                    $message[] = 'Failed to add to wishlist.';
                }
                $stmt->close();
            }
        }
    }

    if (isset($_POST['add_to_cart'])) {

        $pid = filter_var($_POST['pid'], FILTER_VALIDATE_INT);
        $p_name = sanitize_input($_POST['p_name'] ?? '');
        $p_price = filter_var($_POST['p_price'], FILTER_VALIDATE_FLOAT);
        $p_image = sanitize_input($_POST['p_image'] ?? '');
        $p_qty = filter_var($_POST['p_qty'], FILTER_VALIDATE_INT);
        if (!$p_qty || $p_qty < 1) $p_qty = 1;

        if (!$pid || !$p_name || !$p_price || !$p_image) {
            $message[] = 'Invalid product data!';
        } else {
            // Check if already in cart
            $stmt = $conn->prepare("SELECT * FROM `cart` WHERE pid = ? AND user_id = ?");
            $stmt->bind_param("ii", $pid, $user_id);
            $stmt->execute();
            $cart_result = $stmt->get_result();
            $stmt->close();

            if ($cart_result->num_rows > 0) {
                $message[] = 'Already added to cart!';
            } else {
                // If in wishlist, delete from wishlist
                $stmt = $conn->prepare("SELECT * FROM `wishlist` WHERE pid = ? AND user_id = ?");
                $stmt->bind_param("ii", $pid, $user_id);
                $stmt->execute();
                $wishlist_result = $stmt->get_result();
                $stmt->close();

                if ($wishlist_result->num_rows > 0) {
                    $stmt = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ? AND user_id = ?");
                    $stmt->bind_param("ii", $pid, $user_id);
                    $stmt->execute();
                    $stmt->close();
                }

                // Insert into cart
                $stmt = $conn->prepare("INSERT INTO `cart` (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iisdis", $user_id, $pid, $p_name, $p_price, $p_qty, $p_image);
                if ($stmt->execute()) {
                    $message[] = 'Added to cart!';
                } else {
                    $message[] = 'Failed to add to cart.';
                }
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<?php include 'header.php'; ?>

<?php if (!empty($message)): ?>
    <div class="messages">
        <?php foreach ($message as $msg): ?>
            <p><?= htmlspecialchars($msg); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<section class="p-category">
   <a href="category.php?category=fruits">fruits</a>
   <a href="category.php?category=vegetables">vegetables</a> <!-- Fixed typo -->
   <a href="category.php?category=fish">fish</a>
   <a href="category.php?category=meat">meat</a>
</section>

<section class="products">
   <h1 class="title">latest products</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      $products_result = $select_products->get_result();
      $select_products->close();

      if ($products_result->num_rows > 0) {
         while ($fetch_products = $products_result->fetch_assoc()) {
   ?>
   <form action="" class="box" method="POST">
      <div class="price">$<span><?= htmlspecialchars($fetch_products['price']); ?></span>/-</div>
      <a href="view_page.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="" />
      <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
      <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>" />
      <input type="hidden" name="p_name" value="<?= htmlspecialchars($fetch_products['name']); ?>" />
      <input type="hidden" name="p_price" value="<?= htmlspecialchars($fetch_products['price']); ?>" />
      <input type="hidden" name="p_image" value="<?= htmlspecialchars($fetch_products['image']); ?>" />
      <input type="number" min="1" value="1" name="p_qty" class="qty" />
      <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist" />
      <input type="submit" value="add to cart" class="btn" name="add_to_cart" />
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>

   </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
