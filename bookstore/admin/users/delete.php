<?php
if (isset($_SESSION['users'])){
    if (isset($_SESSION['id'])){
        if ($_SERVER['REQUEST_URI'] !== '/bookstore/admin/index.php?d=users&f=index&action=delete&id='.$_SESSION['id']){
            //header("location:http://localhost/bookstore/404.php");
            die("404 - Not Found");
        }
        else{
            if (isset($_SESSION['degree'])){
                $_SESSION['users_delete'] = "users_delete";
                if (isset($_POST['Delete'])){
                    try{
                        $sqldelete = $conn->prepare("DELETE FROM user_tbl WHERE id = '$_SESSION[id]'");
                        $sqldelete->execute();
                        USession('users_delete');
                        USession('id');
                        echo '<script>alert("This User Have Been Delete By Administrator.");</script>';
                        header("refresh:0;url=http://localhost/bookstore/admin/index.php?d=users&f=index");
                    }
                    catch (PDOException $e){
                        die($e);
                    }
                }
                elseif (isset($_POST['Cancel'])){
                    USession('users_delete');
                    USession('id');
                    header("location:http://localhost/bookstore/admin/index.php?d=users&f=index");
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
<?php if (isset($_SESSION['users_delete'])){?>
    <input type="submit" name="Delete" class="submit" value="Yes, I'm Sure" style="display: block;">
    <input type="submit" name="Cancel" class="submit" value="Cancel It" style="display: block;">
<?php }elseif (!isset($_SESSION['users_delete'])){die("404 - Not Found");}?>