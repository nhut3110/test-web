<?php

include 'connection.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zero</title>

    <!-- FONT AWESOME --> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

    <!-- BOOSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <!-- MAIN CSS  -->
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
    <!-- HEADER  -->
    <header>
            <img src="./image/logo.png" alt="logo">
            <nav>
                <ul>
                    <li><a href="#">TRANG CHỦ</a></li>
                    <li><a href="#">DESIGN</a></li>
                    <li><a href="#">VÁY</a></li>
                    <li><a href="#">ÁO</a></li>
                    <li><a href="#">QUẦN</a></li>
                    <li><a href="#">ABOUT</a></li>
                </ul>
            </nav>
            <div class="header__content">
                <i class="fa-solid fa-user"></i>
                <i class="fa-solid fa-cart-shopping"></i>
                <input type="text" placeholder="Search..">
            </div>
            
    </header>

    <!-- CAROUSEL  -->
    <section class="carousel">
        <div class="carousel__content">
            <img src="./image/Screenshot 2022-10-01 092729.png" alt="">
        </div>
    </section>

    <!-- PRODUCT  -->
    <section class="product">
        <h1>Sản phẩm bán chạy</h1>
        <div class="card-deck">
            <?php  
               $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
               if(mysqli_num_rows($select_products) > 0){
                  while($fetch_products = mysqli_fetch_assoc($select_products)){
            ?>
            <div class="product">
                <img class="card-img-top" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $fetch_products['name']; ?></h5>
                    <p class="card-text"><?php echo $fetch_products['price']; ?>đ</p>
                    <i class="fa-sharp fa-solid fa-cart-plus"></i>
                </div>
            </div>
            <?php
               }
            }else{
               echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>
    </section>

    <!-- FOOTER  -->
    <footer class="footer__contact">
        <div class="contact">
            <p>Gọi Mua Hàng(8:30 - 17:00)</p>
            <h2>
                <i class="fas fa-phone-alt"></i>
                039 5342 134
            </h2>
            <p>tất cả ngay trong tuần</p>
        </div>
        <div class="contact">
            <p>Gọi Mua Hàng(8:30 - 17:00)</p>
            <h2>
                <i class="fas fa-phone-alt"></i>
                039 5342 134
            </h2>
            <p>tất cả ngay trong tuần</p>
        </div>
        <div class="contact">
            <p>Đăng kí nhận thông tin mới</p>
            <div class="box__email">
                <div class="box__dk"></div>
                <div class="button"><p>Đăng Kí</p></div>
            </div>
        </div>
        <div class="contact">
            <p>Theo dõi chúng tôi</p>
            <div class="list__icon">
                <i class="fab fa-facebook"></i>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-youtube"></i>
                <i class="fab fa-twitter"></i>
            </div>
        </div>
    </footer>

</body>

</html>