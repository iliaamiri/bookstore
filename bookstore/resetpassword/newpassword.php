<?php include "../resources/config.php";include "../resources/customfunctions.php";
if (isset($_GET['Email'])){
    if (isset($_SESSION['client_email_Verifying'])){
        if ($_SESSION['client_email_Verifying'] === $_GET['Email']){
            if (isset($_COOKIE['newpass'])){
                if ($_COOKIE['newpass'] === md5($_SESSION['client_email_Verifying'],'SUPER_SALTY')){
                    $_SESSION['verifypass'] = rand(100000,999999999);
                    if (isset($_POST['Change'])){
                        $Password1 = $_POST['Password1'];
                        if (preg_match('/^[a-zA-Z0-9_@+.!?]*$/i',$Password1)){
                            $Password2 = $_POST['Password2'];
                            if (preg_match('/^[a-zA-Z0-9_@+.!?]*$/i',$Password2)){
                                if ($Password1 === $Password2){
                                    $secPassword = sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5($Password2,'SUPER_SALTY'))))))))))))));
                                    try{
                                        $conn = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
                                        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                        $sql = $conn->prepare("UPDATE user_tbl SET Password = '$secPassword' WHERE Email = '$_SESSION[client_email_Verifying]'");
                                        $sql->execute();
                                        USession('verifypass');
                                        USession('client_email_Verifying');
                                        CookieDelete('newpass');
                                        header("refresh:0;url=http://localhost/bookstore/l0g1n.php");
                                        echo '<script>alert("Your New Password has updated successfully! Lets LOGIN!")</script>';
                                    }
                                    catch (PDOException $e){
                                        die($e);
                                    }
                                }
                                else{
                                    $_SESSION['msg'] = 'Passwords must be match!';
                                }
                            }
                            else{
                                $_SESSION['msg'] = 'Invalid Password Value!';
                            }
                        }
                        else{
                            $_SESSION['msg'] = 'Invalid Password Value!';
                        }
                    }
                }
                else{
                    USession('client_email_Verifying');
                    CookieDelete('newpass');
                    header("refresh:1;url=http://localhost/bookstore/404.php");
                    die("404 - Not Found");
                }
            }
            elseif (!isset($_COOKIE['newpass'])){
                USession('client_email_Verifying');
                header("refresh:1;url=http://localhost/bookstore/404.php");
                die("404 - Not Found");
            }
        }
        else{
            USession('client_email_Verifying');
            header("refresh:1;url=http://localhost/bookstore/404.php");
            die("404 - Not Found");
        }
    }
    elseif(!isset($_SESSION['client_email_Verifying'])){
        header("location:../404.php");
    }
}
elseif (!isset($_GET['Email'])){
    header("location:../404.php");
}
?>
<?php if (isset($_SESSION['verifypass'])){?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Reset Password</title>
        <link rel="icon" href="img/books-stack-of-three.png" type="image/gif" sizes="16x16">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/panel.css" type="text/css" rel="stylesheet">
        <link href="../css/style.css" type="text/css" rel="stylesheet">
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
    <form method="post">
        <div class="container">
            <div class="row">
                <a href="http://localhost/bookstore"><img src="../img/bookstore.png" alt="bookstore" class="logo"></a>
            </div>
            <div class=" col-md-12 box">
                <span class="header">NEW PASSWORD</span>
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
                <input type="password" name="Password1" required="required" class="input" placeholder="New Password">
                <input type="password" name="Password2" required="required" class="input" placeholder="Re-Type to be sure">
            <input type="submit" name="Change" class="submit" value="Change It">
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
<?php }elseif(!isset($_SESSION['verifypass'])){die("404 - Not Found");}?>
