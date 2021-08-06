<?php
if (isset($_GET['f'])){
    if ($_GET['d']  == 'profile'){
        if ($_SERVER['REQUEST_URI'] != '/bookstore/admin/index.php?d=profile&f=edit'){
            die("404 - Not Found");
        }
        else{
            if (isset($_SESSION['degree'])){
                if (isset($_COOKIE['id'])){
                    $cookie_id = explode("-",$_COOKIE['id']);
                    if ($cookie_id['0'] == $_SESSION['degree']){
                        if (isset($_SESSION['user'])){
                            $_SESSION['profile'] = $_SESSION['user'];
                            try{
                                $profileconn = new PDO("mysql:hostname=$host;dbname=$db",$user,$pass);
                                $profileconn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                                $psql = $profileconn->prepare("SELECT * FROM user_tbl WHERE Username = '$_SESSION[user]'");
                                $psql->execute();
                                $rows = $psql->fetch(PDO::FETCH_ASSOC);
                                if ($rows['Username'] !== $_SESSION['user']){
                                    header("refresh:1;url=http://localhost/bookstore/logout.php");
                                    echo '<script>alert("You had better Re-login!");</script>';
                                }
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
                                            $esql = $profileEdit->prepare("UPDATE user_tbl SET Username= '$Username' , Age= '$Age' WHERE Username='$_SESSION[user]'");
                                            $esql->execute();
                                            header("refresh:0");
                                            echo '<script>alert("Your Info had changed successfully!");</script>';
                                        }
                                        catch (PDOException $e){
                                            die($e);
                                        }
                                    }
                                    else{
                                        header("location:index.php?f=profile_edit&BadInput=Age");
                                    }
                                }
                                else{
                                    header("location:index.php?f=profile_edit&BadInput=Username");
                                }
                            }
                        }
                        elseif (!isset($_SESSION['user'])){

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
        <input type="text" value="<?php echo $rows['Username'];?>" name="Username" placeholder="Username">
        <input type="age" value="<?php echo $rows['Age'];?>" name="Age" placeholder="Age">
        <input type="submit" name="edit" value="Save">
        <a href="http://localhost/bookstore/admin/index.php?d=profile&f=resetpass">Reset Your Password</a>
    </form>
<?php }elseif (!isset($_SESSION['profile'])){die("404 - Not Found");}?>