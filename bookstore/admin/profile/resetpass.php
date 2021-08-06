<?php
if (isset($_GET['f'])){
    if ($_GET['d']  == 'profile'){
        if ($_SERVER['REQUEST_URI'] != '/bookstore/admin/index.php?d=profile&f=resetpass'){
            die("404 - Not Found");
        }
        else{
            if (isset($_SESSION['degree'])){
                if (isset($_COOKIE['id'])){
                    $cookie_id = explode("-",$_COOKIE['id']);
                    if ($cookie_id['0'] == $_SESSION['degree']){
                        if (isset($_COOKIE['user_info'])){
                            $_SESSION['profile'] = $_SESSION['user'];
                            try{
                                $profileconn = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
                                $profileconn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                $psql = $profileconn->prepare("SELECT * FROM user_tbl WHERE Username = '$_SESSION[user]'");
                                $psql->execute();
                                $rows = $psql->fetch(PDO::FETCH_ASSOC);
                            }
                            catch (PDOException $e){
                                die($e);
                            }
                            if (isset($_POST['edit'])){
                                $OPassword = $_POST['OPassword'];
                                if (preg_match('/^[a-zA-Z0-9_@+.!?]*$/i',$OPassword)){
                                    $OPassword =sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5($_POST['OPassword'],'SUPER_SALTY'))))))))))))));
                                    if ($OPassword === $rows['Password']){
                                        $Password1 = $_POST['Password1'];
                                        if (preg_match('/^[a-zA-Z0-9_@+.!?]*$/i',$Password1)){
                                            if ($Password1 === $_POST['OPassword']){
                                                $_SESSION['msg'] = 'Your New Password can not be your old ONE!';
                                            }
                                            else{
                                                $Password2 = $_POST['Password2'];
                                                if (preg_match('/^[a-zA-Z0-9_@+.!?]*$/i',$Password2)){
                                                    if ($Password1 === $Password2){
                                                        $NewPassword = sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5(sha1(md5($Password2,'SUPER_SALTY'))))))))))))));
                                                        try{
                                                            $profileEdit = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
                                                            $profileEdit->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                                            $esql = $profileEdit->prepare("UPDATE user_tbl SET Password = '$NewPassword' WHERE Username='$_SESSION[user]' AND Password = '$OPassword'");
                                                            $esql->execute();
                                                            header("refresh:1;url=http://localhost/bookstore/logout.php");
                                                            echo '<script>alert("Your Password had changed successfully!.................. Re-login For Sure!     ");</script>';
                                                        }
                                                        catch (PDOException $e){
                                                            die($e);
                                                        }
                                                    }
                                                    else{
                                                        $_SESSION['msg'] = 'Your New Passwords are Not Match!';
                                                    }
                                                }
                                                else{
                                                    $_SESSION['msg'] = 'Invalid Password Value';
                                                }
                                            }
                                        }
                                        else{
                                            $_SESSION['msg'] = 'Invalid Password Value';
                                        }
                                    }
                                    else{
                                        $_SESSION['msg'] = 'Your Old Password is not true';
                                    }
                                }
                                else{
                                    $_SESSION['msg'] = 'Invalid Old Password';
                                    //header("location:index.php?f=profile_edit&BadInput=Username");
                                }
                            }
                        }
                        elseif (!isset($_COOKIE['user_info'])){
                            header("location:http://localhost/bookstore/404.php");
                        }
                    }
                    else{
                        unset($_SESSION['degree']);
                        CookieDelete("id");
                        header("location:http://localhost/bookstore/404.php");
                    }
                }
                elseif (!isset($_COOKIE['id'])){
                    unset($_SESSION['degree']);
                    header("location:http://localhost/bookstore/404.php");
                }
            }
            elseif (!isset($_SESSION['degree'])){
                header("location:http://localhost/bookstore/404.php");
            }
        }
    }
    else{
        die("404 - Not Found");
        //header("location:http://localhost/bookstore/404.php");
    }
}
elseif (!isset($_GET['f'])){
    die("404 - Not Found");
    //header("location:http://localhost/bookstore/404.php");
}
?>
<?php if (isset($_SESSION['profile'])){?>
    <form method="post">
        <input type="password" name="OPassword" placeholder="Old Password" class="input">
        <input type="password" name="Password1" placeholder="New Password" class="input">
        <input type="password" name="Password2" placeholder="Re-Type To be sure" class="input">
        <input type="submit" name="edit" value="Save" class="submit" style="display: block;">
        <p style="color: white;font-size: 20px;text-align: center;">
            <?php
            if (isset($_SESSION['msg'])){
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </p>
    </form>
<?php }elseif (!isset($_SESSION['profile'])){die("404 - Not Found");}?>