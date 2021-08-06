<?php
if (isset($_SESSION['user'])){
            if ($_SERVER['REQUEST_URI'] != '/bookstore/account/me_edit'){
                die("404 - Not Found");
            }
            else{
                if (isset($_SESSION['degree'])){
                    if (isset($_COOKIE['id'])){
                        $cookie_id = explode("-",$_COOKIE['id']);
                        if ($cookie_id['0'] == $_SESSION['degree']){
                            $_SESSION['profile'] = $_SESSION['user'];
                            try{
                                $profileconn = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
                                $profileconn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                $psql = $profileconn->prepare("SELECT * FROM user_tbl WHERE Username = '$_SESSION[user]' AND Status = '1'");
                                $psql->execute();
                                $rows = $psql->fetch(PDO::FETCH_ASSOC);
                            }
                            catch (PDOException $e){
                                die($e);
                            }
                            if (isset($_POST['edit'])){
                                $Username = $_POST['Username'];
                                if (preg_match('/^[a-zA-Z0-9_@]*$/i',$Username)){
                                    $Age = $_POST['Age'];
                                    if (preg_match('/^[0-9]*$/i',$Age)){
                                        try{
                                            $profileEdit = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
                                            $profileEdit->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                            $esql = $profileEdit->prepare("UPDATE user_tbl SET Username= '$Username' , Age= '$Age' WHERE Username='$_SESSION[user]' AND Status = '1'");
                                            $esql->execute();
                                            header("refresh:0");
                                            echo '<script>alert("Your Info had changed successfully!");</script>';
                                        }
                                        catch (PDOException $e){
                                            die($e);
                                        }
                                    }
                                    else{
                                        $_SESSION['msg'] = "Invalid Age";
                                    }
                                }
                                else{
                                    $_SESSION['msg'] = "Invalid Username";
                                }
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
elseif (!isset($_SESSION['user'])){
    header("location:../../404.php");
}
?>
<?php if (isset($_SESSION['profile'])){?>
    <form method="post">
    <input type="text" value="<?php echo $rows['Username'];?>" name="Username" placeholder="Username">
    <input type="age" value="<?php echo $rows['Age'];?>" name="Age" placeholder="Age">
    <input type="submit" name="edit" value="Save">
    </form>
<?php }elseif (!isset($_SESSION['profile'])){die("404 - Not Found");}?>
