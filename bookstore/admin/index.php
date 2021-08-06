<?php include "../resources/config.php";include"../resources/customfunctions.php";
if (isset($_SESSION['degree'])){
    if (isset($_COOKIE['id'])){
        $cookie_id = explode("-",$_COOKIE['id']);
        if ($cookie_id['0'] == $_SESSION['degree']){
            if (isset($_SESSION['user'])){
                if (isset($_SESSION['USERS_EMAIL'])){
                    if (isset($_SESSION['CERTIFICATE_CODE'])){
                        if (preg_match('/^[a-zA-Z0-9_@]*$/',$_SESSION['user'])){
                            try{
                                $conn = new PDO("mysql:host=$host;dbname=$db",$user,$pass);
                                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                $checksqll = $conn->prepare("SELECT * FROM user_tbl WHERE Username = '$_SESSION[user]'");
                                $checksqll->execute();
                                $rowsss = $checksqll->fetch(PDO::FETCH_ASSOC);
                                if ($rowsss['Status'] !== '2' AND '3'){
                                    header("location:../404.php");
                                }
                            }
                            catch (PDOException $e ){
                                die($e);
                            }
                                $CertificateUser = $_SESSION['CERTIFICATE_CODE'];
                                try{
                                    //$conn->set_charset("utf-8");
                                    $CertificateUserSQL = $conn->prepare("SELECT * FROM user_tbl WHERE Session_id = '$CertificateUser'");
                                    $CertificateUserSQL->execute();
                                    $sessionRow = $CertificateUserSQL->fetch(PDO::FETCH_ASSOC);
                                    if ($CertificateUserSQL->rowCount() === 0){
                                        echo '<script>alert("Certification Failed.. redirecting..");</script>';header("refresh:2;url=http://localhost/bookstore/login");
                                        exit();
                                    }
                                    if ($CertificateUser !== $sessionRow['Session_id']){
                                        CookieDelete("id");USession('CERTIFICATE_USER');USession('USERS_EMAIL');echo '<script>alert("Certification Failed.. redirecting..");</script>';header("refresh:2;url=http://localhost/bookstore/login");
                                    }
                                    else{
                                        $_SESSION['Certificated'] = md5($_SESSION['user']);
                                        $_SESSION['Welcome_admin'] = rand(11111,999999);
                                        @$d = $_GET['d'];
                                        @$f = $_GET['f'];
                                        //include "../lib/book.php";
                                        echo '<div style="background: whitesmoke">Welcome to your panel <i style="color: green;font-size: 25px;">' . $_SESSION['user'] . '</i> ( ' . '<b style="color: red;">' . $_SESSION['degree'] . '</b> )</div><br />';
                                    }
                                }
                                catch (PDOException $e){
                                    echo '<p color="transparent">'.$e.'</p>';die(header("refresh:2;url=http://localhost/bookstore/404.php"));
                                }
                    }
                    else{echo '<script>alert("Certification Failed.. redirecting..");</script>';header("refresh:2;url=http://localhost/bookstore/login");
                            exit();
                        }
                    }
                    elseif (!isset($_SESSION['CERTIFICATE_CODE'])) {USession('degree');USession('USERS_EMAIL');CookieDelete("id");CookieDelete("user_info");header("location:../404.php");}
                }
                elseif (!isset($_SESSION['USERS_EMAIL'])){USession('degree');CookieDelete("id");CookieDelete("user_info");header("location:../404.php");}
            }
            elseif (!isset($_SESSION['user'])){
                session_destroy();
                CookieDelete('id');
                header("location:../404.php");
            }
            }
            else{USession('degree');CookieDelete("id");header("location:../404.php");}}
            elseif (!isset($_COOKIE['id'])){USession('degree');header("location:../404.php");}}
            elseif (!isset($_SESSION['degree'])){header("location:../404.php");}
?>
<?php if (isset($_SESSION['Welcome_admin']) AND isset($_SESSION['CERTIFICATE_CODE'])){?>
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
        <link href="../css/style.css" type="text/css" rel="stylesheet">
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
                <a href="http://localhost/booksotre/admin" class="btn btn-custom-sideba"><i class="fa fa-user"></i></a>
                <a href="http://localhost/booksotre/admin/info.html" class="btn btn-custom-sideba" style="padding: 7px 15px;"><i class="fa fa-info"></i></a>
            </div>
            <hr>
            <a href="http://localhost/bookstore/admin/index.php?d=profile&f=index"><div class="row sidebar-left-item">Profile</div></a>
            <a href="http://localhost/bookstore/admin/index.php?d=users&f=index"><div class="row sidebar-left-item">Users</div></a>
            <a href="http://localhost/bookstore/admin/Book"><div class="row sidebar-left-item">Books</div></a>
            <a href="http://localhost/bookstore/admin/Book"><div class="row sidebar-left-item">Books</div></a>
            <a href="http://localhost/bookstore/admin/index.php?d=&f="><div class="row sidebar-left-item">CashIn</div></a>
            <a href="http://localhost/bookstore/admin/index.php?d=&f="><div class="row sidebar-left-item">CashOut</div></a>
            <a href="http://localhost/bookstore/admin/index.php?d=&f="><div class="row sidebar-left-item">Sliders</div></a>
            <a href="http://localhost/bookstore/admin/index.php?d=&f="><div class="row sidebar-left-item">Menus</div></a>
            <a href="http://localhost/bookstore/logout.php"><div class="row sidebar-left-item">Logout   <i class="fas fa-sign-out-alt" style="margin-left: 2px;"></i></div></a>
            <a href=""><div class="row sidebar-left-ad1">Ad</div></a>
        </div>
        <div class="col-md-7 box">
            <div class="row">
                <?php if(isset($d)){if(isset($f)){if(preg_match('/^[a-zA-Z]*$/i',$d)){if(preg_match('/^[a-zA-Z]*$/i',$f)){if(file_exists($d."/".$f.".php")){include $d."/".$f.".php";echo'<!--';}else{echo'No File such this exist!';}}else{echo'No File such this exist!';}}else{echo'No File such this exist!';}}}?>
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
<?php }elseif(!isset($_SESSION['Welcome_admin']) AND !isset($_SESSION['CERTIFICATE_CODE'])){die('something wrong');}?>
