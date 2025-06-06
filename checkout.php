<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('location:login.php');
    exit;
}

$message = [];

if (isset($_POST['order'])) {

    // Get inputs as-is (no filter sanitize string)
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $method = $_POST['method'];

    // Build full address without sanitizing strings
    $address = 'flat no. ' . $_POST['flat'] . ' ' . $_POST['street'] . ' ' . $_POST['city'] . ' ' . $_POST['state'] . ' ' . $_POST['country'] . ' - ' . $_POST['pin_code'];

    // Use standard datetime format for DB
    $placed_on = date('Y-m-d H:i:s');

    $cart_total = 0;
    $cart_products = [];

    // Fetch cart items
    $cart_query = mysqli_prepare($conn, "SELECT * FROM `cart` WHERE user_id = ?");
    mysqli_stmt_bind_param($cart_query, "i", $user_id);
    mysqli_stmt_execute($cart_query);
    $cart_result = mysqli_stmt_get_result($cart_query);

    if (mysqli_num_rows($cart_result) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_result)) {
            $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ')';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ', $cart_products);

    if ($cart_total == 0) {
        $message[] = 'Your cart is empty';
    } else {
        // Check if the order already exists
        $order_query = mysqli_prepare($conn, "SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
        mysqli_stmt_bind_param($order_query, "ssssssd", $name, $number, $email, $method, $address, $total_products, $cart_total);
        mysqli_stmt_execute($order_query);
        $order_result = mysqli_stmt_get_result($order_query);

        if (mysqli_num_rows($order_result) > 0) {
            $message[] = 'Order already placed!';
        } else {
            // Insert new order
            $insert_order = mysqli_prepare($conn, "INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert_order, "issssssds", $user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on);
            mysqli_stmt_execute($insert_order);

            if (mysqli_stmt_affected_rows($insert_order) > 0) {
                // Clear the cart
                $delete_cart = mysqli_prepare($conn, "DELETE FROM `cart` WHERE user_id = ?");
                mysqli_stmt_bind_param($delete_cart, "i", $user_id);
                mysqli_stmt_execute($delete_cart);

                $message[] = 'Order placed successfully!';
            } else {
                $message[] = 'Failed to place order, please try again.';
            }
        }
    }
}

?>
