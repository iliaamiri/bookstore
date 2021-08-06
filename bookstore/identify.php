<?php include "resources/config.php"; include "resources/customfunctions.php"; include "resources/getbrowser.php"; include_once "lib/user.php";
$users=new user();
$login = $users->Login($_POST['Username'],$_POST['Password']);
if ($login === 'TRUE'){
    $Permission = $users->getPermission($_SESSION['Certificate_Code']);
    echo $Permission;
}elseif ($login !== 'TRUE'){
    die("Not Valid Login");
}
    if (isset($_SESSION['user'])){
        if (isset($_SESSION['identify'])){
                $Username = $_SESSION['user'];
                try{
                    $connection = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
                    $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                    $sql = $connection->prepare("SELECT * FROM user_tbl WHERE Username = '$Username'");
                    $sql->execute();
                    $row = $sql->fetch();
                }
                catch (PDOException $e){
                    die($e);
                }
                if ($_SESSION['status'] === $row['Status']){
                    if ($_SESSION['status'] === '1'){
                        $_SESSION['degree']= "Client";
                        $degree = "Client";
                        $location = "location:account";
                    }
                    elseif ($_SESSION['status'] === '2'){
                        $_SESSION['degree']= "Administrator";
                        $_SESSION['user_session']=rand(10000,99999);
                        $degree = "Administrator";
                        $location = "location:admin";
                    }
                    elseif ($_SESSION['status'] === '3'){
                        $_SESSION['degree']= "SecondAdmin";
                        $degree = "SecondAdmin";
                        $location = "location:admin";
                    }
		    else{
			echo '<script>alert("This Account Is Banned For '.$row['Status'].'-Cheating.");</script>';
				CookieDelete('user_info');
				CookieDelete('id');
				CookieDelete('email_verifying');
	    		USession('user');
			header("refresh:1;url=http://localhost/bookstore/404.php");
		    }
		        if (isset($_SESSION['degree'])){
                        $salt = 'SUPER_SALTY';$encodevalue = $Username.$row['Email'].rand(111111,999999);
                        $CertificationCode = md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5($encodevalue)))))))));
                        $obj = new OS_BR();
                        $browser=  $obj->showInfo('browser');
                        date_default_timezone_set("Asia/Tehran");
                        $date = date("Y-m-d H:i:s");
                        try{
                            $sqladd = $connection->prepare("UPDATE user_tbl SET Session_id = '$CertificationCode',Last_Ip = '$_SERVER[REMOTE_ADDR]' , Last_Browser= '$browser' , Last_Login = '$date' , Log = '1' WHERE Username = '$row[Username]'");
                            $sqladd->execute();
                            $_SESSION['CERTIFICATE_CODE'] = $CertificationCode;
                            $browser = $obj->showInfo('browser');
                            setcookie('id',$degree."-".$browser,time() + 3600 * 7,'/');
                            $_SESSION['USERS_EMAIL'] = $row['Email'];
                            USession('status');
                            header($location);
                        }
                        catch (PDOException $e){
                            echo 'Shit! Something is BADLY wrong!';
                            die(header("location:404.php"));
                        }
                }
                }
                elseif ($cookie['1'] != $row['Status']){
                    unset($_SESSION['user']);
                    unset($_SESSION['identify']);
                    header("location:404.php");
                }
        }
        elseif (!isset($_SESSION['identify'])){
            unset($_SESSION['user']);
            header("location:404.php");
        }
    }
    elseif (!isset($_SESSION['user'])){
        header("location:404.php");
    }
?>