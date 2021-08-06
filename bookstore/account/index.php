<?php include "../resources/config.php";include "../resources/customfunctions.php";
if (isset($_SESSION['user'])){
    if (isset($_COOKIE['id'])){
        if (isset($_SESSION['degree'])){
            $cookie_id = explode("-",$_COOKIE['id']);
            if ($_SESSION['degree'] == $cookie_id['0']){
                if (isset($_SESSION['CERTIFICATE_CODE'])){
                    if (isset($_SESSION['USERS_EMAIL'])){
                        if (preg_match('/^[a-zA-Z0-9_@]*$/i',$_SESSION['user'])){
                            try{
                                $conn = new PDO("mysql:host=$host;dbname=$db",$user,$pass);
                                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                $checksqll = $conn->prepare("SELECT * FROM user_tbl WHERE Username = '$_SESSION[user]'");
                                $checksqll->execute();
                                $rowsss = $checksqll->fetch(PDO::FETCH_ASSOC);
                                if ($rowsss['Status'] !== '1'){
                                    header("location:../404.php");
                                }
                            }
                            catch (PDOException $e ){
                                die($e);
                            }
                            $CertificateUser = $_SESSION['CERTIFICATE_CODE'];
                            try{
                                $conn = new PDO("mysql:host=$host;dbname=$db",$user,$pass);
                                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                //$conn->set_charset("utf-8");
                                $CertificateUserSQL = $conn->prepare("SELECT * FROM user_tbl WHERE Email = '$_SESSION[USERS_EMAIL]' AND  Session_id = '$CertificateUser'");
                                $CertificateUserSQL->execute();
                                    $sessionRow = $CertificateUserSQL->fetch(PDO::FETCH_ASSOC);
                                    if ($CertificateUser !== $sessionRow['Session_id']){
                                        CookieDelete("id");USession('user');USession('degree');USession('CERTIFICATE_USER');USession('USERS_EMAIL');echo '<script>alert("Certification Failed.. redirecting..");</script>';header("refresh:2;url=http://localhost/bookstore/404.php");
                                    }
                                    $_SESSION['Certificated'] = md5($_SESSION['user']);

                            }
                            catch (PDOException $e){
                                echo '<p color="transparent">'.$e.'</p>';
                                die(header("refresh:2;url=http://localhost/bookstore/404.php"));
                            }
                        }
                        else{
                            USession('user');CookieDelete("id");USession('degree');USession('CERTIFICATE_USER');USession('USERS_EMAIL');header("location:../404.php");
                        }
                    }
                    elseif (!isset($_SESSION['USERS_EMAIL'])){
                        CookieDelete("id");USession('degree');USession('user');USession('CERTIFICATE_USER');header("location:../404.php");
                    }
                }
                elseif (!isset($_SESSION['CERTIFICATE_CODE'])){
                    CookieDelete("id");USession('degree');USession('user');header("location:../404.php");
                }
                else{
                    CookieDelete("id");USession('degree');USession('user');header("location:../404.php");
                }
            }
        }
        elseif (!isset($_SESSION['degree'])){
            CookieDelete("id");USession('user');header("location:../404.php");
        }
    }
    elseif (!isset($_COOKIE['id'])){
        USession('user');header("location:../404.php");
    }
}
elseif (!isset($_SESSION['user'])){
    header("location:http://localhost/bookstore/404.php");
}
?>
<?php if (isset($_SESSION['Certificated'])){?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Profile</title>
    <link rel="icon" href="../img/books-stack-of-three.png" type="image/gif" sizes="16x16">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/panel.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Josefin Slab">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Arvo">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato">
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="row">
        <a href="http://localhost/bookstore/logout.php" class="btn btn-danger" style="border-radius: 360px;">Logout</a>
    </div>
    <div class="col-md-2 sidebar-left">
        <div class="row">
            <a href="http://localhost/bookstore" class="btn btn-custom-sideba"><i class="fa fa-home"></i></a>
            <a href="http://localhost/bookstore/logout.php" class="btn btn-custom-sideba"><i class="fas fa-sign-out-alt"></i></a>
            <a href="http://localhost/bookstore/account" class="btn btn-custom-sideba"><i class="fa fa-user"></i></a>
            <a href="http://localhost/bookstore/account/help" class="btn btn-custom-sideba" style="padding: 7px 15px;"><i class="fa fa-info"></i></a>
        </div>
        <hr>
        <a href="http://localhost/bookstore/account/me"><div class="row sidebar-left-item">Profile</div></a>
        <a href="http://localhost/bookstore/account?d=f=book"><div class="row sidebar-left-item">Your Books</div></a>
        <a href="http://localhost/bookstore/account?d=f=cashIn"><div class="row sidebar-left-item">CashIn</div></a>
        <a href="http://localhost/bookstore/account?d=f=cashOut"><div class="row sidebar-left-item">CashOut</div></a>
        <a href="http://localhost/bookstore/logout.php"><div class="row sidebar-left-item">Logout   <i class="fas fa-sign-out-alt" style="margin-left: 2px;"></i></div></a>
        <a href=""><div class="row sidebar-left-ad1">Ad</div></a>
    </div>
    <div class="col-md-7 box">
        <div class="row">
            <?php
            @$d = $_GET['d'];
            @$f = $_GET['f'];
            if (isset($d)){
                if (isset($f)){
                    if (preg_match('/^[a-zA-Z]*$/i',$d)){
                        if (preg_match('/^[a-zA-Z]*$/i',$f)){
                            if (file_exists($d."/".$f.".php")){
                                include $d."/".$f.".php";
                                echo '<!--';
                            }
                            else{
                                echo 'No File such this exist!';
                            }
                        }
                        else{
                            echo 'No File such this exist!';
                        }
                    }
                    else{
                        echo 'No File such this exist!';
                    }
                }
            }
            ?>
            <!--->
        </div>
    </div>
    <div class="col-md-3 sidebar-right">

    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>

</html>
<?php }elseif (!isset($_SESSION['Certificated'])){die("Redirecting to error PAGE in a second");}?>