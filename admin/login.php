<?php include "./templates/top.php"; ?>
<?php include "./templates/navbar.php"; ?>

<div class="container">
    <div class="row justify-content-center" style="margin:100px 0;">
        <div class="col-md-4">
            <h4 class="text-center mb-4">Admin Login</h4>
            
            <!-- Message area -->
            <div class="message mb-3"></div>
            
            <form id="admin-login-form" method="POST">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
                </div>
                
                <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                </div>
                
                <!-- Hidden flag to trigger admin_login in Admin.php -->
                <input type="hidden" name="admin_login" value="1">
                
                <!-- Use type="submit" instead of button -->
                <button type="submit" class="btn btn-success w-100 mt-4 login-btn">Login</button>
            </form>
        </div>
    </div>
</div>

<?php include "./templates/footer.php"; ?>

<!-- Admin login JS -->
<script type="text/javascript" src="./js/admin.js"></script>
