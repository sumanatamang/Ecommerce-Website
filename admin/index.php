<?php 
session_start();  
if (!isset($_SESSION['admin_id'])) {
    header("location:login.php");
    exit();
}

include "./templates/top.php"; 
include "./templates/navbar.php"; 
?>

<div class="container-fluid">
  <div class="row">
    
    <?php include "./templates/sidebar.php"; ?>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <h2 class="text-center my-4">Admin Details</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="admin_list">
            <!-- Admin records will be loaded here via AJAX -->
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<?php include "./templates/footer.php"; ?>

<!-- Admin JS -->
<script type="text/javascript" src="./js/admin.js"></script>
