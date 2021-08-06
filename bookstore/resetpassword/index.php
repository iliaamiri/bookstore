<?php include "../resources/config.php"; include "../resources/customfunctions.php";
$conn = mysqli_connect($host,$user,$pass)or die("Error in connection to the database!");
mysqli_select_db($conn,$db)or die("Error in selecting database");
if (isset($_POST['Check'])){
    $Username = $_POST['Username'];
    if (preg_match('/^[a-zA-Z0-9_@]*$/i',$Username)){
        $Email = $_POST['Email'];
        if (preg_match('/[a-z._%+-]+@[a-z.-]+\.[a-z]{2,4}\b/i',$Email)){
            $sql = mysqli_query($conn,"SELECT * FROM user_tbl WHERE Username = '$Username' AND Email = '$Email'");
            if ($sql && mysqli_num_rows($sql) > 0){
                $row = mysqli_fetch_assoc($sql);
		if ($row['Status'] === '303'){
			echo '<script>alert("You are Banned! cause you made fun with verifying your EMAIL! :.Seeeeeeek.:")</script>';	
                }
		else{
                if ($row['Key_verify'] > 4){
                    if ($row['Status'] !== '202'){
                        mysqli_query($conn,"UPDATE user_tbl SET Status = '202' WHERE Username = '$Username' AND Email = '$Email'")or die("Something wrong");
                    }
                    header("refresh:0;url=http://localhost/bookstore/404.php");
                    echo '<script>alert("You are Banned! cause you made fun with verifying your EMAIL! :.Seeeeeeek.:")</script>';
                }
                else{
                    $salt = 'SUPER_SALTY';
                        $newKey_verify = $row['Key_verify'] + 1;
                        mysqli_query($conn,"UPDATE user_tbl SET Key_verify = '$newKey_verify' WHERE Username = '$Username' AND Email = '$Email'")or die("Something wrong");
                        $SecureEmail = sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5($Email),$salt),$salt)))))))),$salt)),$salt)))),$salt));
                        setcookie('email_verifying',$SecureEmail,time() + 3600 * 4,'/');
                        $_SESSION['client_email_Verifying'] = $Email;
                        $str = "";
                        $array = array_merge(range(0,9),range('a','z'),range('A','Z'));
                        $max = count($array);
                        for ($i = 1;$i < 6;$i++){
                            $rand = mt_rand(0,$max);
                            $str .= $array[$rand];
                        }
                        $VerifyCode = $str;
                        mysqli_query($conn,"UPDATE user_tbl SET VerifyCode = '$VerifyCode' WHERE Username = '$Username' AND Email = '$Email'");
                        $to = $row['Email'];
                        $subject = "Reset Your Password";
                        $message = "
<html>
<head></head>
<body>
<b>Hello ".$row[Username].":</b><br />
<b>Here is your Code : </b><span>".$VerifyCode."</span>
</body>
</html>
";
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        $headers .= "From: khakbaz.babol@drkhakbaz.ir"."\r\n";
                        //mail($to,$subject,$message,$headers);
                        header("location:verify.php?Email=".$Email);

                }
}
            }
            else{
                $_SESSION['msg'] = 'Sorry, One of these two inputs that you filled does not Exist!';
            }
        }
        else{
            $_SESSION['msg'] = 'Invalid Email Value!';
        }
    }
    else{
        $_SESSION['msg'] = 'Invalid Username Value!';
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
            <span class="header">RESET PASSWORD</span>
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
                <input type="email" name="Email" required="required" class="input" placeholder="Your Email">
            <input type="submit" name="Check" class="submit" value="Check It">
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
