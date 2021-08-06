<?php include "resources/config.php";
if (isset($_POST['Signup'])){
    $username = $_POST['Username'];
    $password = sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5($_POST['Password'],'SUPER_SALTY'))))))))))))));
    if (preg_match('/^[a-zA-Z0-9_@.!?]*$/i',$username)){
        if (preg_match('/^[a-zA-Z0-9_@.!?+]*$/i',$_POST['Password'])){
            $conn = mysqli_connect($host,$user,$pass,$db);
            $sql = mysqli_query($conn,"SELECT * FROM user_tbl WHERE Username = '$username'");
            if ($sql && mysqli_num_rows($sql) > 0){
                header("location:signup.php?Error=usernameexist");
            }
            else{
                if (preg_match('/^[0-9]*$/i',$_POST['Age'])){
                    if (preg_match('/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i',$_POST['Email'])){
                        $email = $_POST['Email'];
                        $Age = $_POST['Age'];
                        $sex = $_POST['Sex'];
                        if (isset($sex)){
                            try {
                                $connection = new PDO("mysql:hostname=$host;dbname=test", $user, $pass);
                                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                //$sql = $connection->prepare("INSERT INTO user_tbl (Username,Password,Email,Age,Sex) VALUE ('$username','$password','$email','$Age','$sex')");
                                $sql = $connection->prepare("INSERT INTO table1 (column1,column2,column3,column4,column5) VALUE ('$username','$password','$email','$Age','$sex')");
                                $sql->execute();
                                //$_SESSION['VrfyEmail'] = $email;
                                //header("refresh:2;url=http://localhost/bookstore/verification.php");
                                echo "<script>alert('Great! Your Account Has Been Created! Lets Verify Your Email in 2 seconds!');</script>";
                            } catch (PDOException $e){
                                header("refresh:1;url=http://localhost/bookstore/404.php");
                                die($e);
                            }
                        }
                        else{
                            header("location:signup.php?Error=sex");
                        }
                    }
                    else {
                        header("location:signup.php?Error=email");
                    }
                }
                else{
                    header("location:signup.php?Error=age");
                }
            }
        }
        else{
            header("location:signup.php?Error=pass");
        }
    }
    else{
        header("location:signup.php?Error=username");
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
    <title>Sign Up</title>
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
<form method="post">
    <div class="container">
        <div class="row">
            <a href="http://localhost/bookstore"><img src="img/bookstore.png" alt="bookstore" class="logo"></a>
        </div>
        <div class="col-md-12 box">
            <span class="header">Sign up!</span>
            <div class="row">
            <span style="display: block;text-align: center;">
<input type="text" name="Username" required="required" class="input" placeholder="Your Username">
<input type="password" name="Password" required="required" class="input" placeholder="Your Password">
<input type="text" name="Age" required="required" class="input" placeholder="Your Age">
<input type="email" name="Email" required="required" class="input" placeholder="Your Email">
<B style="font-size: 23px;font-family: 'Arvo';margin-right: 2px;">Sex:</B>
<input type="radio" name="Sex" value="Male"><b style="font-family: 'Lato';font-size: 20px;">Male</b>
<input type="radio" name="Sex" value="Female"><b style="font-family: 'Lato';font-size: 20px;">Female</b><br>
                <p style="color: white;font-size: 25px;font-family: 'Josefin Slab';">
                    <?php
                    if (isset($_GET['Error'])){
                        $q = $_GET['Error'];
                        if ($q == 'usernameexist'){
                            echo 'This username already exists!';
                        }
                        if ($q == 'pass'){
                            echo 'Invalid Password';
                        }
                        if ($q == 'username'){
                            echo 'Invalid Username';
                        }
                        if ($q == 'age'){
                            echo "Invalid Age";
                        }
                        if ($q == 'sex'){
                            echo 'check The sex checkbox';
                        }
                        if ($q == 'email'){
                            echo 'Invalid email';
                        }
                    }
                    ?>
                </p>
    <input type="submit" name="Signup" value="Sign me Up" class="submit">
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