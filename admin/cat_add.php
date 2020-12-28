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
  if (empty($_POST['name']) || empty($_POST['description'])) {
      if (empty($_POST['name'])) {
          $nameError = 'category name is required';
      }
      if (empty($_POST['description'])) {
          $descError = 'description is required';
      }
  }else {
      $name =$_POST['name'];
      $description = $_POST['description'];

      $stmt =$pdo->prepare("INSERT INTO categories (name,description) VALUES (:name,:description) ");
      $result =$stmt->execute(
          array(':name'=>$name,':description'=>$description)
      );
      if ($result) {
          echo "<script>alert('category added is successfully');window.location.href='category.php';</script>";
      }
  }
}

?>

<?php include('header.php'); ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
              <form action="cat_add.php" method="post">
              <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
              <div class="form-group">
                  <label for="">Name</label><P style="color:red;"><?php echo empty($nameError) ?  '' : '*'. $nameError ?></P>
                  <input type="text" name="name" value="" class="form-control">
              </div>
              <div class="form-group">
                  <label for="">Description</label><P style="color:red;"><?php echo empty($descError) ?  '' : '*'. $descError ?></P>
                  <textarea class="form-control" name="description" id="" cols="80" rows="10"></textarea>
              </div>
              <div class="form-group">
                  <input class="btn btn-success" type="submit" name="" value="SUBMIT">
                  <a href="category.php" class="btn btn-primary">Back</a>
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
