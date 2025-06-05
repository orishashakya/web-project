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

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <!-- swiper image carosel -->
</head>
<body>
    <!-- Header Section -->
     <header class="header">
        <a href="index.php" class="logo"> 
            <i class="fa fa-cart-plus" aria-hidden="true"></i>
            <span>Grocery Plus</span>
        </a>

    <nav class="navbar" >
        <a href="#home">home</a>
        <a href="features.html">features</a>
        <a href="#products">products</a>
        <a href="categories.html">categories</a>
        <a href="#review">review</a>
        <a href="#about">about</a>
    </nav>



    <div class="icons">
        <div class="fa fa-bars" id="menu-btn"></div>
        <div class="fa fa-search" id="search-btn"></div>
        <div class="fa fa-shopping-cart" id="cart-btn"></div>
        <div class="fa fa-user" id="login-btn"></div>
        
    </div>
        <form class="search-form">
            <input type="search" id="search-box" placeholder="Search Here....">
            <label for="search-box" class="fa fa-search"></label>
        </form>

        <form action="login.php" method="POST" class="login-form">
          <h3>Login Now</h3>
          <input type="username" placeholder="Username" class="box" name="username">
          <input type="password" placeholder="Password" class="box" name="password">

          <p>Don't have an account?<a href="register.php">Create Now</a></p>
          <input type="submit" value="Login Now" class="btn">
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
           <a href="#products" class="btn">Shop Now</a>
           
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
