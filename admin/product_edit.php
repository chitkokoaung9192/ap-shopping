<?php
session_start();
require '../config/config.php';
require '../config/common.php';

if (empty($_SESSION['user_id'] ) && empty($_SESSION['logged_in'] )){
  header ('location:login.php');
}
if ($_SESSION['role'] != 1) {
  header('Location:login.php');
}
if ($_POST) {
  if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category'])
        || empty($_POST['quantity']) || empty($_POST['price']) || empty($_FILES['image'])) {
      if (empty($_POST['name'])) {
          $nameError = 'category name is required';
      }
      if (empty($_POST['description'])) {
          $descError = 'description is required';
      }
      if (empty($_POST['category'])) {
        $catError = 'category is required';
    }
      if (empty($_POST['quantity'])) {
          $qtyError ='quantity is required';
      }elseif (is_numeric($_POST['quantity']) !=1 ) {
          $qtyError ='quantity shoub be integer value';
      }
      if (empty($_POST['price'])) {
        $priceError ='price is required';
    }elseif(is_numeric($_POST['price']) !=1 ){
        $priceError ='price shoub be integer value';
    }
    if (empty($_FILES['image'])) {
        $imageError ='Image is required';
    }
  }else {
    if ($_FILES['image']['name'] != null) {
        $file = 'images/'.($_FILES['image']['name']);
    $imageType = pathinfo($file,PATHINFO_EXTENSION);
    if ($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png' ) {
        echo "<script>alert('image shoub be png ,jpg and jpeg')</script>";
    }else {
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $qty = $_POST['quantity'];
        $price = $_POST['price'];
        $cat = $_POST['category'];
        $image = $_FILES['image']['name'];
        

        move_uploaded_file($_FILES['image']['tmp_name'],$file);
         $stmt = $pdo -> prepare("UPDATE products SET name=:name,description=:description,price=:price,
                                quantity=:quantity,category_id=:category,image=:image");
        $result = $stmt -> execute(
            array(':name'=>$name,':description'=>$desc,':price'=>$price,':quantity'=>$qty,':category'=>$cat,':image'=>$image)
        );
        if ($result) {
            echo "<script>alert('product is updated');window.location.href='index.php';</script>";
        }
    }
    }else {
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $qty = $_POST['quantity'];
        $price = $_POST['price'];
        $cat = $_POST['category'];
        $id =$_POST['id'];

         $stmt = $pdo -> prepare("UPDATE products SET name=:name,description=:description,price=:price,
                                quantity=:quantity,category_id=:category WHERE id=:id");
        $result = $stmt -> execute(
            array(':name'=>$name,':description'=>$desc,':price'=>$price,':quantity'=>$qty,':category'=>$cat,':id'=>$id)
        );
        if ($result) {
            echo "<script>alert('product is updated');window.location.href='index.php';</script>";
        }
    }
  }
}
$stmt =$pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
$stmt->execute();
$result =$stmt->fetchAll();
?>

<?php include('header.php'); ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
              <form action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
              <input type="hidden" name="id" value="<?php echo escape($result[0]['id']) ?>">
              
              <div class="form-group">
                  <label for="">Name</label><P style="color:red;"><?php echo empty($nameError) ?  '' : '*'. $nameError ?></P>
                  <input type="text" name="name" value="<?php echo escape($result[0]['name']) ?>" class="form-control">
              </div>
              <div class="form-group">
                  <label for="">Description</label><P style="color:red;"><?php echo empty($descError) ?  '' : '*'. $descError ?></P>
                  <textarea class="form-control" name="description" id="" cols="30" rows="10"><?php echo escape($result[0]['description']) ?></textarea>
              </div>
              <?php
              $catstmt =$pdo->prepare("SELECT * FROM categories");
              $catstmt->execute();
              $catResult =$catstmt->fetchAll();
              ?>
              <div class="form-group">
                  <label for="">Category</label><P style="color:red;"><?php echo empty($catError) ?  '' : '*'. $catError ?></P>
                <select name="category" id="" class="form-control">
                <option value="">SELECT CATEGORY</option>
                <?php foreach ($catResult as  $value) {?>
                    <?php if ($value['id'] == $result[0]['category_id']) :?>
                    <option value="<?php echo $value['id'] ?>" selected><?php echo $value['name'] ?></option>
                    <?php else :?>
                        <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                    <?php endif ?>
                <?php } ?>
                </select>
              </div>
              <div class="form-group">
                  <label for="">Quantity</label><P style="color:red;"><?php echo empty($qtyError) ?  '' : '*'. $qtyError ?></P>
                  <input type="number" name="quantity" value="<?php echo escape($result[0]['quantity']) ?>" class="form-control">
              </div>
              <div class="form-group">
                  <label for="">Price</label><P style="color:red;"><?php echo empty($priceError) ?  '' : '*'. $priceError ?></P>
                  <input type="number" name="price" value="<?php echo escape($result[0]['price']) ?>" class="form-control">
              </div>
              <div class="form-group">
                  <label for="">Image</label><P style="color:red;"><?php echo empty($imageError) ?  '' : '*'. $imageError ?></P>
                  <img src="images/<?php echo escape($result[0]['image']) ?>" alt="" width="150px" height="150px"><br>
                  <input type="file" name="image" value="">
              </div>
              <div class="form-group">
                  <input class="btn btn-success" type="submit" name="" value="SUBMIT">
                  <a href="index.php" class="btn btn-primary">Back</a>
              </div>
            </form>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  <?php include('footer.html'); ?>
