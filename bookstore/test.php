<?php session_start() ;
ob_start();
include "../inc/API.php";  // $pw and $url set in this file
error_reporting(E_ALL ^ E_DEPRECATED);
include('../inc/db-connect.php');


$player = $_SESSION['PlayerNameShow'];
$password = $_SESSION["PasswordShow"];
$params = "Password=$pw&Command=AccountsPassword" . "&Player=" . urlencode($player) . "&PW=" . urlencode($password);
$api = Poker_API($url,$params,true);
if ($api["Result"] != "Ok") die($api["Error"] . "<br/>" . "Click Back Button to retry.");
if ($api["Verified"] != "Yes") die("Password is incorrect. Click Back Button to retry.");

$params = "Password=$pw&Command=AccountsGet&Player=" . urlencode($player);
if ($api["Result"] != "Ok") die($api["Error"] . "<br/>" . "Click Back Button to retry.");
$api = Poker_API($url,$params,true);
$chips3 = $api["Balance"];



if(isset($_POST['passwd']) AND !empty($_POST['passwd']) AND !empty($_POST['confirmpass']) )
{
    $confirmpass=$_POST['confirmpass'];
    $passwd=$_POST['passwd'];
    if ($passwd <> $confirmpass)
    {
    die("<script type='text/javascript'>alert('پسوردهای وارد شده مطابقت ندارند. لطفا مجددا وارد نمایید');</script>");
    }
    else {
   $params2 = "Password=$pw&Command=AccountsEdit&Player=" . urlencode($player). "&PW=" . urlencode($passwd);
    $api2 = Poker_API($url,$params2,true);
    if ($api2["Result"] != "Ok") die($api2["Error"] . "<br/>" . "Try Again");
   echo "" ;
   ?>

<!-- Success Message -->
					<label class="alert alert-success">
						پسورد شما با موفقیت تغییر کرد. شما بطور خودکار به صفحه لاگین منتقل می شوید.
						<div class="message-close icon-remove"></div>
					</label>
	<!-- End Success Message -->
<script type="text/javascript">
         setTimeout("window.location='../panel/logout.php'",2000);
            </script>
   <?php
   }
}
else {echo '<label class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"></button>ورودی نامعتبر می باشد</label>' ; exit ;}
?>

