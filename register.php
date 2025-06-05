<?php
include 'config.php';

$message = [];

if (isset($_POST['submit'])) {

    // Sanitize inputs
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Hash passwords with md5 (consider using password_hash() for better security)
    $pass = md5($_POST['pass']);
    $cpass = md5($_POST['cpass']);

    // File info
    $image = filter_var($_FILES['image']['name'], FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message[] = 'User email already exists!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Confirm password does not match!';
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $pass, $image);

            if ($stmt->execute()) {
                if ($image_size > 2000000) {
                    $message[] = 'Image size is too large!';
                } else {
                    if (!is_dir('uploaded_img')) {
                        mkdir('uploaded_img', 0755, true);
                    }
                    
                    if (is_uploaded_file($image_tmp_name)) {
                        if (move_uploaded_file($image_tmp_name, $image_folder)) {
                            header('Location: login.php');
                            exit;
                        } else {
                            $message[] = 'Failed to move the uploaded file.';
                        }
                    } else {
                        $message[] = 'Uploaded file is invalid.';
                    }
                    
                }
            } else {
                $message[] = 'Registration failed: ' . $stmt->error;
            }
        }
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
</head>

<body>

    <?php
    if (!empty($message)) {
        foreach ($message as $msg) {
            echo '
      <div class="message">
         <span>' . htmlspecialchars($msg) . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>';
        }
    }
    ?>
<div class="register-body">
    <section class="form-container">
        <form action="" method="POST" enctype="multipart/form-data">
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
