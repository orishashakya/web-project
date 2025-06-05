<?php
@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
   header('location:login.php');
   exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$fetch_profile = $result->fetch_assoc();
$stmt->close();

$message = [];

if (isset($_POST['update_profile'])) {
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);

   $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
   $stmt->bind_param("ssi", $name, $email, $user_id);
   $stmt->execute();
   $stmt->close();

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp = $_FILES['image']['tmp_name'];
   $image_path = 'uploaded_img/' . $image;
   $old_image = $_POST['old_image'];

   if (!empty($image)) {
      if ($image_size > 2000000) {
         $message[] = 'Image too large!';
      } else {
         $stmt = $conn->prepare("UPDATE users SET image = ? WHERE id = ?");
         $stmt->bind_param("si", $image, $user_id);
         $stmt->execute();
         $stmt->close();

         if (!is_dir('uploaded_img')) mkdir('uploaded_img', 0777, true);
         move_uploaded_file($image_tmp, $image_path);

         if ($old_image && file_exists('uploaded_img/' . $old_image)) {
            unlink('uploaded_img/' . $old_image);
         }

         $message[] = 'Image updated!';
      }
   }

   $old_pass = $_POST['old_pass'];
   $update_pass = md5($_POST['update_pass']);
   $new_pass = md5($_POST['new_pass']);
   $confirm_pass = md5($_POST['confirm_pass']);

   if (!empty($_POST['update_pass']) && !empty($_POST['new_pass']) && !empty($_POST['confirm_pass'])) {
      if ($update_pass !== $old_pass) {
         $message[] = 'Old password incorrect!';
      } elseif ($new_pass !== $confirm_pass) {
         $message[] = 'Passwords do not match!';
      } else {
         $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
         $stmt->bind_param("si", $confirm_pass, $user_id);
         $stmt->execute();
         $stmt->close();
         $message[] = 'Password updated!';
      }
   }

   $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
   $stmt->bind_param("i", $user_id);
   $stmt->execute();
   $result = $stmt->get_result();
   $fetch_profile = $result->fetch_assoc();
   $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>User Profile</title>
   <link rel="stylesheet" href="css/components.css">
   <style>
      .profile-pic {
         width: 150px;
         height: 150px;
         border-radius: 50%;
         object-fit: cover;
         margin-bottom: 1rem;
      }
   </style>
</head>
<body>

<?php include 'header.php'; ?>

<section class="update-profile">
   <h1 class="title">Your Profile</h1>

   <?php foreach ($message as $msg): ?>
      <div class="message"><span><?= $msg ?></span></div>
   <?php endforeach; ?>

   <form action="" method="POST" enctype="multipart/form-data">
      <img src="uploaded_img/<?= htmlspecialchars($fetch_profile['image']) ?>" alt="Profile Picture" class="profile-pic">

      <div class="flex">
         <div class="inputBox">
            <span>Name:</span>
            <input type="text" name="name" value="<?= htmlspecialchars($fetch_profile['name']) ?>" required class="box">
            <span>Email:</span>
            <input type="email" name="email" value="<?= htmlspecialchars($fetch_profile['email']) ?>" required class="box">
            <span>Change Picture:</span>
            <input type="file" name="image" class="box" accept="image/*">
            <input type="hidden" name="old_image" value="<?= htmlspecialchars($fetch_profile['image']) ?>">
         </div>

         <div class="inputBox">
            <input type="hidden" name="old_pass" value="<?= $fetch_profile['password'] ?>">
            <span>Old Password:</span>
            <input type="password" name="update_pass" class="box">
            <span>New Password:</span>
            <input type="password" name="new_pass" class="box">
            <span>Confirm Password:</span>
            <input type="password" name="confirm_pass" class="box">
         </div>
      </div>

      <div class="flex-btn">
         <input type="submit" value="Update Profile" name="update_profile" class="btn">
         <a href="index.php" class="option-btn">Back to Home</a>
      </div>
   </form>
</section>

<!-- <?php include 'footer.php'; ?> -->
</body>
</html>
