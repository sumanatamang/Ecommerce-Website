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
        <h2>Product List</h2>
        <a href="#" data-toggle="modal" data-target="#add_product_modal" class="btn btn-warning btn-sm">Add Product</a>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>Product No.</th>
              <th>Name</th>
              <th>Image</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Category</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="product_list">
            <!-- Products will be populated via products.js AJAX -->
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="add_product_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Product</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-product-form" enctype="multipart/form-data">
          <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="product_name" class="form-control" placeholder="Enter Product Name" required>
          </div>
          <div class="form-group">
            <label>Category Name</label>
            <select class="form-control category_list" name="category_id" required>
              <option value="">Select Category</option>
            </select>
          </div>
          <div class="form-group">
            <label>Product Description</label>
            <textarea class="form-control" name="product_desc" placeholder="Enter product description"></textarea>
          </div>
          <div class="form-group">
            <label>Product Quantity</label>
            <input type="number" name="product_qty" class="form-control" placeholder="Enter Product Quantity" required>
          </div>
          <div class="form-group">
            <label>Product Price</label>
            <input type="number" name="product_price" class="form-control" placeholder="Enter Product Price" required>
          </div>
          <div class="form-group">
            <label>Product Keywords <small>(e.g., apple, iphone, mobile)</small></label>
            <input type="text" name="product_keywords" class="form-control" placeholder="Enter Product Keywords">
          </div>
          <div class="form-group">
            <label>Product Image <small>(jpg, jpeg, png)</small></label>
            <input type="file" name="product_image" class="form-control" required>
          </div>
          <input type="hidden" name="add_product" value="1">
          <button type="button" class="btn btn-primary add-product">Add Product</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="edit_product_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="edit-product-form" enctype="multipart/form-data">
          <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="e_product_name" class="form-control" placeholder="Enter Product Name" required>
          </div>
          <div class="form-group">
            <label>Category Name</label>
            <select class="form-control category_list" name="e_category_id" required>
              <option value="">Select Category</option>
            </select>
          </div>
          <div class="form-group">
            <label>Product Description</label>
            <textarea class="form-control" name="e_product_desc" placeholder="Enter product description"></textarea>
          </div>
          <div class="form-group">
            <label>Product Quantity</label>
            <input type="number" name="e_product_qty" class="form-control" placeholder="Enter Product Quantity"
              required>
          </div>
          <div class="form-group">
            <label>Product Price</label>
            <input type="number" name="e_product_price" class="form-control" placeholder="Enter Product Price" required>
          </div>
          <div class="form-group">
            <label>Product Keywords <small>(e.g., apple, iphone, mobile)</small></label>
            <input type="text" name="e_product_keywords" class="form-control" placeholder="Enter Product Keywords">
          </div>
          <div class="form-group">
            <label>Product Image <small>(jpg, jpeg, png)</small></label>
            <input type="file" name="e_product_image" class="form-control">
            <img src="../product_images/1.0x0.jpg" class="img-fluid mt-2" width="50" alt="current image">
          </div>
          <input type="hidden" name="pid">
          <input type="hidden" name="edit_product" value="1">
          <button type="button" class="btn btn-primary submit-edit-product">Update Product</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once("./templates/footer.php"); ?>
<script type="text/javascript" src="./js/products.js"></script>