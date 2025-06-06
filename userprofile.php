<?php
@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
   header('location:login.php');
   exit;
}

$message = [];

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$fetch_profile = $result->fetch_assoc();
$stmt->close();

if (isset($_POST['update_profile'])) {
   // Sanitize input safely
   $name = trim($_POST['name']);
   $email = trim($_POST['email']);

   // Update name and email
   $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
   $stmt->bind_param("ssi", $name, $email, $user_id);
   $stmt->execute();
   $stmt->close();

   // Handle image upload securely
   if (!empty($_FILES['image']['name'])) {
      $image_tmp = $_FILES['image']['tmp_name'];
      $image_size = $_FILES['image']['size'];
      $image_error = $_FILES['image']['error'];

      // Check upload error
      if ($image_error !== UPLOAD_ERR_OK) {
         $message[] = 'Error uploading image!';
      } elseif ($image_size > 2 * 1024 * 1024) { // 2MB limit
         $message[] = 'Image too large!';
      } else {
         $image_info = getimagesize($image_tmp);
         if ($image_info === false) {
            $message[] = 'Uploaded file is not a valid image!';
         } else {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $new_image_name = 'user_' . $user_id . '_' . time() . '.' . $ext;
            $image_path = 'uploaded_img/' . $new_image_name;

            if (!is_dir('uploaded_img')) {
               mkdir('uploaded_img', 0777, true);
            }

            if (move_uploaded_file($image_tmp, $image_path)) {
               // Delete old image if exists
               if (!empty($fetch_profile['image']) && file_exists('uploaded_img/' . $fetch_profile['image'])) {
                  unlink('uploaded_img/' . $fetch_profile['image']);
               }

               // Update DB with new image name
               $stmt = $conn->prepare("UPDATE users SET image = ? WHERE id = ?");
               $stmt->bind_param("si", $new_image_name, $user_id);
               $stmt->execute();
               $stmt->close();

               $message[] = 'Image updated!';
            } else {
               $message[] = 'Failed to move uploaded file!';
            }
         }
      }
   }

   // Handle password update
   if (!empty($_POST['update_pass']) && !empty($_POST['new_pass']) && !empty($_POST['confirm_pass'])) {
      // Get current hashed password from DB (server-side)
      $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      $current_hash = $row['password'];
      $stmt->close();

      $old_pass_input = $_POST['update_pass'];
      $new_pass = $_POST['new_pass'];
      $confirm_pass = $_POST['confirm_pass'];

      // Verify old password
      if (!password_verify($old_pass_input, $current_hash)) {
         $message[] = 'Old password incorrect!';
      } elseif ($new_pass !== $confirm_pass) {
         $message[] = 'New passwords do not match!';
      } else {
         // Hash new password and update
         $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
         $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
         $stmt->bind_param("si", $new_hash, $user_id);
         $stmt->execute();
         $stmt->close();
         $message[] = 'Password updated!';
      }
   }

   // Refresh profile data
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
         width: 100px;
         height: 100px;
         border-radius: 50%;
         object-fit: cover;
         margin-bottom: 1rem;
      }
   </style>
</head>
<body>


<div class="register-body">
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
   </div>

<!-- <?php include 'footer.php'; ?> -->
</body>
</html>
