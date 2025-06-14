<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {
   $email = trim($_POST['email']);
   $pass = md5($_POST['pass']);

   // Validate email format
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $message[] = 'Invalid email format!';
   } else {
      $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
      $stmt->bind_param("ss", $email, $pass);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
         $row = $result->fetch_assoc();

         if ($row['user_type'] == 'admin') {
            $_SESSION['admin_id'] = $row['id'];
            header('location:admin_page.php');
         } elseif ($row['user_type'] == 'user') {
            $_SESSION['user_id'] = $row['id'];
            header('location:index.php');
         }
         exit;
      } else {
         $message[] = 'Incorrect email or password!';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Login</title>
   <link rel="stylesheet" href="css/components.css">
</head>
<body>

<?php if (isset($message)) {
   foreach ($message as $msg) {
      echo '<div class="message"><span>' . $msg . '</span></div>';
   }
} ?>
<div class="register-body">
<section class="form-container">
   <form action="" method="POST">
      <h3>Login Now</h3>
      <input type="email" name="email" class="box" placeholder="Enter your email" required>
      <input type="password" name="pass" class="box" placeholder="Enter your password" required>
      <input type="submit" value="Login Now" class="btn" name="submit">
      <p>Don't have an account? <a href="register.php">Register Now</a></p>
   </form>
</section>
</div>

</body>
</html>