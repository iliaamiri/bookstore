<?php include "resources/config.php";
if (isset($_COOKIE['Verification_process'])){
    if (isset($_SESSION['Email'])){
        if (isset($_POST['Check'])){
            $Key = $_POST['Key'];
            if (preg_match('/^[a-zA-Z0-9]*$/i',$Key)){
                $cookie = explode("-",$_COOKIE['Verification_process']);
                $Email = $_SESSION['Email'];
                $secretEmail = md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5($Email,'SUPER_SALTY'))))),'SUPER_SALTY'))))))))))))),'SUPER_SALTY');
                if ($cookie['1'] == $secretEmail){
                    $conn = mysqli_connect($host,$user,$pass,$db)or die("ERROR IN CONNECTION");
                    $sql = mysqli_query($conn,"SELECT * FROM user_tbl WHERE Email = '$Email'")or die("SYNTAX ERROR IN DATABASE");
                    $row = mysqli_fetch_assoc($sql);
                    if ($row['Key_Try'] < 4){
                        $secretKey = md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5($Key,'SUPER_SALTY'))),'SUPER_SALTY'))))))),'SUPER_SALTY');
                        if ($cookie['0'] == $secretKey){
                            mysqli_query($conn,"UPDATE user_tbl SET Key_verify = '0', Key_Try = '0', Status = '1' WHERE Email = '$Email'")or die("OH SOME ERROR HAPPENED!");
                            unset($_COOKIE['Verification_process']);
                            setcookie('Verification_process',null,time() - 1,'/');
                            unset($_SESSION['Email']);
                            echo '<div class="container-fluid" style="background: white;"><div class="row"><img src="img/checked.png" style="display: inline-block;margin-left: 10px;" alt="checked" class="img-responsive">Your account has verified successfully! Welcome to Bookstore!!!! </div></div>';
                            header("refresh:2;url=http://localhost/bookstore");
                        }
                        elseif ($cookie['0'] != $secretKey){
                            $newKeyTry = $row['Key_Try'] + 1;
                            try{
                                $connections = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
                                $connections->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                $sqladd = $connections->prepare("UPDATE user_tbl SET Key_Try = '$newKeyTry' WHERE Email = '$Email'");
                                $sqladd->execute();
                                header("location:verify.php?KeyMatch=False");
                            }
                            catch (PDOException $ee){
                                die($ee);
                            }
                        }
                    }
                    else{
                        try{
                            $connection = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
                            $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                            $sqlban = $connection->prepare("UPDATE user_tbl SET Status = '101' WHERE Email = '$Email'");
                            $sqlban->execute();
                            echo "<script>alert('You are Banned! Cause you made fun with Verifying process.');</script>";
                            header("refresh:4;url=http://localhost/bookstore");
                        }
                        catch (PDOException $e){
                            die($e);
                        }
                    }
                }
                elseif ($cookie['1'] != $secretEmail){
                    unset($_COOKIE['Verification_process']);
                    setcookie('Verification_process',null,time() - 1,'/');
                    echo "<script>alert('Something wrong happened!');</script>";
                    header("location:404.php");
                }
            }
            else{
                header("location:verify.php?Key=False");
            }
        }
    }
    elseif (!isset($_SESSION['Email'])){
        header("location:404.php");
    }
}
elseif (!isset($_COOKIE['Verification_process'])){
    header("location:404.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Verify your Email</title>
    <link rel="icon" href="img/books-stack-of-three.png" type="image/gif" sizes="16x16">
    <link href="css/bootstrap.min.css" rel="stylesheet">
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
<form method="post">
    <div class="container">
        <div class="col-md-12 box">
            <span class="header">Check your Email</span>
            <div class="row">
            <span style="display: block;text-align: center;">
                <input type="text" name="Key" class="check" placeholder="Type your code" maxlength="5">
            <input type="submit" name="Check" class="submit" value="Check The Code">
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

