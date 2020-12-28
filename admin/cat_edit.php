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
      $id = $_POST['id'];

      $stmt =$pdo->prepare("UPDATE categories SET name=:name , description=:description WHERE id=:id ");
      $result =$stmt->execute(
          array(':name'=>$name,':description'=>$description ,':id'=>$id )
      );
      if ($result) {
          echo "<script>alert('category updated is successfully');window.location.href='category.php';</script>";
      }
  }
}

$stmt =$pdo->prepare("SELECT * FROM categories WHERE id=".$_GET['id']);
$stmt ->execute();
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
              <form action="cat_edit.php" method="post">
              <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
              <input type="hidden" name="id" value="<?php echo escape($result[0]['id']) ?>">
              <div class="form-group">
                  <label for="">Name</label><P style="color:red;"><?php echo empty($nameError) ?  '' : '*'. $nameError ?></P>
                  <input type="text" name="name" value="<?php echo escape($result[0]['name']) ?>" class="form-control">
              </div>
              <div class="form-group">
                  <label for="">Description</label><P style="color:red;"><?php echo empty($descError) ?  '' : '*'. $descError ?></P>
                  <textarea class="form-control" name="description" id="" cols="80" rows="10"><?php echo escape($result[0]['description']) ?></textarea>
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
