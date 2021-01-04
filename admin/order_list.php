<?php
session_start();
require '../config/config.php';
require '../config/common.php';

if (empty($_SESSION['user_id'] ) && empty($_SESSION['logged_in'] )){
  header ('location:login.php');
}
if ($_SESSION['role'] != 1) {
  header ('location:login.php');
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
                <h3 class="card-title">Order listings</h3>
              </div>

            <?php
            if (!empty($_GET['pageno'])) {
            $pageno =$_GET['pageno'];
            }else {
            $pageno =1;
            }
            $numOfrecs =5;
            $offset =($pageno - 1) * $numOfrecs;
            
            $stmt =$pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC");
            $stmt ->execute();
            $rawResult =$stmt->fetchAll();
            $total_pages =ceil(count($rawResult) / $numOfrecs );

            $stmt =$pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC LIMIT $offset,$numOfrecs ");
            $stmt ->execute();
            $result =$stmt->fetchAll();
            ?>
              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <a type="button" class="btn btn-success" href="cat_add.php">New Category</a>
                </div><br>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>User</th>
                      <th>Total Prices</th>
                      <th>Order Date</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                    if ($result) {
                      $i=1;
                      foreach ($result as $value) { ?>
                      <?php
                      $userstmt =$pdo->prepare("SELECT * FROM users WHERE id=".$value['user_id']);
                      $userstmt->execute();
                      $userResult=$userstmt->fetchAll();
                      ?>
                    <tr>
                      <td><?php echo $i;?></td>
                      <td><?php echo escape($userResult[0]['name']) ?></td>
                      <td><?php echo escape($value['total_price']) ?></td>
                      <td><?php echo escape(date('y-m-d',strtotime($value['order_date']))) ?></td>
                        <td>
                          <div class="btn-group">
                            <div class="container">
                              <a type="button" class="btn btn-default" href="order_detail.php?id=<?php echo $value['id'] ?>">View</a>
                            </div>
                        </div>
                        </td>
                    </tr>
                    <?php
                    $i++;
                      }
                    }
                    ?>
                  </tbody>
                </table><br>
                <nav aria-label="Page navigation example" style ="float:right">
                <ul class="pagination">
                  <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                  <li class="page-item <?php if($pageno <=1){echo 'distable';} ?>">
                  <a class="page-link" href="<?php if($pageno <=1) {echo '#';}else{ echo "?pageno=".($pageno-1);} ?>">Previous</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                  <li class="page-item <?php if($pageno >= $total_pages){echo 'distable';} ?>">
                  <a class="page-link" href="<?php if($pageno >= $total_pages){echo '#';}else{echo "?pageno=".($pageno+1);} ?>">Next</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a></li>
                </ul>
              </nav>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  <?php include('footer.html'); ?>
