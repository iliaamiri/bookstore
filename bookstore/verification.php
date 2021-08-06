<?php include "resources/config.php"; include "resources/customfunctions.php";
if (isset($_COOKIE['user'])){
    $cookie = explode("|",$_COOKIE['user']);
    if ($cookie['1'] == '2'){
        echo "<script>alert('Hi Admin! Why are U here?! lets go to Admins Panel! ( you will redirect to your panel in few seconds )');</script>";
        header("refresh:2;url=http://localhost/bookstore/admin");
    }
    elseif ($cookie['1'] == '1'){
        echo "<script>alert('You have already verified as a user in Bookstore! lets go to your account! ( you will be there in few seconds )');</script>";
        header("refresh:2;url=http://localhost/bookstore/account");
    }
    else{
        unset($_COOKIE['user']);
        setcookie('user',null,time() - 1,'/');
        header("refresh:3;url=http://localhost/bookstore/404.php");
        echo "<script>alert('Something wrong happened!');</script>";
    }
}
elseif (!isset($_COOKIE['user'])){
    if (isset($_POST['Send'])){
        $Email = $_POST['Email'];
        if (preg_match('/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i',$Email)){
            $conn = mysqli_connect($host,$user,$pass,$db)or die("CONNECTION ERROR");
            $sql = mysqli_query($conn,"SELECT * FROM user_tbl WHERE Email = '$Email'");
            if ($sql && mysqli_num_rows($sql) > 0 ){
                $row = mysqli_fetch_assoc($sql);
                if ($row['Status'] == '0'){
                    if ($row['Key_verify'] < 4){
                        $str = "";
                        $chars = array_merge(range('a','z'),range('A','Z'),range('0','9'));
                        $max = count($chars);
                        for ($i = 0;$i < 5;$i++){
                            $rand = mt_rand(0,$max);
                            $str .= $chars[$rand];
                        }
                        $secretKey = md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5($str,'SUPER_SALTY'))),'SUPER_SALTY'))))))),'SUPER_SALTY');
                        $secretEmail = md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5($Email,'SUPER_SALTY'))))),'SUPER_SALTY'))))))))))))),'SUPER_SALTY');
                        setcookie('Verification_process',$secretKey."-".$secretEmail,time() + 3600 * 4,'/');
                        $newVerifyKey = $row['Key_verify'] + 1;
                        $sqlkey = mysqli_query($conn,"UPDATE user_tbl SET Key_verify = '$newVerifyKey' WHERE Email = '$Email'")or die("ERROR IN DATABASE");
                        $to = $row['Email'];
                        $subject = "Please Verify Your Account"; /* NOTE !!!!!!!!! : delete ' in line 46*/
                        $message = "
<html>
<head></head>
<body>
<b>Hello ".$row['Username'].":</b><br />
<b>Here is your Code : </b><span>".$str."</span>
</body>
</html>
";
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        $headers .= "From: khakbaz.babol@drkhakbaz.ir"."\r\n";
                        //mail($to,$subject,$message,$headers);
                        if (isset($_SESSION['VrfyEmail'])){
                            unset($_SESSION['VrfyEmail']);
                        }
                        $_SESSION['Email'] = $Email;
                        header("location:verify.php?id=".$str);
                    }
                    else{
                        try{
                            $connection = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
                            $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                            $sqlban = $connection->prepare("UPDATE user_tbl SET Status = '101' WHERE Email = '$Email'");
                            $sqlban->execute();
                            if (isset($_SESSION['VrfyEmail'])){
                                unset($_SESSION['VrfyEmail']);
                            }
                            echo "<script>alert('You are Banned! Cause you made fun with Verifying process.');</script>";
                            header("refresh:4;url=http://localhost/bookstore");
                        }
                        catch (PDOException $e){
                            die($e);
                        }
                    }
                }
                else{
                    echo '<div class="container-fluid" style="background: white;"><div class="row"><img src="img/checked.png" style="display: inline-block;margin-left: 10px;" alt="checked" class="img-responsive">Your user had verified <a href="http://localhost/bookstore/login">Click </a>For Login</div></div>';
                }
            }
            else{
                header("location:verification.php?EmailExist=False");
            }
        }
        else{
            header("location:verification.php?Email=False");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Verification</title>
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
        <div class="row">
            <a href="http://localhost/bookstore"><img src="img/bookstore.png" alt="bookstore" class="logo"></a>
        </div>
        <div class="col-md-12 box">
            <span class="header">Verify your Account</span>
            <div class="row">
            <span style="display: block;text-align: center;">
                <input type="text" name="Email" class="email" placeholder="Your Email" value="<?php if (isset($_SESSION['VrfyEmail'])){echo $_SESSION['VrfyEmail'];}?>">
            <input type="submit" name="Send" class="submit" value="Let's Verify it!">
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
