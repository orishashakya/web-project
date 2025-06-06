<?php

@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (empty($user_id)) {
    header('location:login.php');
    exit;
}

$message = [];

// Helper to sanitize text input
function clean_text($text) {
    return htmlspecialchars(trim($text));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_to_wishlist'])) {

        $pid = filter_var($_POST['pid'] ?? '', FILTER_VALIDATE_INT);
        $p_name = clean_text($_POST['p_name'] ?? '');
        $p_price = filter_var($_POST['p_price'] ?? '', FILTER_VALIDATE_FLOAT);
        $p_image = clean_text($_POST['p_image'] ?? '');

        if (!$pid || !$p_name || !$p_price || !$p_image) {
            $message[] = "Invalid product data!";
        } else {
            // Check if product already in wishlist
            $stmt = $conn->prepare("SELECT * FROM `wishlist` WHERE pid = ? AND user_id = ?");
            $stmt->bind_param("ii", $pid, $user_id);
            $stmt->execute();
            $wishlist_result = $stmt->get_result();
            $stmt->close();

            // Check if product already in cart
            $stmt = $conn->prepare("SELECT * FROM `cart` WHERE pid = ? AND user_id = ?");
            $stmt->bind_param("ii", $pid, $user_id);
            $stmt->execute();
            $cart_result = $stmt->get_result();
            $stmt->close();

            if ($wishlist_result->num_rows > 0) {
                $message[] = "Already added to wishlist!";
            } elseif ($cart_result->num_rows > 0) {
                $message[] = "Already added to cart!";
            } else {
                $stmt = $conn->prepare("INSERT INTO `wishlist` (user_id, pid, name, price, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iisss", $user_id, $pid, $p_name, $p_price, $p_image);
                if ($stmt->execute()) {
                    $message[] = "Added to wishlist!";
                } else {
                    $message[] = "Failed to add to wishlist.";
                }
                $stmt->close();
            }
        }
    }

    if (isset($_POST['add_to_cart'])) {

        $pid = filter_var($_POST['pid'] ?? '', FILTER_VALIDATE_INT);
        $p_name = clean_text($_POST['p_name'] ?? '');
        $p_price = filter_var($_POST['p_price'] ?? '', FILTER_VALIDATE_FLOAT);
        $p_image = clean_text($_POST['p_image'] ?? '');
        $p_qty = filter_var($_POST['p_qty'] ?? 1, FILTER_VALIDATE_INT);
        if ($p_qty < 1) $p_qty = 1;

        if (!$pid || !$p_name || !$p_price || !$p_image) {
            $message[] = "Invalid product data!";
        } else {
            // Check if product already in cart
            $stmt = $conn->prepare("SELECT * FROM `cart` WHERE pid = ? AND user_id = ?");
            $stmt->bind_param("ii", $pid, $user_id);
            $stmt->execute();
            $cart_result = $stmt->get_result();
            $stmt->close();

            if ($cart_result->num_rows > 0) {
                $message[] = "Already added to cart!";
            } else {
                // Remove from wishlist if exists
                $stmt = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ? AND user_id = ?");
                $stmt->bind_param("ii", $pid, $user_id);
                $stmt->execute();
                $stmt->close();

                // Add to cart
                $stmt = $conn->prepare("INSERT INTO `cart` (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iisdis", $user_id, $pid, $p_name, $p_price, $p_qty, $p_image);
                if ($stmt->execute()) {
                    $message[] = "Added to cart!";
                } else {
                    $message[] = "Failed to add to cart.";
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
<!-- ... your head content ... -->
</head>
<body>

<?php include 'header.php'; ?>

<?php
// Display messages
if (!empty($message)) {
    echo '<div class="messages">';
    foreach ($message as $msg) {
        echo '<p>' . htmlspecialchars($msg) . '</p>';
    }
    echo '</div>';
}
?>

<section class="search-form">

   <form action="" method="POST">
      <input type="text" class="box" name="search_box" placeholder="search products..." required>
      <input type="submit" name="search_btn" value="search" class="btn">
   </form>

</section>

<section class="products" style="padding-top: 0; min-height:100vh;">
   <div class="box-container">

<?php
if (isset($_POST['search_btn'])) {
    $search_box = trim($_POST['search_box']);
    $search_param = "%{$search_box}%";

    $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE ? OR category LIKE ?");
    $select_products->bind_param("ss", $search_param, $search_param);
    $select_products->execute();
    $result = $select_products->get_result();

    if ($result->num_rows > 0) {
        while ($fetch_products = $result->fetch_assoc()) {
            ?>
            <form action="" class="box" method="POST">
                <div class="price">$<span><?= htmlspecialchars($fetch_products['price']); ?></span>/-</div>
                <a href="view_page.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>" class="fas fa-eye"></a>
                <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="">
                <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
                <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>">
                <input type="hidden" name="p_name" value="<?= htmlspecialchars($fetch_products['name']); ?>">
                <input type="hidden" name="p_price" value="<?= htmlspecialchars($fetch_products['price']); ?>">
                <input type="hidden" name="p_image" value="<?= htmlspecialchars($fetch_products['image']); ?>">
                <input type="number" min="1" value="1" name="p_qty" class="qty">
                <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">
                <input type="submit" value="add to cart" class="btn" name="add_to_cart">
            </form>
            <?php
        }
    } else {
        echo '<p class="empty">no result found!</p>';
    }
}
?>

   </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
