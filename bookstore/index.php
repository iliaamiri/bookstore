<?php include "resources/config.php"; include "resources/customfunctions.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Welcome to Online Bookstore</title>
    <link rel="icon" href="img/books-stack-of-three.png" type="image/gif" sizes="16x16">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" type="text/css" rel="stylesheet">
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
<div class="container-fluid">
    <div class="row header">
        <div class="col-md-3" style="padding: 0;">
            <a href="http://localhost/bookstore"><img src="img/bookstore.png" alt="bookstore" class="logo"></a>
        </div>
        <div class="col-md-2">
            <?php
                if (isset($_SESSION['Certificated']) AND isset($_COOKIE['id'])) {
                    if (!isset($_SESSION['user'])){
                        USession('Certificated');
                        header("refresh:0");
                    }
                    if (isset($_SESSION['CERTIFICATE_CODE'])){
                        try{
                            $conn = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
                            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                            $sqlCheck= $conn->prepare("SELECT * FROM user_tbl WHERE Session_id = '$_SESSION[CERTIFICATE_CODE]'");
                            $sqlCheck->execute();
                            if ($sqlCheck->rowCount() === 0){
                                USession('Certificated');
                                echo '<script>alert("Something Happened wrong. Click Back.")</script>';
                                exit();
                            }
                            $row = $sqlCheck->fetch(PDO::FETCH_ASSOC);
                        }
                        catch (PDOException $e){
                            USession('Certificated');
                            header("location:404.php");
                        }
                        echo '<B style="color: white;">Welcome To Bookstore, '.$row['Username'].'!</B>';
                        if ($row['Status'] === '2' AND'3'){
                            echo '<a href="http://localhost/bookstore/admin" class="btn btn-success">My Account</a>';
                        }
                        elseif($row['Status'] === '1'){
                            echo '<a href="http://localhost/bookstore/account" class="btn btn-success">My Account</a>';
                        }
                        echo '<a href="http://localhost/bookstore/logout.php" class="btn btn-danger">Logout<i class="fas fa-sign-out-alt"></i></a>';
                    }
                    elseif (!isset($_SESSION['CERTIFICATE_CODE'])){
                        USession('Certificated');
                        echo '<script>alert("Oh.. you didnt certificated by us! please re-login.");</script>';
                        header("refresh:2;url=http://localhost/bookstore/login");
                    }
            ?><?php }elseif (!isset($_SESSION['Certificated']) OR !isset($_COOKIE['id'])){?>
                    <a href="http://localhost/bookstore/login" class="btn btn-custom-login"><b class="in-l">Login</b></a>
                    <a href="http://localhost/bookstore/signup" class="btn btn-custom-signup"><b class="in-s">Sign Up</b></a>
            <?php }?>
        </div>
        <div class="col-md-1 col-md-push-1"><a href="verification.php">Didn't you verify your account yet?</a></div>
    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>

</html>
