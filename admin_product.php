<?php

include 'connection.php';

session_start();

if(isset($_POST['add_product'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = $_POST['price'];
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = 'Product name already added';
   }else{
      $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, price, image) VALUES('$name', '$price', '$image')") or die('query failed');
      //get product id from products
      $select_product_id_query = mysqli_query($conn, "SELECT `id` FROM products where name= '$name'") or die('query failed');

      while($row = $select_product_id_query->fetch_assoc()){
         $myArray['id'] = $row['id'];
      }
      $prod_id = $myArray['id'];

      //add product id to product option table


      if($add_product_query){
         if($image_size > 2000000){
            $message[] = 'Image size is too large';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Product added successfully!';
            // echo json_encode($select_product_id_query);
         }
      }else{
         $message[] = 'Product could not be added!';
      }
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed hehe');
   header('location:admin_product.php');
}

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];

   mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price' WHERE id = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image file size is too large';
      }else{
         mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }

   header('location:admin_product.php');

}

?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Dashboard products</title>

      <!-- font awesome cdn link  -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

      <!-- Bootstrap CDN -->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

      <!-- custom admin css file link  -->
      <link rel="stylesheet" href="css/styleWeb.css">

   </head>
   <body>
   <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>


   <!-- product CRUD section starts  -->

   <section class="add-products" style="margin-top: 50px;">

      <h1 class="text-center font-rubik py-5 font-weight-bold text-uppercase">products summary</h1>
      <div class="container d-flex justify-content-center mb-5">
         <form action="" method="post" enctype="multipart/form-data" class="card col-lg-8 d-flex justify-content-center p-4 font-rubik border rounded border-dark shadow">
            <h3 class="text-uppercase text-center font-weight-bold mb-4">add product</h3>
            <input type="text" name="name" class="px-2 py-2 border rounded border-dark mb-3" placeholder="enter product name" required>
            <input type="number" min="0" name="price" class="px-2 py-2 border rounded border-dark mb-3" placeholder="enter product price" required>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="px-2 py-2 border rounded border-dark mb-3" required>
            <input type="submit" value="add product" name="add_product" class="btn btn-primary px-2 py-1 text-capitalize">
         </form>
      </div>

   </section>

   <!-- product CRUD section ends -->

   <!-- show products  -->

   <section class="show-products">

      <div class="container d-flex flex-wrap justify-content-center" >
         <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            if(mysqli_num_rows($select_products) > 0){
               while($fetch_products = mysqli_fetch_assoc($select_products)){
         ?>
         <div class="card col-lg-2 py-3 border rounded border-dark m-2 font-rubik shadow">
            <div class=" my-auto">
               <img class="img d-block w-100" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
            </div>
            <div class="name text-capitalize"style="font-size: 1.5rem;"><?php echo $fetch_products['name']; ?></div>
            <div class="price font-weight-bold">Price: <span class="font-weight-normal text-success"><?php echo $fetch_products['price']; ?></span><span class="pl-1 font-weight-normal">VND</span></div>
            <div class="row d-flex flex-wrap justify-content-center">
               <a href="admin_product.php?update=<?php echo $fetch_products['id']; ?>" class="col-md-5 btn btn-primary m-1 p-0 text-capitalize">update</a>
               <a href="admin_product.php?delete=<?php echo $fetch_products['id']; ?>" class="col-md-5 btn btn-danger m-1 p-0 text-capitalize" onclick="return confirm('delete this product?');">delete</a>
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

   <section class="edit-product-form pt-3">
      <h1 class="text-center font-rubik py-2 font-weight-bold text-capitalize">Update information product</h1>
      <div class="container d-flex flex-wrap justify-content-center mt-1 pb-5">
         
         <?php
            if(isset($_GET['update'])){
               $update_id = $_GET['update'];
               $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
               if(mysqli_num_rows($update_query) > 0){
                  while($fetch_update = mysqli_fetch_assoc($update_query)){
         ?>
         
         <form action="" method="post" enctype="multipart/form-data" class="card d-flex col-lg-4 justify-content-center p-4 font-rubik border rounded border-dark shadow">
            <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
            <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
            <div class="py-auto">
               <img class="img d-block w-100 mb-3" src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="product">
            </div>
            <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="px-2 py-2 border rounded border-dark mb-2 text-capitalize" required placeholder="enter product name">
            <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="px-2 py-2 border rounded border-dark mb-2" required placeholder="enter product price">
            <input type="file" class="px-2 py-2 border rounded border-dark mb-2" name="update_image" accept="image/jpg, image/jpeg, image/png">
            <div class="row d-flex flex-wrap justify-content-center">
               <input type="submit" value="update" name="update_product" class="col-md-5 btn btn-primary m-1 p-2 text-capitalize">
               <input type="reset" value="cancel" id="close-update" class="col-md-5 btn btn-light m-1 py-1 text-capitalize">
            </div>
         </form>
      
         <?php
               }
            }
            }else{
               echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
            }
         ?>
      </div>

   </section>
   </body>
</html>