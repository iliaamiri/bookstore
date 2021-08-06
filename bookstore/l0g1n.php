<?php include "resources/config.php";include "resources/customfunctions.php"; include_once "lib/user.php";
if (isset($_COOKIE['id']) AND isset($_SESSION['CERTIFICATE_CODE']) AND isset($_SESSION['Certificated'])){
    go("admin");
}
    if (isset($_POST['Login'])) {

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Login</title>
    <link rel="icon" href="img/books-stack-of-three.png" type="image/gif" sizes="16x16">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/panel.css" type="text/css" rel="stylesheet">
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Josefin Slab">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Arvo">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<form method="post" action="identify.php">
    <div class="container">
        <div class="row">
            <a href="http://localhost/bookstore"><img src="img/bookstore.png" alt="bookstore" class="logo"></a>
        </div>
        <div class="col-md-12 box">
            <span class="header">Login</span>
            <div class="row">
            <span style="display: block;text-align: center;">
                <b style="font-size: 20px;">
                    <?php
                        if (isset($_SESSION['msg'])){
                           echo $_SESSION['msg'];
                           unset($_SESSION['msg']);
                        }
                    ?>
                </b>
                <input type="text" name="Username" required="required" class="input" placeholder="Username">
                <input type="password" name="Password" required="required" class="input" placeholder="Password">
            <input type="submit" name="Login" class="submit" value="Let me In">
                <a href="http://localhost/bookstore/resetpassword/" class="btn btn-warning" style="padding: 10px;-webkit-border-radius: 3600px;-moz-border-radius: 3600px;border-radius: 3600px;">Forgot Your Password?</a>
            </span>
            </div>
        </div>
    </div>
</form>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>
