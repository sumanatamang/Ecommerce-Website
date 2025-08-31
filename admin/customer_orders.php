<?php session_start(); ?>
<?php include_once("./templates/top.php"); ?>
<?php include_once("./templates/navbar.php"); ?>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <?php include "./templates/sidebar.php"; ?>

    <!-- Main Content -->
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="pt-3 pb-2 mb-3 border-bottom">
        <h2>Customer Orders</h2>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Order #</th>
              <th>Product Id</th>
              <th>Product Name</th>
              <th>Quantity</th>
              <th>Trx Id</th>
              <th>Payment Status</th>
            </tr>
          </thead>
          <tbody id="customer_order_list">
            <!-- Order rows will be populated via JS -->
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<?php include_once("./templates/footer.php"); ?>

<script type="text/javascript" src="./js/customers.js"></script>