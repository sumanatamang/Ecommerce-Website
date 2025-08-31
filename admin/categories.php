<?php session_start(); ?>
<?php include_once("./templates/top.php"); ?>
<?php include_once("./templates/navbar.php"); ?>
<div class="container-fluid">
  <div class="row">
    <?php include "./templates/sidebar.php"; ?>

    <div class="col-12">
      <div class="row mb-3">
        <div class="col-10">
          <h2>Categories</h2>
        </div>
        <div class="col-2">
          <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#add_category_modal">Add Category</button>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Category Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="category_list">
            <!-- Category rows populated via JS -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="add_category_modal" tabindex="-1" role="dialog" aria-labelledby="addCategoryLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryLabel">Add Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-category-form">
          <div class="form-group">
            <label>Category Name</label>
            <input type="text" class="form-control" name="category_name" placeholder="Enter Category Name">
          </div>
          <input type="hidden" name="add_category" value="1">
          <button type="button" class="btn btn-primary add-category">Add Category</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once("./templates/footer.php"); ?>
<script type="text/javascript" src="./js/categories.js"></script>
