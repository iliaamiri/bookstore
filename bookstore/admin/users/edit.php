<?php
if (isset($_SESSION['users'])){
    if (isset($_SESSION['id'])){
        if ($_SERVER['REQUEST_URI'] !== '/bookstore/admin/index.php?d=users&f=index&action=edit&id='.$_SESSION['id']){
            //header("location:http://localhost/bookstore/404.php");
            die("404 - Not Found");
        }
        else{
            if (isset($_SESSION['degree'])){
                $_SESSION['users_edit'] = "users_edit";
                try{
                    $sqll = $conn->prepare("SELECT * FROM user_tbl WHERE id = '$_SESSION[id]'");
                    $sqlcheckadmin = $conn->prepare("SELECT * FROM user_tbl WHERE id = '$_SESSION[id]' AND Status = '2'");
                    $sqll->execute();
                    $count = $sqll->rowCount();
                    if ($count === 0){
                        echo 'Nothing Found To Edit';
                        exit();
                    }
                    $sqlcheckadmin->execute();
                    if ($sqlcheckadmin->rowCount() > 0){
                        echo '<script>alert("This id can not be delete, try the others from users list.");</script>';
                        header("refresh:0;url=http://localhost/bookstore/admin/index.php?d=users&f=index");
                    }
                    $rows = $sqll->fetch(PDO::FETCH_ASSOC);
                }
                catch (PDOException $e){
                    die($e);
                }
                if (isset($_POST['Edit'])){
                    $Username = $_POST['Username'];
                    if (preg_match('/^[a-zA-Z0-9_@]*$/i',$Username)){
                        $Email = $_POST['Email'];
                        if (preg_match('/[a-z._%+-]+@[a-z.-]+\.[a-z]{2,4}\b/i',$Email)){
                            $Age = $_POST['Age'];
                            if (preg_match('/^[0-9]*$/i',$Age)){
                                $Degree = $_POST['Degree'];
                                if (preg_match('/^[0-9]*$/i',$Degree)){
                                        try{
                                            $sql2 = $conn->prepare("UPDATE user_tbl SET Username = '$Username',Email ='$Email',Age = '$Age' ,Status = '$Degree' WHERE id = '$_SESSION[id]'");
                                            $sql2->execute();
                                            USession('users_edit');
                                            USession('id');
                                            echo '<script>alert("DataBase Have Changed successfully!");</script>';
                                            header("refresh:0;url=http://localhost/bookstore/admin/index.php?d=users&f=index");
                                        }
                                        catch (PDOException $e){
                                            die($e);
                                        }
                                }
                                else{
                                    $_SESSION['msg'] = "Invalid Degree Value!";
                                }
                            }
                            else{
                                $_SESSION['msg'] = "Invalid Age Value!";
                            }
                        }
                        else{
                            $_SESSION['msg'] = "Invalid Email Value!";
                        }
                    }
                    else{
                        $_SESSION['msg'] = "Invalid Username Value!";
                    }
                }
            }
            elseif (!isset($_SESSION['degree'])){
                USession('id');
                USession('users');
                CookieDelete('user_info');
            }
        }
    }
    elseif (!isset($_SESSION['id'])){
        die("404 - Not Found");
        //header("location:http://localhost/bookstore/404.php");
    }
}
elseif (!isset($_SESSION['users'])){
    header("location:http://localhost/bookstore/404.php");
}
?>
<?php if (isset($_SESSION['users_edit'])){?>
    <input type="text" name="Username" class="input" placeholder="Username" required="required" value="<?php echo $rows['Username'];?>">
    <input type="email" name="Email" class="input" placeholder="Email" required="required" value="<?php echo $rows['Email'];?>">
    <input type="text" name="Age" class="input" placeholder="Age" required="required" value="<?php echo $rows['Age'];?>">
    <div style="display: block;margin-left: 100px;">
        <select name="Degree">
            <option value="1">Normal Client</option>
            <option value="3">Co-Admin</option>
            <option value="404">Ban By Admin</option>
        </select>
    </div>
    <input type="submit" name="Edit" class="submit" value="Edit" style="display: block;">
<?php }elseif (!isset($_SESSION['users_edit'])){die("404 - Not Found");}?>

