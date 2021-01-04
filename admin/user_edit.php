<?php 
session_start();
  require '../config/config.php';
  require '../config/common.php';

  if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location:login.php');
  }
  if ($_SESSION['role'] != 1) {
    header('Location:login.php');
  }
  if ($_POST) {
    if (empty($_POST['name']) || empty($_POST['email']) ) {
      if (empty($_POST['name'])) {
        $nameError = 'Name cannot be empty !';
      }
      if (empty($_POST['email'])) {
        $emailError = 'Email cannot be empty !';
      }   
      }elseif (!empty($_POST['password']) && strlen($_POST['password'] ) < 4){
        $passwordError = 'Password must be at least 4 chararcter ! ';
      }
      else{
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      
        if (empty($_POST['role'])) {
            $role = 0;
        }else{
            $role= 1;
        }
          $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
          $stmt->execute(
          array(':email'=>$email,':id'=>$id)
          );
          $user = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($user)  {
            echo "<script>alert('User email has already existed.')</script>";
          }
          else{
            if ($password != null) {
              $stmt = $pdo->prepare("UPDATE  users SET name='$name',email ='$email',password ='$password',role='$role' WHERE id = '$id'");        
            }else{
                $stmt = $pdo->prepare("UPDATE  users SET name= '$name',email='$email',role ='$role' WHERE id ='$id'");
            }
          $result = $stmt->execute();
          if ($result) {
            echo "<script>alert('Successfully Updated');window.location.href='user_list.php';</script>";
      }
   }
  }
  } 
  $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ".$_GET['id']);
  $stmt->execute();
  $result = $stmt->fetchAll();
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
                  <div class="form-group">
                    <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">
                     <label for="name">Name</label><p class="text-danger mt-3 font-weight-bold"><?php echo empty($nameError) ? '' : $nameError; ?></p>
                     <input type="text" class="form-control " name="name" id="name" value="<?php echo escape($result[0]['name']) ?>">
                  </div>
                  <div class="form-group">
                     <label for="email">Email</label><p class="text-danger mt-3 font-weight-bold"><?php echo empty($emailError) ? '' : $emailError; ?></p>
                     <input type="text" name="email" id="email" class="form-control" cols="30" rows="10" value="<?php echo escape($result[0]['email']) ?>">
                     </input>
                  </div>
                  <div class="form-group">
                     <label for="password">Password</label><p class="text-danger mt-3 font-weight-bold"><?php echo empty($passwordError) ? '' : $passwordError; ?></p>
                     <span style="font-size:10px;">the user already has password</span>
                     <input type = "password" name="password" id="password" class="form-control" cols="10" rows="10"></input>
                  </div>
                  <div class="form-group">
                     <label for="role" class="d-block">Role</label>
                     <input type="checkbox" name="role" id="role">
                       
                     </input>
                  </div>
                  
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