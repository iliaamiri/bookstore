<?php
/**
 * Created by PhpStorm.
 * User: pcs
 * Date: 20/03/2018
 * Time: 05:38 PM
 */

class book
{
    private $HOST = "localhost";
    private $USER = "root";
    private $PASS = "";
    private $DB = "bookstore";
    private $ServerEmail = "example@example.com";
    function __construct()
    {
        $this->CertificationChecking();
    }
    private function CertificationChecking(){
        if (isset($_COOKIE['id']) AND isset($_SESSION['user'])){ // Checking sessions and cookies
            if (!isset($_SESSION['Certificated'])) {
                USession('user');
                alert("Certification Failed.. redirecting..");
                redirect("0", "http://localhost/bookstore/404.php");
                exit();
            }
            if (!isset($_SESSION['CERTIFICATE_CODE'])){
                USession('user');
                USession('Certificated');
                alert("Certification Failed.. redirecting..");
                redirect("0", "http://localhost/bookstore/404.php");
                exit();
            }
            try{ // try to find the user
                $BOOKconn = new PDO("mysql:host=$this->HOST;dbname=$this->DB",$this->USER,$this->PASS);
                $BOOKconn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $BOOKql = $BOOKconn->prepare("SELECT * FROM user_tbl WHERE Session_id = '$_SESSION[CERTIFICATE_CODE]'");
                $BOOKql->execute();
                if ($BOOKql->rowCount() == 0){ // when it's not in database.user_tbl
                    session_destroy();CookieDelete('id');
                    go("http://localhost/bookstore/404.php");
                    exit();
                }
                $_SESSION['Book_Certificated'] = $BOOKql->fetch(PDO::FETCH_ASSOC)['Session_id']; // Identified Session will be set.
            }catch (PDOException $e){ // catching errors
                die("error");
            }
        }
        elseif (!isset($_COOKIE['id']) OR !isset($_SESSION['user'])){
            go("http://localhost/bookstore/404.php");
        }
    }
    public function AddBook($title,$price,$brief,$age,$off){ // Add a new book function
        try{ // checking the owner or the user who wants to add a new book
            $ADDBconn = new PDO("mysql:host=$this->HOST;dbname=$this->DB",$this->USER,$this->PASS);
            $ADDBconn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $ADDBsqlcheck = $ADDBconn->prepare("SELECT * FROM user_tbl WHERE Session_id = '$_SESSION[Book_Certificated]'");
            $ADDBsqlcheck->execute();
            if ($ADDBsqlcheck->rowCount() === 0){
                session_destroy();CookieDelete('id');
                go("http://localhost/bookstore/404.php");
                exit();
            }
            $OWNER = $ADDBsqlcheck->fetch(PDO::FETCH_ASSOC); // owner's information as a array
            if (isset($title) AND isset($price) AND isset($brief) AND isset($age) AND isset($off)){
                $product_id=rand(10000,99999);
                $date = date("Y-m-d H:i:s");
                        if (preg_match('/^[a-zA-Z0-9-,_.+()?!&\s]*$/i',$title) AND preg_match('/^[0-9.]*$/i',$price) AND preg_match('/^[a-zA-Z0-9-,_.+()?!:@#&\s\/]*$/i',$brief) AND preg_match('/^[0-9-]*$/i',$age) AND preg_match('/^[0-9.]*$/i',$off)){
                        //$var = array($OWNER['Email'],$title,$age,$brief,$price,$product_id,$date,$off);
                        //var_dump($var);
                        //die();
                    $NEWBOOK= $ADDBconn->prepare("INSERT INTO books_tbl (BookOwner,BookName,AgeSort,Brief,Price,Product_ID,AddDate,OFF) VALUE ('$OWNER[Email]','$title','$age','$brief','$price','$product_id','$date','$off')");
                    $NEWBOOK->execute();
                    if (date("a") === 'am'){
                        $NightORDay = "nice day!";
                    }elseif (date("a") === 'pm'){
                        $NightORDay = "good night!";
                    }
                    /*$to = $OWNER['Email'];
                    $subject = "New Book Is Added";
                    $message = "
<html>
<head></head>
<body>
<b>Hello ".$OWNER['Username'].":</b><br />
<b><span>Your Book With Name : <b>".$title."</b> , Was Added In : ".$date." . We Will Check It Out Maximum In 48hours, Thank You! have a ".$NightORDay."</span>
</body>
</html>
";
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= "From: ".$this->ServerEmail."\r\n";
                    //mail($to,$subject,$message,$headers);*/
                }
                else{
                    $_SESSION['msg'] = "Invalid Inputs";
                }
            }
            elseif (!isset($title) OR !isset($price) OR !isset($_POST['Brief']) OR !isset($age) OR !isset($off)){
                $_SESSION['msg'] = "All the inputs must be FILL !";
            }
        }catch (PDOException $e){
            alert("something bad happened!");
            redirect("1","http://localhost/bookstore/404.php");
        }
    }
    public function VerifyBook($product_id){
        if (preg_match('/^[0-9]*$/i',$product_id)){
            try{
                $conn = new PDO("mysql:host=$this->HOST;dbname=$this->DB",$this->USER,$this->PASS);
                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $verifysql= $conn->prepare("UPDATE books_tbl SET ");
                $verifysql->execute();
            }catch (PDOException $e){
                die($e);
            }
        }else{
            die("Invalid Value");
        }
    }
}
// using :
// create a new object as book
$obj = new book();
// functions :
// add a new book
// $obj->AddBook();