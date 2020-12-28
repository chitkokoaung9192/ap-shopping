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
if (!empty($_POST['search'])) {
    setcookie('search',$_POST['search'], time() + (86400 * 30), "/");
}else {
    if(empty($_GET['pageno'])){
        unset($_COOKIE['search']);
        setcookie('search',null,-1,'/');
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
        <div class="card-header">
        <h3 class="card-title text-uppercase font-weight-bold">Users Listing</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
        <?php 
        if (!empty($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
         }else{
            $pageno = 1 ;
            }
            $numofrecs = 5;
            $offset = ($pageno -1 ) * $numofrecs ;
           
            if (empty($_POST['search']) && empty($_COOKIE['search'])) {
            $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
            $stmt->execute();
            $rawResult = $stmt->fetchAll();
            $total_pages = ceil(count($rawResult) / $numofrecs);
            $stmt = $pdo->prepare("SELECT * FROM users  ORDER BY id DESC LIMIT $offset,$numofrecs ");
            $stmt->execute();
            $result = $stmt->fetchAll();
            }else{      
            $searchKey = isset($_POST['search']) ?  $_POST['search'] : $_COOKIE['search'] ;
            $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
            $stmt->execute();
            $rawResult = $stmt->fetchAll();                 
            $total_pages = ceil(count($rawResult) / $numofrecs); 

            $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numofrecs ");
            $stmt->execute();
            $result = $stmt->fetchAll();
            }
            ?>
        <a href="user_add.php" class="btn btn-success mb-3">Create New User</a>
        <table class="table table-bordered">
            <thead>                  
            <tr>
                <th style="width: 10px">#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th style="width: 40px">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php 
                if ($result) {
                    $i = 1;
                    foreach ($result as $value) { ?>
                    <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo escape($value['name']) ;?></td>
                <td><?php echo escape($value['email']) ;?></td>
                <td><?php echo $value['role']==1 ?'admin' :'user'?></td>
                <td class="d-flex flex-row">
                <a href="user_edit.php?id=<?php echo $value['id'] ?>" class="btn btn-outline-warning mr-3">Edit</a>
                <a href="user_delete.php?id=<?php echo $value['id'] ?>" 
                    onClick = "return confirm('Are u sure to delete this item ?')"
                    class="btn btn-outline-danger">Delete</a>
                </td>
            </tr>                   
                <?php
                $i++;
            }
                }
                ?>
            </tbody>
        </table>
        </div>
        <!-- /.card-body -->
        <nav>
        <ul class="pagination d-flex justify-content-end mr-3">
            <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
            <li class="page-item <?php if($pageno <= 1 ){ echo 'disabled' ;} ?>" >
            <a class="page-link" href="<?php if($pageno <= 1 ){echo '#';}else{echo "?pageno = ".
            ($pageno-1);} ?>">Previous</a></li>
            <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
            <li class="page-item <?php if($pageno >= $total_pages ){ echo 'disabled' ;} ?>">
            <a class="page-link" href="<?php if($pageno >= $total_pages ){echo '#';}
            else{echo "?pageno=".($pageno+1);} ?>">Next</a>
            </li>
            <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a></li>
        </ul>
        </nav>
        </div>
        <!-- /.card -->
        <!-- /.card -->
        </div>
    </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
    <!-- /.content -->
  <?php 
     require_once 'footer.html';
   ?>