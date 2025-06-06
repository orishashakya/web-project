
<?php
include "config.php";
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$fetch_profile = ['image' => 'default.png'];

if ($user_id) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $fetch_profile = $result->fetch_assoc();
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
      

        <title>Grocery Store</title>
        <!-- Code for favicon-->
        <link rel="icon" type="image/icon" href="image/favicon.png">

        
        <!-- Code for favicon-->

        <!-- Code for font awesome cdn -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Code for font awesome cdn -->

        <!-- code for linking css file -->
         <link rel="stylesheet" type="text/css" href="css/style.css">
        <!-- code for linking css file -->

</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <a href="index.php" class="logo"> 
            <i class="fa fa-cart-plus" aria-hidden="true"></i>
            <span>Grocery Plus</span>
        </a>

    <nav class="navbar" >
        <a href="index.php">home</a>
        <a href="features.php">features</a>
        <a href="view_page.php">products</a>
        <a href="orders.php">orders</a>
        <a href="category.php">categories</a>
        <a href="about.php">about</a>
    </nav>



    <div class="icons">
         <a href="search_page.php" class="fa fa-search"></a>
         <?php
         // Count wishlist items
         $wishlist_count = 0;
         $wishlist_sql = "SELECT COUNT(*) as count FROM wishlist WHERE user_id = ?";
         $wishlist_stmt = mysqli_prepare($conn, $wishlist_sql);
         mysqli_stmt_bind_param($wishlist_stmt, "i", $user_id);
         mysqli_stmt_execute($wishlist_stmt);
         $wishlist_result = mysqli_stmt_get_result($wishlist_stmt);
         if ($wishlist_row = mysqli_fetch_assoc($wishlist_result)) {
            $wishlist_count = $wishlist_row['count'];
         }

         // Count cart items
         $cart_count = 0;
         $cart_sql = "SELECT COUNT(*) as count FROM cart WHERE user_id = ?";
         $cart_stmt = mysqli_prepare($conn, $cart_sql);
         mysqli_stmt_bind_param($cart_stmt, "i", $user_id);
         mysqli_stmt_execute($cart_stmt);
         $cart_result = mysqli_stmt_get_result($cart_stmt);
         if ($cart_row = mysqli_fetch_assoc($cart_result)) {
            $cart_count = $cart_row['count'];
         }
         ?>

         <a href="wishlist.php"><i class="fa fa-heart"></i><span>(<?= $wishlist_count; ?>)</span></a>
         <a href="cart.php"><i class="fa fa-shopping-cart"></i><span>(<?= $cart_count; ?>)</span></a>
      </div>

        <?php if (isset($_SESSION['user_id']) && $user_id): ?>
    <!-- Show user menu when logged in -->
    <div class="user-menu">
        <img src="image/<?= htmlspecialchars($fetch_profile['image']) ?>" class="user-icon" id="userMenuToggle" alt="User">
        <div class="user-dropdown" id="userDropdown">
            <a href="userprofile.php">Update Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
<?php elseif (isset($_SESSION['admin_id'])): ?>
    <!-- Show admin menu when admin is logged in -->
    <div class="user-menu">
        <img src="image/default.png" class="user-icon" alt="Admin">
        <div class="user-dropdown">
            <a href="admin_profile.php">Admin Panel</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
<?php else: ?>
    <!-- Show register/login when not logged in -->
    <div class="auth-buttons">
        <a href="register.php">
            <input type="button" value="Register" class="reg-btn">
        </a>
        <a href="login.php">
            <input type="button" value="Login" class="log-btn">
        </a>
    </div>
<?php endif; ?>
    </div>

    <form class="search-form">
        <input type="search" id="search-box" placeholder="Search Here....">
        <label for="search-box" class="fa fa-search"></label>
    </form>
</header>
    <!-- Header Section -->

    <!-- Home Banner Section --> 
     <section class="home" id="home">
        <div class="content">
            <h1 class="organic">100% Fresh and <span>Organic</span></h1>
           <h1>Your Trusted <span>Grocery</span> Partner</h1>
           <p> Fresh Products, Great Prices, and Fast Delivery!<br>
            Everything you need in one place!</p>
            <br>
           <a href="view_page.php" class="btn">Shop Now</a>
           
        </div>
    </section>

    <!-- Home Banner Section -->

    <!-- Feature Section -->
<section class="features" id="features">
    <h1 class="heading">Our <span>Features</span></h1>

    <div class="box-container">
        <div class="box">
            <img src="image/feature-img-1.png">
            <h2>Fresh & Organic</h2>
            <p>Guaranteed High-Quality, Fresh & Organic groceries for a healthier lifestyle.</p>
            <a href="#" class="btn">Read More</a>
        </div>

        <div class="box">
            <img src="image/feature-img-2.png">
            <h2>Free Delivery</h2>
            <p>Enjoy hassle-free shopping with fast and free delivery on all orders.</p>
            <a href="#" class="btn">Read More</a>
        </div>

        <div class="box">
            <img src="image/feature-img-3.png">
            <h2>Easy Payments</h2>
            <p>Multiple secure payment options for a seamless checkout experience.</p>
            <a href="#" class="btn">Read More</a>
        </div>
    </div>
</section>

    <!-- Feature Section -->

    <!-- Product Section -->
    <section class="products" id="products">
        <h1 class="heading">Our <span>Products</span></h1>

        <div class="slider-wrap">
          <img src="image/arrow.png" id="backbtn" alt="Previous">
      
          <div class="slider" id="slider">
            <div class="slide">
                <div class="box">
              <img src="image/product-img-1.png">

              <h1>Fresh Apples</h1>
              <div class="price">
                <div class="old-price">Rs 700.00</div>
                <div class="new-price">Rs 650.00 / KG</div>
              </div>
              <div class="stars">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-half"></i>
              </div>
              <br>
              <a href="#" class="btn">Add to Cart</a>
              </div>
            </div>
      
            <div class="slide">
                <div class="box">
                  <img src="image/product-img-2.png" alt="Fresh Oranges">
                  <h1>Fresh Oranges</h1>
                  <div class="price">
                    <div class="old-price">Rs 700.00</div>
                    <div class="new-price">Rs 650.00 / KG</div>
                  </div>
                  <div class="stars">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-half"></i>
                  </div>
                  <br>
                  <a href="#" class="btn">Add To Cart</a>
                </div>
              </div>
              
      
            <div class="slide">
                <div class="box">
              <img src="image/product-img-3.png">
              <h1>Fresh Blueberries</h1>
              <div class="price">
                <div class="old-price">Rs 700.00</div>
                <div class="new-price">Rs 650.00 / KG</div>
              </div>
              <div class="stars">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-half"></i>
              </div>
              <br>
              <a href="#" class="btn">Add to Cart</a>
            </div>
            </div>
      
            <div class="slide">
                <div class="box">
              <img src="image/product-img-4.png">
              <h1>Fresh Grapes</h1>
              <div class="price">
                <div class="old-price">Rs 700.00</div>
                <div class="new-price">Rs 650.00 / KG</div>
              </div>
              <div class="stars">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-half"></i>
              </div>
              <br>
              <a href="#" class="btn">Add to Cart</a>
            </div>
            </div>

            <div class="slide">
                <div class="box">
              <img src="image/product-img-6.png">
              <h1>Fresh Carrots</h1>
              <div class="price">
                <div class="old-price">Rs 700.00</div>
                <div class="new-price">Rs 650.00 / KG</div>
              </div>
              <div class="stars">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-half"></i>
              </div>
              <br>
              <a href="#" class="btn">Add to Cart</a>
            </div>
            </div>
            <div class="slide">
                <div class="box">
              <img src="image/product-img-12.png">
              <h1>Fresh WaterMelon</h1>
              <div class="price">
                <div class="old-price">Rs 700.00</div>
                <div class="new-price">Rs 650.00 / KG</div>
              </div>
              <div class="stars">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-half"></i>
              </div>
              <br>
              <a href="#" class="btn">Add to Cart</a>
            </div>
            </div>
            <div class="slide">
                <div class="box">
              <img src="image/product-img-13.png">
              <h1>Fresh Fishes</h1>
              <div class="price">
                <div class="old-price">Rs 700.00</div>
                <div class="new-price">Rs 650.00 / KG</div>
              </div>
              <div class="stars">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-half"></i>
              </div>
              <br>
              <a href="#" class="btn">Add to Cart</a>
            </div>
            </div>
          </div>
      
          <img src="image/arrow.png" id="nextbtn" alt="Next">
        </div>
      </section>
      
</section>
<!-- Product Section -->

<!-- Categories -->
 <section class="categories" id="categories">
    <h1 class="heading">Product<span>Categories</span></h1>
    <div class="box-container">
        <div class="box">
            <img src="image/cat-1.png">
            <h2>Vegetables</h2>
            <p>Upto 30% off</p>
            <a href="#" class="btn">Shop Now</a>
        </div>
    
    <!--      -->
    <div class="box">
        <img src="image/cat-2.png">
        <h2>Fresh Fruits</h2>
        <p>Upto 15% off</p>
        <a href="#" class="btn">Shop Now</a>
    </div>
    <!--      -->
    <div class="box">
        <img src="image/cat-3.png">
        <h2>Dairy Products</h2>
        <p>Upto 20% off</p>
        <a href="#" class="btn">Shop Now</a>
    </div>
    <!--  -->
    <div class="box">
        <img src="image/cat-4.png">
        <h2>Fresh Meats</h2>
        <p>Upto 10% off</p>
        <a href="#" class="btn">Shop Now</a>
    </div>
</div>
 </section>
<?php

?>















    

<script src="js/script.js"></script>   
 
</body>


</html>
