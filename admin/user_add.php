<?php 
session_start();
  require '../config/config.php';
  require '../config/common.php';
  
  if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location:login.php');
  }
  if ($_SESSION['role'] != 1 ) {
    header('Location:login.php');
  }
  if ($_POST) {
      if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4  ) {
        if (empty($_POST['name'])) {
          $nameError = 'Name cannot be empty !';
        }
        if (empty($_POST['email'])) {
          $emailError = 'Email cannot be empty !';
        }
        if (empty($_POST['password'])) {
          $passwordError = 'Password cannot be empty !';
        }
        if (strlen($_POST['password'] ) < 4  ) {
          $passwordError = 'Password must be at least 5 chararcter ! ';
        }
      }else{
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
      if (empty( $_POST['role'])) {
       $role = 0;
      }else{
        $role = 1;
      }
      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
      $stmt->bindValue(':email',$email);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        echo "<script>alert('User email has already existed.')</script>";
      }
      else{
          $stmt = $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES(:name,:email,:password,:role)");
          $result = $stmt->execute(
          array(':name' => $name , ':email' => $email ,':password' => $password , ':role' => $role)
        );
      if ($result) {
        echo "<script>alert('Successfully Added');window.location.href='user_list.php';</script>";
      }
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
                <form action="" method="post" >
                  <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="form-group">
                     <label for="name">name</label><p class="text-danger mt-3 font-weight-bold"><?php echo empty($nameError) ? '' : $nameError; ?></p>
                     <input type="text" class="form-control " name="name" id="name" >                 
                  </div>
                  <div class="form-group">                  
                 
                     <label for="email">Email</label><p class="text-danger mt-3 font-weight-bold"><?php echo empty($emailError) ? '' : $emailError; ?></p>
                     <input type = "email" name="email" id="email" class="form-control" cols="10" rows="10"></input>
                  </div>
                  <div class="form-group">
                     <label for="password">Password</label><p class="text-danger mt-3 font-weight-bold"><?php echo empty($passwordError) ? '' : $passwordError; ?></p>
                     <input type = "password" name="password" id="password" class="form-control" cols="10" rows="10"></input>
                  </div>

                  <input type="checkbox" name="role">
                  <div class="form-group d-flex justify-content-end">
                    <input type="submit" value="SUBMIT" class="btn btn-success mr-3">
                    <a href="index.php" class="btn btn-danger">Back</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
          
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  <?php 
     require_once 'footer.html';
   ?>
