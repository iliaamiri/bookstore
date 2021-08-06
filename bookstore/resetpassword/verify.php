<?php include "../resources/config.php";include "../resources/customfunctions.php";
if (isset($_GET['Email'])){
    if (isset($_SESSION['client_email_Verifying'])){
        if ($_SESSION['client_email_Verifying'] === $_GET['Email']){
            if (isset($_COOKIE['email_verifying'])){
                $salt = 'SUPER_SALTY';
                $secDecode = sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5($_SESSION['client_email_Verifying']),$salt),$salt)))))))),$salt)),$salt)))),$salt));
                if ($secDecode === $_COOKIE['email_verifying']){
                    $Email = $_SESSION['client_email_Verifying'];
                    $_SESSION['verify'] = rand(10000,99999);
                    $conn = mysqli_connect($host,$user,$pass)or die("Error in connection to the database!");
                    mysqli_select_db($conn,$db)or die("Error in selecting database");
                    $sql = mysqli_query($conn,"SELECT * FROM user_tbl WHERE Email = '$Email'");
                    if ($sql){
                        $row = mysqli_fetch_assoc($sql);
                        if (isset($_POST['Verify'])){
                            $Code = $_POST['Code'];
                            if (isset($Code)){
                                if (preg_match('/^[a-zA-Z0-9]*$/',$Code)){
                                    if ($row['Key_Try'] > 3 ){
                                        if ($row['Status'] !== '303'){
                                            mysqli_query($conn,"UPDATE user_tbl SET Status = '303' WHERE Email = '$Email'")or die("Something wrong");
                                        }
                                        header("refresh:0;url=http://localhost/bookstore/404.php");
                                        echo '<script>alert("You are Banned cause you tried your code 4 times.")</script>';
                                    }
                                    else{
                                        $newKeyTry = $row['Key_Try'] + 1;
                                        mysqli_query($conn,"UPDATE user_tbl SET Key_Try = '$newKeyTry' WHERE Email ='$Email'");
                                        if (isset($_COOKIE['email_verifying'])){
                                            if ($row['VerifyCode'] === $Code){
                                                USession('verify');
                                                CookieDelete('email_verifying');
                                                mysqli_query($conn,"UPDATE user_tbl SET Key_Try = '0' , VerifyCode = NULL WHERE Email = '$Email' ");
                                                if ($row['Key_verify'] > 0){
                                                    mysqli_query($conn,"UPDATE user_tbl SET Key_verify ='0' WHERE Email = '$Email' ");
                                                }
                                                setcookie('newpass',md5($Email,$salt),time() + 1800 * 1,'/');
                                                header("location:newpassword.php?Email=".$Email);
                                            }
                                            else{
                                                $_SESSION['msg'] = "Wrong Try.. Try It AGAIN : ";
                                            }
                                        }
                                        elseif (!isset($_COOKIE['email_verifying'])){
                                            USession('verify');
                                            USession('client_email_Verifying');
                                            CookieDelete('email_verifying');
                                            header("location:../404.php");
                                        }
                                    }
                                }
                                else{
                                    $_SESSION['msg'] = 'Invalid Code Value!';
                                }
                            }
                            elseif (!isset($Code)){
                                die("Code didn't post");
                            }
                        }
                    }
                    else{
                        $_SESSION['msg'] = "Something is working badly..";
                    }
                }
                else{
                    USession('client_email_Verifying');
                    CookieDelete('email_verifying');
                    header("refresh:1;url=http://localhost/bookstore/404.php");
                    die("404 - Not Found");
                }
            }
            elseif (!isset($_COOKIE['email_verifying'])){
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
<?php if (isset($_SESSION['verify'])){?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Verify Your Email</title>
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
            <span class="header">CHECK YOUR EMAIL</span>
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
                <input type="text" name="Code" required="required" class="check" placeholder="Code">
            <input type="submit" name="Verify" class="submit" value="Verify It">
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
<?php }elseif(!isset($_SESSION['verify'])){die("404 - Not Found");}?>
