<?php
session_start();

// Redirect logged-in users to index
if (isset($_SESSION["uid"])) {
    header("location:index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ecommerce Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <script src="js/jquery2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="main.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="wait overlay">
    <div class="loader"></div>
</div>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">    
        <div class="navbar-header">
            <a href="index.php" class="navbar-brand">Ecommerce Site</a>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
            <li><a href="index.php"><span class="glyphicon glyphicon-modal-window"></span> Product</a></li>
        </ul>
    </div>
</div>

<p><br/></p>
<p><br/></p>
<p><br/></p>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2"></div>

        <div class="col-md-8" id="login_msg">
            <!--Alert from login form-->
        </div>

        <div class="col-md-2"></div>
    </div>

    <div class="row">
        <div class="col-md-2"></div>

        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading text-center">Customer Login</div>
                <div class="panel-body">
                    <form id="login_form" onsubmit="return false;">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                        </div>

                        <p><br/></p>

                        <div class="row">
                            <div class="col-md-12">
                                <input style="width:100%;" type="submit" name="login_button" value="Login" class="btn btn-success btn-lg">
                            </div>
                        </div>

                        <p><br/></p>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a href="customer_registration.php?register=1">Create a new account</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-footer"></div>
            </div>
        </div>

        <div class="col-md-2"></div>
    </div>
</div>
</body>
</html>
