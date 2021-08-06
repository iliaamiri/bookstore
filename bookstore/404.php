<?php include "resources/config.php";
if (isset($_POST['Send'])){
    $ip = $_SERVER['REMOTE_ADDR'];
    $info = $_SERVER['HTTP_USER_AGENT'];
    $data = "Client with Ip : ".$ip." And use ".$info." , ||| HE HEADED TO 404.php in ".date("Y/m/d.l")." IN ".date("h:i:sa")."\r\n";
    $filesize = filesize("resources/404_log.txt") * .0009765625;
    if ($filesize > 100){
        $file = fopen("resources/404_log.txt","w");
    }
    else{
        $file = fopen("resources/404_log.txt","a");
    }
    fwrite($file,$data);
    fclose($file);
    echo "<script>alert('Thank you for your helpful feedback! We will fix it ;) ( you will redirect to Home in few seconds )');</script>";
    header("refresh:2;url=http://localhost/bookstore");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>404 - Not Found</title>
    <link rel="icon" href="img/books-stack-of-three.png" type="image/gif" sizes="16x16">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/404.css" type="text/css" rel="stylesheet">
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
        <span class="e404">404</span>
        <div class="row">
            <span style="text-align: center;display: block;font-size: 30px;"><b class="sorry">Sorry!</b> <i style="font-family: 'Lato';">The page you requested was not found!....</i></span>
        </div>
        <div class="row">
            <span style="display: block;text-align: center;">
            <input type="submit" name="Send" class="submit" value="Tell us your feedback">
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