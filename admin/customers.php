<?php 
session_start(); 
include_once("./templates/top.php"); 
include_once("./templates/navbar.php"); 
?>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <?php include "./templates/sidebar.php"; ?>

    <!-- Main Content -->
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Customers</h2>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>Customer No.</th>
              <th>Name</th>
              <th>Email</th>
              <th>Mobile</th>
              <th>Address</th>
            </tr>
          </thead>
          <tbody id="customer_list">
            <!-- Customer rows will be populated via customers.js -->
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<?php include_once("./templates/footer.php"); ?>
<script type="text/javascript" src="./js/customers.js"></script>
