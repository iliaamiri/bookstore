<?php require_once 'database.php';
class user
{
    private $table = "user_tbl";
    private static $permission;
    public function Identify($id){
        if (preg_match('/^[0-9]*$/i',$id)){
            try{
                $sql = database::$conn->prepare("SELECT * FROM $this->table WHERE id = '$id'");
                $sql->execute();
                if ($sql->rowCount() == 0){
                    die("this id does not exist in database");
                    exit();
                }
                $row = $sql->fetch(PDO::FETCH_ASSOC);
                return $row;
            }catch (PDOException $e){
                die($e);
            }
        }else{
            die("Invalid Id");
        }
    } //AND preg_match('/^[a-zA-Z0-9_@+.!?]*$/i',$password)
    public function Login($username,$password){
        $username_pattern = '/^[a-zA-Z0-9-_]*$/i';
        $password_pattern = '/^[a-zA-Z0-9-_+?!\s]*$/i';
        if (isset($username) AND isset($password) AND !empty($username) AND !empty($password)){
            if (preg_match($username_pattern,$username) AND preg_match($password_pattern,$password)){
                $password = sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5($password, 'SUPER_SALTY'))))))))))))));
                try{
                    $check = database::$conn->prepare("SELECT * FROM $this->table WHERE Username = '$username' AND Password = '$password'");
                    $check->execute();
                    if ($check->rowCount() == 0){
                        $_SESSION['msg'] = "Wrong Login Details";
                        header("location:login");
                        exit();
                    }
                    $rows = $check->fetch(PDO::FETCH_ASSOC);
                    switch ($rows['Status']){
                        case '0':
                            $_SESSION['msg'] = "This user is not active";
                            header("location:login");
                            exit();
                            break;
                        case '101':
                            $_SESSION['msg'] = "This user is Banned";
                            header("location:login");
                            exit();
                            break;
                    }
                    $_SESSION['Certificate_Code'] = md5(sha1(md5(sha1(md5(sha1(md5(rand(1000,9999))))))));
                    $Last_ip = $_SERVER['REMOTE_ADDR'];
                    $Last_login = date("Y/m/d h:i:sa");
                    $update = database::$conn->prepare("UPDATE $this->table SET Session_id = '$_SESSION[Certificate_Code]' , Last_ip = '$Last_ip' , Last_Login = '$Last_login' WHERE Username = '$rows[Username]'");
                    $update->execute();
                    if ($update->execute()){
                        $_SESSION['Login'] = 'TRUE';
                    }else{
                        $_SESSION['Login'] = 'FALSE';
                    }
                    return $_SESSION['Login'];
                }catch (PDOException $e){
                    die("Error in PDO : ".$e);
                }
            }
            else{
                die("Invalid Values");
            }
        }elseif (!isset($username) OR !isset($password) OR empty($username) OR empty($password)){
            $_SESSION['msg'] = "Username Or Password Didn't Set!";
            header("location:login");
            exit();
        }
    }
    public function getPermission($certificate_code){
        if (isset($_SESSION['Login']) AND $_SESSION['Login'] === 'TRUE'){
            if (isset($certificate_code) AND preg_match('/^[a-z0-9]*$/i',$certificate_code)){
                try{
                    $checkCeritification = database::$conn->prepare("SELECT * FROM $this->table WHERE Session_id = '$certificate_code'");
                    $checkCeritification->execute();
                    if ($checkCeritification->rowCount() === 0){
                        die("Unknown Certification Code!");
                        exit();
                    }
                    $user_info = $checkCeritification->fetch(PDO::FETCH_ASSOC);
                    switch ($user_info['Status']){
                        case "1":
                            self::$permission = "Normal-Client";
                            break;
                        case "2":
                            self::$permission = "Administrator";
                            break;
                        case "3":
                            self::$permission = "Co-Admin";
                    }
                    return self::$permission;
                }catch (PDOException $e){
                    die("Error in checking certification code");
                }
            }
            elseif (!isset($certificate_code) OR !preg_match('/^[a-z0-9]*$/i',$certificate_code)){
                die("Invalid Certification Code");
            }
        }
        elseif (!isset($_SESSION['Login']) OR $_SESSION['Login'] !== 'TRUE'){
            die("Technically , you are not login in this site. please re-login.");
        }
    }
}
/*
if ($_SERVER['REQUEST_URI'] == '/test/admin/news.php' ){
	header("location:admin");
}*/