<?php
include "header.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register for Groceries</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
       function validateForm() {
    const phone = document.forms["registerForm"]["phone"].value;
    const email = document.forms["registerForm"]["email"].value;

    const phonePattern = /^[0-9]{10}$/;
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!phonePattern.test(phone)) {
        alert("Please enter a valid 10-digit phone number.");
        return false;
    }

    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    return true; // allow form submission
}

    </script>
</head>
<body class="register-body">

<div class="form-container">
    <h1>Registration Form Of Grocery Store</h1>
    <form name="registerForm" action="register.php" method="POST" onsubmit="return validateForm();">
        <label>First Name:</label>
        <input type="text" name="first_name" required pattern="[A-Za-z]+" title="Only letters allowed">

        <label>Last Name:</label>
        <input type="text" name="last_name" required pattern="[A-Za-z]+" title="Only letters allowed">

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Phone:</label>
        <input type="tel" name="phone" required placeholder="e.g. 9876543210" pattern="[0-9]{10}" title="Enter a 10-digit phone number">

        <label>Address:</label>
        <input type="text" name="address" required>

        <label>Password:</label>
        <input type="password" name="password" required minlength="6" title="Password must be at least 6 characters">

        <button type="submit" class="btn">Register</button>
    </form>
</div>

</body>
</html>


</div>
</div>