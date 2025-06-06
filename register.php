<?php
include 'config.php';

$message = [];

if (isset($_POST['submit'])) {

    // Sanitize inputs
    $name = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    // File info
    $image = $_FILES['image'] ?? null;

    // Basic validation
    if (!$email) {
        $message[] = 'Invalid email address!';
    }
    if (strlen($pass) < 6) {
        $message[] = 'Password must be at least 6 characters!';
    }
    if ($pass !== $cpass) {
        $message[] = 'Confirm password does not match!';
    }
    if (!$image || $image['error'] !== UPLOAD_ERR_OK) {
        $message[] = 'Please upload a valid image file.';
    } else {
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        $image_ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        if (!in_array($image_ext, $allowed_ext)) {
            $message[] = 'Invalid image format! Only JPG, JPEG, PNG allowed.';
        }
        if ($image['size'] > 2 * 1024 * 1024) {
            $message[] = 'Image size must be less than 2MB.';
        }
    }

    // If no errors so far
    if (empty($message)) {

        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message[] = 'User email already exists!';
        } else {
            // Hash password securely
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

            // Create upload folder if doesn't exist
            if (!is_dir('uploaded_img')) {
                mkdir('uploaded_img', 0755, true);
            }

            // Generate unique file name to avoid overwriting
            $new_image_name = uniqid('user_', true) . '.' . $image_ext;
            $image_folder = 'uploaded_img/' . $new_image_name;

            // Move uploaded file
            if (!move_uploaded_file($image['tmp_name'], $image_folder)) {
                $message[] = 'Failed to upload image.';
            } else {
                // Insert user into database
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $email, $hashed_pass, $new_image_name);

                if ($stmt->execute()) {
                    header('Location: login.php');
                    exit;
                } else {
                    $message[] = 'Registration failed: ' . $stmt->error;
                }
            }
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <link rel="stylesheet" href="css/components.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/all.min.css" />
</head>

<body>

<?php
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '
      <div class="message">
         <span>' . htmlspecialchars($msg) . '</span>
         <i class="fa fa-times" onclick="this.parentElement.remove();"></i>
      </div>';
    }
}
?>

<div class="register-body">
    <section class="form-container">
        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
            <h3>Register now</h3>
            <input type="text" name="name" class="box" placeholder="Enter your name" required />
            <input type="email" name="email" class="box" placeholder="Enter your email" required />
            <input type="password" name="pass" class="box" placeholder="Enter your password" required />
            <input type="password" name="cpass" class="box" placeholder="Confirm your password" required />
            <input type="file" name="image" class="box" required accept="image/jpg, image/jpeg, image/png" />
            <input type="submit" value="Register now" class="btn" name="submit" />
            <p>Already have an account? <a href="login.php">Login now</a></p>
        </form>
    </section>
</div>

</body>

</html>
